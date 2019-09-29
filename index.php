<?php

// Enable output buffering
ob_start();  

include_once 'app/core/Database.php';
include_once 'app/core/Core.php';

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);

switch ($request_uri[0]) {
    case '/tuckshop/login' :
        include_once 'app/pages/login.php';
        break;
    case '/tuckshop/' :
        include_once 'app/pages/login.php';
        break;
    case '/tuckshop/signup' :
        include_once 'app/pages/signup.php';
        break;
    case '/tuckshop/products' :
        include_once 'app/pages/products.php';
        break;
    case '/tuckshop/product' :
        include_once 'app/pages/product.php';
        break;
    case '/tuckshop/productdelete' :
        include_once 'app/pages/productdelete.php';
        break;
    case '/tuckshop/logout' :
        include_once 'app/pages/logout.php';
        break;
    case '/tuckshop/export' :
        include_once 'app/pages/exportorders.php';
        break;
    default :
        include_once 'app/pages/404.html';
}
