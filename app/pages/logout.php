<?php 

include_once 'app/core/Core.php';

session_start();
session_destroy();

header("Location: ".SITE_URL);