<?php

declare (strict_types = 1);
require_once './init.php';

if (!isset($_SESSION['user'])) {
    page_403();
}

// --- Получение данных из БД ---

$categories = get_categories($link);

//массив ошибок при заполнении формы
$lot_errors = [];
$errors_message = [
    'lot-name' => 'Введите наименование лота',
    'category' => 'Выберите категорию',
    'message' => 'Напишите описание лота',
    'lot-img' => 'Не загружен файл',
    'lot-rate' => 'Введите начальную цену',
    'lot-step' => 'Введите шаг ставки',
    'lot-date' => 'Введите дату завершения торгов',
];

// --- Получение данных из формы ---

//проверка: форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_lot = $_POST;

    //проверка: наименование лота lot_title
    $new_lot['lot-name'] = isset($new_lot['lot-name']) ? trim($new_lot['lot-name']) : '';
    if ($new_lot['lot-name'] === '') {
        $lot_errors['lot-name'] = true;
    }

    //проверка: категория category_id
    $new_lot['category'] = isset($new_lot['category']) ? trim($new_lot['category']) : '';
    if ($new_lot['category'] === '') {
        $lot_errors['category'] = true;
    }

    //проверка: описание лота lot_description
    $new_lot['message'] = isset($new_lot['message']) ? trim($new_lot['message']) : '';
    if ($new_lot['message'] === '') {
        $lot_errors['message'] = true;
    }

    //проверка: изображение lot_image
    if (!isset($_FILES['lot-img']) or $_FILES['lot-img']['tmp_name'] === "") {
        $lot_errors['lot-img'] = true;
    } else {
        $file_type = mime_content_type($_FILES['lot-img']['tmp_name']);

        if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {
            $lot_errors['lot-img'] = true;
            $errors_message['lot-img'] = 'Загрузите изображение в формате .png или .jpeg)';
        }
    }

    //проверка: начальная цена lot_price_start
    $new_lot['lot-rate'] = isset($new_lot['lot-rate']) ? trim($new_lot['lot-rate']) : '';
    if (!is_numeric($new_lot['lot-rate'])) {
        $lot_errors['lot-rate'] = true;
    } else {
        $new_lot['lot-rate'] = (int)$new_lot['lot-rate'];
        if ($new_lot['lot-rate'] <= 0) {
            $lot_errors['lot-rate'] = true;
            $errors_message['lot-rate'] = 'Введите целое положительное число';
        }
    }

    //проверка: шаг ставки lot_step
    $new_lot['lot-step'] = isset($new_lot['lot-step']) ? trim($new_lot['lot-step']) : '';
    if (!is_numeric($new_lot['lot-step'])) {
        $lot_errors['lot-step'] = true;
    } else {
        $new_lot['lot-step'] = (int)$new_lot['lot-step'];
        if ($new_lot['lot-step'] <= 0) {
            $lot_errors['lot-step'] = true;
            $errors_message['lot-step'] = 'Введите целое положительное число';
        }
    }

    //проверка: дата завершения lot_date_end
    $new_lot['lot-date'] = isset($new_lot['lot-date']) ? trim($new_lot['lot-date']) : '';
    if (!is_date_valid($new_lot['lot-date'])) {
        $lot_errors['lot-date'] = true;
    } elseif (new DateTime($new_lot['lot-date']) < new DateTime()) {
        $lot_errors['lot-date'] = true;
        $errors_message['lot-date'] = 'Введите будущую дату';
    }

    if (count($lot_errors)) {
        //проверка формы выявила ошибки, отобразить ошибки на странице
        $content = include_template('./add.php', [
            'categories' => $categories,
            'lot_errors' => $lot_errors,
            'errors_message' => $errors_message,
            'new_lot' => $new_lot,
        ]);
    } else {
        //сохранение нового лота в БД
        $file_tmp = $_FILES['lot-img']['tmp_name'];
        $file_path = 'uploads/';

        if ($file_type === 'image/jpeg') {
            $file_name = uniqid('', true) . '.jpg';
        } else {
            $file_name = uniqid('', true) . '.png';
        }

        //файл изображения перенести в публичную директорию и сохранить ссылку
        move_uploaded_file($file_tmp, $file_path . $file_name);
        $new_lot['lot-img'] = $file_path . $file_name;


        $sql = 'INSERT INTO lots
        (lot_date_create, category_id, lot_title, lot_description, lot_image, lot_price_start, lot_step, lot_date_end, user_id)
        VALUES (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?)';

        $new_lot_id =  db_insert_data($link, $sql, [
            $new_lot['category'],
            $new_lot['lot-name'],
            $new_lot['message'],
            $new_lot['lot-img'],
            $new_lot['lot-rate'],
            $new_lot['lot-step'],
            $new_lot['lot-date'],
            $_SESSION['user'],
        ]);

        //при успешном сохранении формы, переадресация на страницу нового лота
        if ($new_lot_id) {
            header('Location: /lot.php?id=' . $new_lot_id);
            exit;
        }

        $error = 'Ошибка при добавление лота в БД';
        $error_page = include_template('./error.php', ['error' => $error]);
        print($error_page);
        die();
    }
} else {
    $content = include_template('./add.php', [
        'categories' => $categories,
        'lot_errors' => $lot_errors,
        'errors_message' => $errors_message,
    ]);
}

// --- Сборка страницы с лотом ---

$menu_general = include_template('./menu_general.php', [
    'categories' => $categories
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
