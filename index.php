<?php

require_once './data.php';
require './helpers.php';

$is_auth = rand(0, 1);

function price_format($number)
{
    return number_format(ceil($number), 0, ',', ' ');
}

$content = include_template('./index.php', [
    'categories' => $categories,
    'lots' => $lots,
]);

$layout = include_template('./layout.php', [
    'title' => "Главная",
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'categories' => $categories,
]);

print($layout);
