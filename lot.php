<?php

declare (strict_types = 1);
require_once './init.php';

// --- Получение данных ---

$categories = get_categories($link);

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    page_404();
}

$lot = get_lot_by_id($link, $id);
$lot_bets = get_bets_by_lot_id($link, $id);

//массив ошибок при заполнении формы
$bet_errors = [];
$errors_message = [
    'cost' => 'Введите вашу ставку, целое положительное число',
];
$show_bet_form = $session_user['is_auth'] === 1;

//Блок добавления ставки не показывается
if ($show_bet_form) {
    $lot_creator = get_lot_by_id($link, $id);
    $bet_last_user = get_bets_by_lot_id($link, $id);
    $bet_last_user = $bet_last_user[0] ?? null;

    if (
        check_time2($lot_creator['lot_date_end']) ||
        $bet_last_user && $bet_last_user['user_id'] === $_SESSION['user'] ||
        $lot_creator['user_id'] === $_SESSION['user']
    ) {
        $show_bet_form = false;
    }
}

// --- Получение данных из формы ---

//проверка: форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_bet = $_POST;

    //проверка: ставка
    $new_bet['cost'] = isset($new_bet['cost']) ? trim($new_bet['cost']) : '';
    if (!is_numeric($new_bet['cost'])) {
        $bet_errors['cost'] = true;
        $errors_message['cost'] = 'Введите число';
    } else {
        $new_bet['cost'] = (int)($new_bet['cost']);
        $min_price = ($lot['bet_price'] !== null) ? $lot['bet_price'] + $lot['lot_step'] : $lot['lot_price_start'] + $lot['lot_step'];

        if ($new_bet['cost'] < $min_price) {
            $bet_errors['cost'] = true;
            $errors_message['cost'] = 'Должна быть больше или равна минимальной ставке';
        }
    }

    if (count($bet_errors)) {
        //проверка формы выявила ошибки, отобразить ошибки на странице
        $content = include_template('./lot.php', [
            'categories' => $categories,
            'show_bet_form' => $show_bet_form,
            'lot' => $lot,
            'bet_errors' => $bet_errors,
            'errors_message' => $errors_message,
            'new_bet' => $new_bet,
        ]);
    } else {
        //сохранение новой ставки в БД

        $sql = "INSERT INTO bets (bet_date_create, user_id, lot_id, bet_price)
        VALUES (CURRENT_TIMESTAMP, ?, ?, ?)";

        $new_bet_id =  db_insert_data($link, $sql, [
            $_SESSION['user'],
            $lot['id'],
            $new_bet['cost'],
        ]);

        //при успешном сохранении формы, переадресация на страницу ставок
        if ($new_bet_id) {
            header('Location: /my-bets.php');
            exit();
        }

        $error = 'Ошибка при добавление лота в БД';
        $error_page = include_template('./error.php', ['error' => $error]);
        print($error_page);
        die();
    }
} else {
    $content = include_template('./lot.php', [
        'categories' => $categories,
        'show_bet_form' => $show_bet_form,
        'lot' => $lot,
        'bet_errors' => $bet_errors,
        'errors_message' => $errors_message,
        'lot_bets' => $lot_bets,
    ]);
}

// --- Сборка страницы с лотом ---

$menu_general = include_template('./menu_general.php', [
    'categories' => $categories
]);

$layout = include_template('./layout.php', [
    'title' => $lot['lot_title'],
    'session_user' => $session_user,
    'menu' => $menu_general,
    'content' => $content,
    'categories' => $categories,
]);

print($layout);
