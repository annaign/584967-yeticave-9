<?php

declare (strict_types = 1);

require_once './init.php';

$user_name = 'User';
$is_auth = rand(0, 1);

// --- Получение данных ---

$categories = get_categories($link);
$lots = get_lots($link);

// --- Сборка главной страницы ---

$menu_main = include_template('./menu_main.php', [
    'categories' => $categories
]);

$content = include_template('./index.php', [
    'categories' => $categories,
    'lots' => $lots,
]);

$layout = include_template('./layout.php', [
    'title' => "Главная",
    'add_lot_style' => "",
    'main_page' => true,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'menu' => $menu_main,
    'content' => $content,
    'categories' => $categories,
]);

print($layout);
