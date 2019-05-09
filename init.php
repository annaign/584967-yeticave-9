<?php

require_once './helpers.php';
require_once './functions.php';

$link = mysqli_connect("localhost", "root", "", "yeticave");

if ($link === false) {
    $error = mysqli_connect_error();
    $error_page = include_template('./error.php', ['error' => $error]);
    print($error_page);
    die();
}

mysqli_set_charset($link, "utf8");
