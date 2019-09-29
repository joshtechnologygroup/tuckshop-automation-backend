<?php

class Order {
 
    // database connection and table name
    private $conn;
    private $table_name = "tuckshop_orders";
    private $ot_table_name = "tuckshop_order_item";
    private $user_table_name = "jtg_users";
    private $product_table_name = "tuckshop_products";
 
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }
    
    private function getUserData($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM ".$this->user_table_name." WHERE user_id = ".(int) $userId." LIMIT 1"); 
        $stmt->execute();
        return $stmt->fetch();
    }

    private function getProductData($productId) {
        $stmt = $this->conn->prepare("SELECT * FROM ".$this->product_table_name." WHERE product_id = ".(int) $productId." LIMIT 1"); 
        $stmt->execute();
        return $stmt->fetch();
    }

    function postOrder($requestPayLoad = '') {
        $payLoad = json_decode($requestPayLoad, true);
        $response = [];
        
        if (isset($payLoad['products']) && count($payLoad['products']) && $payLoad['user_id']) {
            // query to insert order record
            $queryOrder = "INSERT INTO " . $this->table_name . "
                (user_id, grand_total, customer_email, total_item_count)
                values(?,?,?,?)";
            
            $totalProduct = 0;
            $grandTotal = 0;
            try {
                foreach ($payLoad['products'] as $product) {
                    $productData = $this->getProductData($product['product_id']);
                    
                    $totalProduct += (int) $product['qty'];
                    $grandTotal += (float) ($productData['price'] * (int) $product['qty']);
                }
                
                $userData = $this->getUserData($payLoad['user_id']);
                $orderQueryObj = $this->conn->prepare($queryOrder);
                $this->conn->beginTransaction();
                $orderQueryObj->execute(
                    [
                        $userData['user_id'],
                        $grandTotal,
                        $userData['email'],
                        $totalProduct
                    ]
                );
                $orderId = $this->conn->lastInsertId();
                
                foreach ($payLoad['products'] as $product) {
                    $productData = $this->getProductData($product['product_id']);

                    // query to insert order record
                    $queryOrderItem = "INSERT INTO " . $this->ot_table_name . "
                        (order_id, product_id, sku, name, qty_ordered, item_price, row_total)
                        values(?,?,?,?,?,?,?)";
                    
                    $orderItemQueryObj = $this->conn->prepare($queryOrderItem);
                    $orderItemQueryObj->execute(
                        [
                            $orderId,
                            (int) $product['product_id'],
                            $productData['sku'],
                            $productData['product_name'],
                            $product['qty'],
                            $productData['price'],
                            $productData['price'] * $product['qty']
                        ]
                    );
                }
                $this->conn->commit();
                $response = [
                    'status' => true,
                    'message' => ''
                ];
            } catch(PDOExecption $e) {
                $this->conn->rollback();
                $response = [
                    'status' => false,
                    'message' => 'Unable to create order. Error: '.$e->getMessage()
                ];
            }
        } else {
            $response = [
                'status' => false,
                'message' => 'User or products data missing in request payload, not able to create the order'
            ];
        }
        
        return $response;
    }
    
    public function getOrdersForMonth($month) {
        $orderData = [];
        if ($month) {
            $monthYear = explode('-', $month);

            $dataMonth = isset($monthYear[0]) ? $monthYear[0] : date('m');
            $dataYear = isset($monthYear[1]) ? $monthYear[1] : date('Y');
            $orderQuery = 'SELECT customer_email, SUM(total_item_count) as items_count,
                SUM(grand_total) as order_total FROM '.$this->table_name.' 
                WHERE month(created_at) = '.$dataMonth.'
                and year(created_at) = '.$dataYear.'
                group by customer_email';
            
            // prepare query statement
            $stmt = $this->conn->prepare($orderQuery);

            // execute query
            $stmt->execute();

            $orderData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $orderData;
    }
}