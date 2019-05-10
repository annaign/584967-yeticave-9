<?php

declare(strict_types=1);

require_once './init.php';

$user_name = 'User';
$is_auth = rand(0, 1);

// --- Получение данных ---

$categories = get_categories($link);
$lots = get_lots($link);

// --- Сборка главной страницы ---

$content = include_template('./index.php', [
    'categories' => $categories,
    'lots' => $lots,
]);

$layout = include_template('./layout.php', [
    'title' => "Главная",
    'main_page_wrap' => "container",
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'content' => $content,
    'categories' => $categories,
]);

print($layout);
