<?php

include_once 'app/core/Core.php';

session_start();

if (isset($_SESSION['username']) && isset($_SESSION['email'])) {
    header("Location: ".SITE_URL."/products");
}

$unauthenticated = 0;
$errorMessage = '';
if (isset($_POST['email'])) {
    include_once 'app/objects/AdminUsers.php';
    include_once 'app/core/Database.php';

    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    $usersObj = new AdminUsers($db);
    
    $userData = $usersObj->getUserByEmail($_POST['email'])->fetch(\PDO::FETCH_ASSOC);
    
    if (!isset($userData['user_id'])) {
       $unauthenticated = 1;
       $errorMessage = 'User Not Found';
    } else if ($userData['password'] == md5($_POST['password'])) {
        $_SESSION['username'] = $userData['username'];
        $_SESSION['email'] = $userData['email'];

        header("Location: ".SITE_URL."/products");
    } else {
        $errorMessage = 'Password Not Valid';
        $unauthenticated = 1;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Login</title>

        <!-- Font Icon -->
        <link rel="stylesheet" type="text/css" href="fonts/material-icon/css/material-design-iconic-font.min.css">

        <!-- Main css -->
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" type="text/css" href="css/theme.css">
    </head>
    <body>
        <div class="main">
            <section class="signup">
                <!-- <img src="images/signup-bg.jpg" alt=""> -->
                <div class="container">
                    <div class="signup-content">
                        <form method="POST" action="" id="login-form" class="login-form" onsubmit="return validateLogin(this)">
                            <h2 class="form-title">Login</h2>
                            <?php if ($unauthenticated) { ?>
                                <div class="login-alert-error" role="alert">
                                    <?= $errorMessage; ?>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <input type="text" class="form-input" name="email" id="email" placeholder="Your Email" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>"/>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-input" name="password" id="password" placeholder="Password"/>
                                <span toggle="#password" class="zmdi zmdi-eye-off field-icon toggle-password"></span>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" id="submit" class="form-submit" value="Login"/>
                            </div>
                        </form>
                        <p class="loginhere" style="display: none;">
                            Don't have an account? <a href="<?= SITE_URL.'/signup' ?>" class="loginhere-link">Create here</a>
                        </p>
                    </div>
                </div>
            </section>
        </div>

        <!-- JS -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>