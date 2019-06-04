<?php

declare (strict_types = 1);
require_once './init.php';

// --- Получение данных из БД ---
$categories = get_categories($link);

// --- Сборка страницы со ставками ---
$menu_general = include_template('./menu_general.php', [
    'categories' => $categories,
]);

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    // --- Сборка главной страницы ---

    $content = include_template('./403.php', []);

    $layout = include_template('./layout.php', [
        'title' => "403 Доступ к странице запрещен",
        'session_user' => $session_user,
        'menu' => $menu_general,
        'content' => $content,
        'categories' => $categories,
    ]);

    print($layout);
    exit();
}

// --- Получение данных из БД ---
$user_bets = get_bets_by_user_id($link, $_SESSION['user']);

// --- Сборка страницы со ставками ---
$content = include_template('./my-bets.php', [
    'categories' => $categories,
    'user_bets' => $user_bets,
]);

$layout = include_template('./layout.php', [
    'title' => 'Добавление лота',
    'add_lot_style' => '<link href="../css/flatpickr.min.css" rel="stylesheet">',
    'session_user' => $session_user,
    'menu' => $menu_general,
    'content' => $content,
    'categories' => $categories,
]);

print($layout);
