<?php

declare (strict_types = 1);

require_once './init.php';

$user_name = 'User';
$is_auth = rand(0, 1);

// --- Получение данных ---

$categories = get_categories($link);

if (isset($_GET['id'])) {
    //защита от SQL-инъекции через приведение к числовому типу
    $id = intval($_GET['id']);
} else {
    header("Location: /pages/404.html");
}

$lot = get_lot_by_id($link, $id);

// --- Сборка страницы с лотом ---

$menu_lot = include_template('./menu_lot.php', [
    'categories' => $categories
]);

$content = include_template('./lot.php', [
    'categories' => $categories,
    'lot' => $lot,
]);

$layout = include_template('./layout.php', [
    'title' => $lot['lot_title'],
    'main_page_wrap' => "",
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'menu' => $menu_lot,
    'content' => $content,
    'categories' => $categories,
]);

print($layout);
