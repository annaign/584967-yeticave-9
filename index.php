<?php

declare (strict_types = 1);
require_once './init.php';

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
    'main_page' => true,
    'session_user' => $session_user,
    'menu' => $menu_main,
    'content' => $content,
    'categories' => $categories,
]);

print($layout);

require_once './getwinner.php';
