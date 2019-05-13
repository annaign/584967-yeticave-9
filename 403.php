<?php

declare (strict_types = 1);

require_once './init.php';

$user_name = 'User';
$is_auth = rand(0, 1);

// --- Получение данных ---

$categories = get_categories($link);

// --- Сборка главной страницы ---

$menu_general = include_template('./menu_general.php', [
    'categories' => $categories
]);

$content = include_template('./403.php', []);

$layout = include_template('./layout.php', [
    'title' => "403 Доступ к странице запрещен",
    'session_user' => $session_user,
    'menu' => $menu_general,
    'content' => $content,
    'categories' => $categories,
]);

print($layout);
