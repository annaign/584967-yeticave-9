<?php

//Получение записей из БД
function db_fetch_data($link, $sql, $data = [])
{
    $result = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    return $result;
}

//Добавление новой записи в БД
function db_insert_data($link, $sql, $data = [])
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        $result = mysqli_insert_id($link);
    }
    return $result;
}

function price_format($number)
{
    return number_format(ceil($number), 0, ',', ' ');
}

function interval_before_midnight()
{
    return date_diff(date_create("now"), date_create("tomorrow midnight"));
}

function check_time($interval, int $sec_time): bool
{
    return ($interval->h * 60 * 60 + $interval->i * 60 + $interval->s <= $sec_time);
}

//Возвращает массив с категориями
function get_categories(mysqli $link): array
{
    $sql = "SELECT id, category_title FROM categories";
    $categories = mysqli_fetch_all(mysqli_query($link, $sql), MYSQLI_ASSOC);
    if ($categories !== false) {
        return $categories;
    }

    $error = mysqli_error($link);
    $error_page = include_template('./error.php', ['error' => $error]);
    print($error_page);
    die();
}

//Возвращает массив с открытыми лотами
function get_lots(mysqli $link): array
{
    $sql = "SELECT lots.id AS id, lot_title, lot_price_start, lot_image, category_title
            FROM lots
            JOIN categories ON lots.category_id = categories.id
            WHERE lots.lot_date_end > NOW()
            GROUP BY lots.id
            ORDER BY lots.lot_date_create DESC";

    $lots = mysqli_fetch_all(mysqli_query($link, $sql), MYSQLI_ASSOC);

    if ($lots !== false) {
        return $lots;
    }

    $error = mysqli_error($link);
    $error_page = include_template('./error.php', ['error' => $error]);
    print($error_page);
    die();
}

//Возвращает лот по его id
function get_lot_by_id(mysqli $link, int $id): ?array
{
    $sql = "SELECT category_title, lot_title, lot_description, lot_price_start, lot_date_end, lot_image, lot_step,
            MAX(bet_price) as bet_price, lots.id
            FROM lots
            JOIN categories ON lots.category_id = categories.id
            LEFT JOIN bets ON lots.id = bets.lot_id
            WHERE lots.id = ?";

    $lot = db_fetch_data($link, $sql, [$id]);
    $lot = $lot[0] ?? NULL;

    if ($lot !== false) {
        if ($lot['id'] === NULL) {
            header("Location: /pages/404.html");
        }
        return $lot;
    }

    $error = mysqli_error($link);
    $error_page = include_template('./error.php', ['error' => $error]);
    print($error_page);
    die();
}

function interval_before_close($time_close)
{
    return date_diff(date_create("now"), date_create($time_close));
}
