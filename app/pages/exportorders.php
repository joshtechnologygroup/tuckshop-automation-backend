<?php
session_start();

include_once 'app/core/Core.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['email'])) {
    header("Location: " . SITE_URL);
}

if (isset($_POST['export_month'])) {
    include_once 'app/core/Database.php';
    include_once 'app/objects/Order.php';

    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    $orderObj = new Order($db);

    $ordersData = $orderObj->getOrdersForMonth($_POST['export_month']);
    
    $fileName = 'tuckshop_orders_'.$_POST['export_month'].'.csv';
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=".$fileName);
    header("Pragma: no-cache");
    header("Expires: 0");
    
    array_unshift($ordersData, ['Email', 'Total Product Bought', 'Grand Total']);
    $output = fopen("php://output", "w");
    foreach ($ordersData as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit(0);
}

$months = getLastNMonthsOptionsArray(5);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Order Report</title>

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/theme.css">
        <link rel="stylesheet" href="css/main.min.css">
    </head>
    <body>
        <?php include_once 'header.php'; ?>

        <div class="container">
            <div class="jumbotron">
                <h1>Export Orders</h1>      
                <p>You can export the orders summary for past months per user from here. The data will be exported as a CSV file.</p>
            </div>
            <div class="text-center">
                <form action="" method="post" style="text-align: left;">
                    <div class="col-auto my-1 w-25">
                        <label class="mr-sm-2" for="export_month">Select Month</label>
                        <select class="custom-select mr-sm-2" name="export_month">
                            <?php foreach ($months as $month) { ?>
                                <option value="<?= $month['value']; ?>"><?= $month['label']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-auto padding-top-10">
                        <button type="submit" class="btn btn-primary mb-2">Export Data</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>