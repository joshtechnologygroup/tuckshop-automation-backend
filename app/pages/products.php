<?php

session_start();

include_once 'app/core/Core.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['email'])) {
    header("Location: ".SITE_URL);
}

include_once 'app/core/Database.php';
include_once 'app/objects/Product.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$productObj = new Product($db);

$products = $productObj->getAllProducts()->fetchAll(\PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Products List</title>
        
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/theme.css">
        <link rel="stylesheet" href="css/main.min.css">
    </head>
    <body>
        <?php include_once 'header.php'; ?>
      
        <div class="container">
            <div class="jumbotron">
                <h1>Tuckshop Products</h1>      
                <p>This list contains all the products that we currently have in our tuckshop.</p>
            </div>
            <?php if (isset($_SESSION['message'])) { ?>
                <div class="alert alert-<?= $_SESSION['message']['type']; ?>" role="alert">
                    <?= $_SESSION['message']['msg']; ?>
                </div>
            <?php 
                    unset($_SESSION['message']);
                } 
            ?>
            <div class="text-right add-button-container">
                <a title="Order Report" href="<?= SITE_URL; ?>/export" class="btn btn-dark">Order Report</a>
                <a title="Add Product" href="<?= SITE_URL; ?>/product" class="btn btn-primary">Add Product</a>
            </div>
            <div class="text-center">
                <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Product Barcode</th>
                        <th scope="col">Product Price</th>
                        <th scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($products as $key => $product) { ?>  
                        <tr>
                            <th scope="row"><?= $key+1; ?></th>
                            <td><?= $product['product_name']; ?></td>
                            <td><?= $product['product_barcode']; ?></td>
                            <td><?= '&#8377; '.formatPrice($product['price']); ?></td>
                            <td>
                                <a title="Edit Product" href="<?= SITE_URL.'/product?id='.(int) $product['product_id']; ?>" class="btn btn-outline-info btn-sm">Edit</a>
                                <a title="Delete Product" onclick="return confirm('Are you sure?')" href="<?= SITE_URL.'/productdelete?id='.(int) $product['product_id']; ?>" class="btn btn-outline-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>