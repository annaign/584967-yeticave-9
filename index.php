<?php

require_once './data.php';
require './helpers.php';

$is_auth = rand(0, 1);

function price_format($number)
{
    return number_format(ceil($number), 0, ',', ' ');
}

function seconds_before_midnight($time_format = false)
{
    $current_time = time();
    $midnight = strtotime("tomorrow midnight");
    $diff_seconds = $midnight -  $current_time;

    if (!$time_format) {
        if ($diff_seconds <= 60 * 60) {
            return true;
        }
        return false;
    }

    return date($time_format, strtotime("today midnight") + $diff_seconds);
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
