<?php

include_once 'app/core/Core.php';
include_once 'app/core/Database.php';
include_once 'app/objects/Product.php';

session_start();

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$productObj = new Product($db);

$productId = getRequestData('id');

if ($productObj->deleteProductById((int) $productId)) {
    $_SESSION['message'] = ['type' => 'success', 'msg' => 'Product Successfully Deleted!'];
} else {
    $_SESSION['error'] = ['type' => 'error', 'msg' => 'Something went wrong while deleting the product, please try again!'];
}

header("Location: ".SITE_URL."/products");
