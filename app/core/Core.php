<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

CONST SITE_URL = 'http://localhost/tuckshop';

function getRequestData($key, $default = null) 
{
    if (isset($_POST[$key])) {
        return $_POST[$key];
    } else if (isset($_GET[$key])) {
        return $_GET[$key];
    } else {
        return $default;
    }
}

function formatPrice($amount)
{
    setlocale(LC_MONETARY, 'en_IN');
    return money_format('%!i', $amount);
}

function getLastNMonthsOptionsArray($count = 1)
{
    $optionsArray = [];
    $index = 0;
    while ($count > 0) {
        $optionsArray[$index]['label'] = date("M, Y", mktime(0, 0, 0, date('m') - $index, date('d'), date('Y')));;
        $optionsArray[$index]['value'] = date("m-Y", mktime(0, 0, 0, date('m') - $index, date('d'), date('Y')));;
        $index++;
        $count--;
    }
    
    return $optionsArray;
}