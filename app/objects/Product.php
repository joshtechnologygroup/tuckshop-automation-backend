<?php

class Product {
 
    // database connection and table name
    private $conn;
    private $table_name = "tuckshop_products";
 
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    function getAllProducts() {
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " where is_active = 1 ORDER BY created_at DESC";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    function getProductByBarcode($barcode = '') {
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " where is_active = 1 and product_barcode = ".$barcode;
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }
    
    function getProductById($id = '') {
        if ($id) {
            // select all query
            $query = "SELECT * FROM " . $this->table_name . " where is_active = 1 and product_id = ".$id;

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // execute query
            $stmt->execute();

            return $stmt;
        }
        return false;
    }
    
    function deleteProductById($id = '') {
        if ($id) {
            // query to update product record
            $queryProductUpdate = "UPDATE " . $this->table_name . " SET
                is_active = 0 where product_id = ?";

            $productQueryObj = $this->conn->prepare($queryProductUpdate);
            $this->conn->beginTransaction();
            $productQueryObj->execute([$id]);
            
            $this->conn->commit();
            return true;
        }
        return false;
    }
    
    function createUpdateProduct($productData = [], $productId = 0) {
        if (count($productData)) {
            try {
                if ($productId) {
                    // query to update product record
                    $queryProductUpdate = "UPDATE " . $this->table_name . " SET
                        product_image = ?, product_desc = ?, product_name = ?, product_barcode = ?, price = ?
                        where product_id = ?";
                    
                    $productQueryObj = $this->conn->prepare($queryProductUpdate);
                    $this->conn->beginTransaction();
                    $productQueryObj->execute(
                        [
                            $productData['image'],
                            $productData['desc'],
                            $productData['name'],
                            $productData['barcode'],
                            $productData['price'],
                            $productId
                        ]
                    );
                } else {
                    // query to insert product record
                    $queryProductInsert = "INSERT INTO " . $this->table_name . "
                        (product_image, product_desc, product_name, product_barcode, price)
                        values(?,?,?,?,?)";

                    $productQueryObj = $this->conn->prepare($queryProductInsert);
                    $this->conn->beginTransaction();
                    $productQueryObj->execute(
                        [
                            $productData['image'],
                            $productData['desc'],
                            $productData['name'],
                            $productData['barcode'],
                            $productData['price']
                        ]
                    );
                }
                $this->conn->commit();
                return true;
            } catch(PDOExecption $e) {
                $this->conn->rollback();
                return false;
            }
        } else {
            return false;
        }
    }
}