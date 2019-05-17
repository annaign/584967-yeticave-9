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
            WHERE lots.id = ?
            GROUP BY lots.id, category_title, lot_title, lot_description, lot_price_start, lot_date_end, lot_image, lot_step";

    $lot = db_fetch_data($link, $sql, [$id]);
    $lot = $lot[0] ?? NULL;

    if ($lot !== false) {
        if ($lot['id'] === NULL) {
            page_404();
        }
        return $lot;
    }

    $error = mysqli_error($link);
    $error_page = include_template('./error.php', ['error' => $error]);
    print($error_page);
    die();
}

function get_bets_by_user_id(mysqli $link, int $user_id): ?array
{
    $sql = "SELECT lots.id as lot_id, lots.lot_image, lots.lot_title, users.user_contacts, categories.category_title,
            lots.lot_date_end, bets.user_id, lots.winner_id, bets.bet_price, bets.bet_date_create
            FROM bets
            JOIN users ON users.id = bets.user_id
            JOIN lots ON lots.id = bets.lot_id
            JOIN categories ON categories.id = lots.category_id
            WHERE bets.user_id = ?
            ORDER BY bets.bet_date_create DESC";

    $bets = db_fetch_data($link, $sql, [$user_id]);

    if ($bets !== false) {
        return $bets;
    }

    $error = mysqli_error($link);
    $error_page = include_template('./error.php', ['error' => $error]);
    print($error_page);
    die();
}

function get_bets_by_lot_id(mysqli $link, $id)
{

    $sql = "SELECT users.user_name, bets.bet_price, bets.bet_date_create
            FROM bets
            JOIN users ON users.id = bets.user_id
            WHERE bets.lot_id = ?
            ORDER BY bets.bet_date_create DESC";

    $lot_bets = db_fetch_data($link, $sql, [$id]);

    if ($lot_bets !== false) {
        return $lot_bets;
    }

    return [];
}

function interval_before_close(string $time_close, $full_time = false): string
{
    $seconds_in_hour = 60 * 60;
    $seconds_before_close = strtotime($time_close) - time();

    $hours = floor($seconds_before_close / $seconds_in_hour);
    $minutes = floor(($seconds_before_close % $seconds_in_hour) / 60);
    $days = floor($seconds_before_close / ($seconds_in_hour * 24));

    if ($hours < 10) {
        $hours = '0' . $hours;
    }
    if ($minutes < 10) {
        $minutes = '0' . $minutes;
    }

    if ($full_time) {
        return "$days:$hours:$minutes";
    }

    return "$hours:$minutes";
}

function check_time2(string $moment, int $limit_time_in_sec = null): bool
{
    if (!$limit_time_in_sec) {
        return time() - strtotime($moment) > 0;
    }

    $seconds_before_close = strtotime($moment) - time();

    return $seconds_before_close > 0 && $seconds_before_close <= $limit_time_in_sec;
}

function format_bet_date(string $date): string
{
    $bet_date = '';
    $date_rates = strtotime($date);
    $date_now = strtotime('now');
    $date_today = strtotime('today midnight');
    $date_yesterday = strtotime('yesterday midnight');
    $date_diff = $date_now - $date_rates;
    $diff_hour = floor($date_diff / 3600);
    $diff_min = floor($date_diff / 60);
    if ($date_yesterday < $date_rates and $date_today > $date_rates) {
        $bet_date = 'Вчера, ' . date('G:i', $date_rates);
    } elseif ($diff_hour > 0 and $date_rates > $date_today) {
        $bet_date = $diff_hour . ' ' . get_noun_plural_form((int)$diff_hour, 'час', 'часа', 'часов') . ' назад';
    } elseif ($date_now - 3600 < $date_rates) {
        $bet_date = $diff_min . ' ' . get_noun_plural_form((int)$diff_min, 'минута', 'минуты', 'минут') . ' назад';
    } elseif ($date_rates < $date_yesterday) {
        $bet_date = date('d.m.y в G:i', $date_rates);
    }
    return $bet_date;
};


function page_404() {
    header("Location: /404.php");
    exit();
}
