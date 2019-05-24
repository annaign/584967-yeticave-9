<?php

declare (strict_types = 1);
require_once './init.php';

if (!isset($_SESSION['user'])) {
    header('Location: /403.php');
    exit();
}

// --- Получение данных из БД ---

$categories = get_categories($link);
$user_bets = get_bets_by_user_id($link, $_SESSION['user']);

// --- Сборка страницы со ставками ---

$menu_general = include_template('./menu_general.php', [
    'categories' => $categories,
]);

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
