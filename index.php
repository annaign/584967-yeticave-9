<?php

require_once './data.php';
require './helpers.php';

$is_auth = rand(0, 1);

function price_format($number)
{
    return number_format(ceil($number), 0, ',', ' ');
}

function interval_before_midnight()
{
    return date_diff(date_create("now"), date_create("tomorrow midnight"));
}

function check_time($interval, int $sec_time): bool
{
    return ($interval->h * 60 * 60 + $interval->i * 60 + $interval->s <= $sec_time);
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
