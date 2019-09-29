<?php

session_start();

include_once 'app/core/Core.php';

if (!isset($_SESSION['username']) && !isset($_SESSION['email'])) {
    header("Location: ".SITE_URL);
}

include_once 'app/core/Database.php';
include_once 'app/objects/Product.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$productObj = new Product($db);

$productId = getRequestData('id');
$productData = [];
$productImg = '//placehold.it/150';
if ($productId) {
    $pageTitle = 'Edit Product #'.$productId;
    $productData = $productObj->getProductById($productId)->fetch(\PDO::FETCH_ASSOC);

    if (isset($productData['product_image']) && $productData['product_image']) {
        if (strpos($productData['product_image'], 'https://') !== false || strpos($productData['product_image'], 'http://') !== false) {
            $productImg = $productData['product_image'];
        } else {
            $productImg = SITE_URL.'/images/products/'.$productData['product_image'];
        }
    }
} else {
    $productId = 0;
    $pageTitle = 'Add New Product';
}

if (isset($_POST['product_form_submitted'])) {
    $postProductData = $_POST['product'];
    
    $uploadFileDir = './images/products/';

    if (isset($_FILES['product_image']) && $_FILES['product_image']['size']) {
        $fileTmpPath = $_FILES['product_image']['tmp_name'];
        $fileName = $_FILES['product_image']['name'];
        $dest_path = $uploadFileDir . $fileName;
        move_uploaded_file($fileTmpPath, $dest_path);

        $postProductData['image'] = $fileName;
    } else {
        $postProductData['image'] = $productData['product_image'];
    }

    if ($productObj->createUpdateProduct($postProductData, $productId)) {
        $_SESSION['message'] = ['type' => 'success', 'msg' => 'Product Successfully Processed!'];
    } else {
        $_SESSION['error'] = ['type' => 'error', 'msg' => 'Something went wrong while processing the product, please try again!'];
    }
    header("Location: ".SITE_URL."/products");
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= $pageTitle; ?></title>
        
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/theme.css">
        <link rel="stylesheet" href="css/main.min.css">
    </head>
    <body>
        <?php include_once 'header.php'; ?>
      
        <div class="container">
            <div class="page-header padding-top-20">
                <h1><?= $pageTitle; ?></h1>      
            </div>
            <hr>
            <div class="row m-y-2">
                <div class="col-lg-8 push-lg-4">
                    <form role="form" id="product-form" action="" method="post" enctype="multipart/form-data" onsubmit="return validateAndClone(this)">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Product Name</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="text" name="product[name]" value="<?= isset($productData['product_name']) ? $productData['product_name'] : ''; ?>">
                                <input class="form-control" type="hidden" name="product_form_submitted" value="1">
                                <input class="form-control" type="hidden" name="product[id]" value="<?= $productId; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Product Description</label>
                            <div class="col-lg-9">
                                <textarea class="form-control" type="text" name="product[desc]"><?= isset($productData['product_desc']) ? $productData['product_desc'] : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Price</label>
                            <div class="col-lg-3">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon1">&#8377;</span>
                                    </div>
                                    <input type="text" class="form-control" name="product[price]" value="<?= isset($productData['price']) ? $productData['price'] : ''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Barcode</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="text" name="product[barcode]" value="<?= isset($productData['product_barcode']) ? $productData['product_barcode'] : ''; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label"></label>
                            <div class="col-lg-9">
                                <a class="btn btn-secondary" href="<?= SITE_URL; ?>/products">Cancel</a>
                                <input type="submit" class="btn btn-primary" value="Save Changes">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 pull-lg-8 text-xs-center">
                    <img src="<?= $productImg; ?>" class="m-x-auto img-fluid img-circle" id="custom-image-holder" alt="avatar">
                    <input type="file" id="product_image" name="product_image" onchange="validateImageAndUpdatePreview(this)" class="padding-top-10" accept="image/jpg,image/png,image/jpeg,image/gif" value="">
                </div>
            </div>
        </div>

        <!-- JS -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
