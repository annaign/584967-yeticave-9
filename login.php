<?php

declare (strict_types = 1);
require_once './init.php';

if (isset($_SESSION['user'])) {
    header('location: /');
    exit();
}

// --- Получение данных из БД ---

$categories = get_categories($link);

//массив ошибок при заполнении формы
$access_errors = [];
$errors_message = [
    'email' => 'Введите e-mail',
    'password' => 'Введите пароль',
];

// --- Получение данных из формы ---

//проверка: форма отправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST;

    //проверка: e-mail
    $user['email'] = trim($user['email']);
    if ($user['email'] === '') {
        $access_errors['email'] = true;
    } elseif (filter_var($user['email'], FILTER_VALIDATE_EMAIL) === false) {
        $access_errors['email'] = true;
        $errors_message['email'] = 'Введите email в правильном формате: name@domain.com';
    }

    //проверка: пароль
    $user['password'] = trim($user['password']);
    if ($user['password'] === '') {
        $access_errors['password'] = true;
    }

    if (count($access_errors)) {
        //проверка формы выявила ошибки, отобразить ошибки на странице
        $content = include_template('./login.php', [
            'access_errors' => $access_errors,
            'errors_message' => $errors_message,
            'user' => $user,
        ]);
    } else {
        //поиск пользователя в БД

        $sql = "SELECT id, user_email, user_password FROM users WHERE user_email = ?";
        $user_db = db_fetch_data($link, $sql, [$user['email']])[0] ?? null;

        if ($user_db === null) {
            $access_errors['email'] = true;
            $errors_message['email'] = 'Этот email не зарегистрирован';
        } else {
            if (!password_verify($user['password'], $user_db['user_password'])) {
                $access_errors['password'] = true;
                $errors_message['password'] = 'Неправильный пароль';
            } else {
                //Данные пользователя подтверждены
                $_SESSION['user'] = $user_db['id'];

                header('Location: /index.php');
                exit();
            }
        }

        if (count($access_errors)) {
            //проверка пользователя выявила ошибки, отобразить ошибки на странице
            $content = include_template('./login.php', [
                'access_errors' => $access_errors,
                'errors_message' => $errors_message,
                'user' => $user,
            ]);
        }
    }
} else {
    $content = include_template('./login.php', [
        'access_errors' => $access_errors,
        'errors_message' => $errors_message,
    ]);
}

// --- Сборка страницы с лотом ---

$menu_general = include_template('./menu_general.php', [
    'categories' => $categories
]);

$layout = include_template('./layout.php', [
    'title' => 'Вход',
    'session_user' => $session_user,
    'menu' => $menu_general,
    'content' => $content,
    'categories' => $categories,
]);

print($layout);
