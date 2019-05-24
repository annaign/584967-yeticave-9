<?php

require_once './vendor/autoload.php';
require_once './helpers.php';
require_once './functions.php';

$link = mysqli_connect('localhost', 'root', '', 'yeticave');

if ($link === false) {
    $error = mysqli_connect_error();
    $error_page = include_template('./error.php', ['error' => $error]);
    print($error_page);
    die();
}

mysqli_set_charset($link, 'utf8');

session_start();
if (isset($_SESSION['user'])) {
    $login_user_id = $_SESSION['user'];

    $sql = "SELECT user_name FROM users WHERE id = ?";
    $session_user = db_fetch_data($link, $sql, [$_SESSION['user']])[0] ?? null;
    $session_user['is_auth'] = 1;
} else {
    $session_user['is_auth'] = 0;
}
