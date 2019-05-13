<?php

declare (strict_types = 1);
require_once './init.php';

// --- Получение данных из БД ---

$categories = get_categories($link);

//массив ошибок при заполнении формы
$registration_errors = [];
$errors_message = [
    'email' => "Введите e-mail",
    'password' => "Введите пароль",
    'name' => "Введите имя",
    'message' => "Напишите как с вами связаться",
];

// --- Получение данных из формы ---

//проверка: форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_user = $_POST;

    //проверка: e-mail
    $new_user['email'] = trim($new_user['email']);
    if ($new_user['email'] === '') {
        $registration_errors['email'] = true;
    } elseif (filter_var($new_user['email'], FILTER_VALIDATE_EMAIL) === false) {
        $registration_errors['email'] = true;
        $errors_message['email'] = 'Введите email в правильном формате: name@domain.com';
    } else {
        $sql = "SELECT id FROM users WHERE user_email = ?";

        $email_in_db = db_fetch_data($link, $sql, [$new_user['email']])[0] ?? null;

        if ($email_in_db) {
            $registration_errors['email'] = true;
            $errors_message['email'] = 'Такой email уже зарегистрирован';
        }
    }

    //проверка: пароль
    $new_user['password'] = trim($new_user['password']);
    if ($new_user['password'] === '') {
        $registration_errors['password'] = true;
    }

    //проверка: имя
    $new_user['name'] = trim($new_user['name']);
    if ($new_user['name'] === '') {
        $registration_errors['name'] = true;
    }

    //проверка: контактные данные
    $new_user['message'] = trim($new_user['message']);
    if ($new_user['message'] === '') {
        $registration_errors['message'] = true;
    }

    if (count($registration_errors)) {
        //проверка формы выявила ошибки, отобразить ошибки на странице
        $content = include_template('./sign-up.php', [
            'registration_errors' => $registration_errors,
            'errors_message' => $errors_message,
            'new_user' => $new_user,
        ]);
    } else {
        //сохранение нового пользователя в БД

        $new_user['password'] = password_hash($new_user['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (user_date_create, user_email, user_name, user_password, user_contacts)
                VALUES (CURRENT_TIMESTAMP, ?, ?, ?, ?)";

        $new_user_id =  db_insert_data($link, $sql, [
            $new_user['email'],
            $new_user['name'],
            $new_user['password'],
            $new_user['message'],
        ]);

        //при успешном сохранении формы, переадресация на страницу входа
        if ($new_user_id) {
            header('Location: /login.php');
            exit();
        }

        $error = "Ошибка при добавление лота в БД";
        $error_page = include_template('./error.php', ['error' => $error]);
        print($error_page);
        die();
    }
} else {
    $content = include_template('./sign-up.php', [
        'registration_errors' => $registration_errors,
        'errors_message' => $errors_message,
    ]);
}

// --- Сборка страницы с лотом ---

$menu_general = include_template('./menu_general.php', [
    'categories' => $categories
]);

$layout = include_template('./layout.php', [
    'title' => "Регистрация",
    'session_user' => $session_user,
    'menu' => $menu_general,
    'content' => $content,
    'categories' => $categories,
]);

print($layout);
