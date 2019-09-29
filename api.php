<?php

// Enable output buffering
ob_start();

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 

// include database and object files
include_once 'app/core/allowed_keys.php';
include_once 'app/core/Database.php';
include_once 'app/core/Core.php';
foreach (glob('app/objects/*.php') as $filename)
{
    include_once $filename;
}
 
if (in_array(getRequestData('api_secret'), array_map('md5', $apiKeys))) {
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();
    
    $apiRequestType = getRequestData('request_type');

    $classObj = null;
    $method = getRequestData('method');
    $method_param = getRequestData('method_param');
    switch ($apiRequestType) {
        case 'product':
            $classObj = new Product($db);
            break;
        case 'users':
            $classObj = new Users($db);
            break;
        case 'order':
            $classObj = new Order($db);
            break;
    }

    $api_arr = [];
    if ($classObj && $method) {
        if (method_exists($classObj, $method)) {
            if ($method_param) {
                $stmt = $classObj->$method($method_param);
            } else {
                $stmt = $classObj->$method();
            }

            if ($stmt) {
                if (is_array($stmt)) {
                    if (isset($stmt['status']) && $stmt['status']) {
                        $responceCode = 200;
                        $status = 1;
                        $error = '';
                    } else {
                        $responceCode = 503;
                        $status = 0;
                        $error = $stmt['message'];
                    }
                } else {
                    // query products
                    $num = $stmt->rowCount();

                    // check if more than 0 record found
                    if ($num > 0) {         
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            if (isset($row['product_image']) && strpos($row['product_image'], 'http://') === false && strpos($row['product_image'], 'https://') === false) {
                                $row['product_image'] = 'tuckshop/images/products/'.$row['product_image'];
                            }
                            array_push($api_arr, $row);
                        }
                    }
                    $responceCode = 200;
                    $status = 1;
                    $error = '';
                }
            } else {
                $responceCode = 503;
                $status = 0;
                $error = 'Unable to fulfill the request, please try again';
            }
        } else {
            $responceCode = 405;
            $status = 0;
            $error = 'Invalid Method';
        }
    } else {
        $responceCode = 405;
        $status = 0;
        $error = 'Request Type or Method Missing';
    }
    http_response_code($responceCode);
 
    $response = [
        'status'=> $status,
        'response_code' => $responceCode,
        'error'=> $error,
        'data' => $api_arr
    ];

    echo json_encode($response);
} else {
    // set response code - 401 Unauthorized
    http_response_code(401);
    echo json_encode(['status'=> 0, 'response_code' => 400, 'error' => 'Unauthorized', 'data' => []]);
}