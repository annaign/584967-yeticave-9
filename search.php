<?php

declare (strict_types = 1);
require_once './init.php';

// --- Получение данных ---

$categories = get_categories($link);

// --- Получение данных из формы ---

//проверка: форма отправлена
if (isset($_GET['search'])) {
    $new_search = trim($_GET['search']);
    $lots = [];
    $pages = null;
    $current_page = 1;

    if ($new_search !== '') {
        $new_search = mysqli_real_escape_string($link, $new_search);

        $sql = "SELECT COUNT(*) AS items_count
                FROM lots
                LEFT JOIN categories ON categories.id = lots.category_id
                LEFT JOIN bets ON bets.lot_id = lots.id
                WHERE MATCH(lots.lot_title, lots.lot_description) AGAINST(?) AND
                lots.lot_date_end > NOW()
                GROUP BY lots.id";

        $current_page = (int)$_GET['page'] ?? 1;
        $items_on_page = (int)$_GET['limit'] ?? 9;
        $offset = ($current_page - 1) * $items_on_page;
        $items_all = count(db_fetch_data($link, $sql, [$new_search]));
        $pages = (int)ceil($items_all / $items_on_page);

        $sql = "SELECT lots.id,lots.lot_image, categories.category_title, lots.lot_title, lots.lot_price_start,
                MAX(bets.bet_price) AS bet_price, lots.lot_date_end, lots.lot_date_create,  COUNT(DISTINCT bets.id) AS bets_sum
                FROM lots
                LEFT JOIN categories ON categories.id = lots.category_id
                LEFT JOIN bets ON bets.lot_id = lots.id
                WHERE MATCH(lots.lot_title, lots.lot_description) AGAINST(?) AND
                lots.lot_date_end > NOW()
                GROUP BY lots.id
                ORDER BY lots.lot_date_create DESC
                LIMIT $items_on_page
                OFFSET $offset";

        $lots = db_fetch_data($link, $sql, [$new_search]);
    }

    $content = include_template('./search.php', [
        'categories' => $categories,
        'session_user' => $session_user,
        'new_search' => $new_search,
        'lots' => $lots,
        'pages' => $pages,
        'current_page' => $current_page,
        'items_on_page' => $items_on_page,
    ]);
} else {
    page_404();
}

// --- Сборка страницы с результатами поиска ---

$menu_general = include_template('./menu_general.php', [
    'categories' => $categories
]);

$layout = include_template('./layout.php', [
    'title' => 'Результаты поиска ',
    'session_user' => $session_user,
    'menu' => $menu_general,
    'content' => $content,
    'categories' => $categories,
]);

print($layout);
