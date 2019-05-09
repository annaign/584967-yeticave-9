<?php

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
    $sql = "SELECT lot_title, lot_price_start, lot_image, category_title
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
