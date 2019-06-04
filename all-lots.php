<?php

declare (strict_types = 1);
require_once './init.php';

// --- Получение данных ---
$categories = get_categories($link);

// --- Получение данных из формы ---

//проверка: форма отправлена
if (isset($_GET['category'])) {
    $category_search = trim($_GET['category']);
    $lots = [];
    $pages = null;
    $current_page = 1;

    if ($category_search !== '') {
        $category_search = mysqli_real_escape_string($link, $category_search);

        $sql = "SELECT COUNT(*) AS items_count, categories.category_title, categories.id
                FROM lots
                LEFT JOIN categories ON categories.id = lots.category_id
                LEFT JOIN bets ON bets.lot_id = lots.id
                WHERE categories.id = ? AND lots.lot_date_end > NOW()
                GROUP BY categories.id";

        $current_page = $_GET['page'] ?? 1;
        $current_page = (int)$current_page;
        if ($current_page  === 0) {
            $current_page = 1;
        }
        $items_on_page = 9;
        $offset = ($current_page - 1) * $items_on_page;
        $category_all = db_fetch_data($link, $sql, [$category_search])[0] ?? null;
        $pages = (int)ceil($category_all['items_count'] / $items_on_page);

        if (!$category_all) {
            $counter = 0;
            while ($counter < count($categories)) {
                if ($categories[$counter]['id'] === $category_search) {
                    $category_all['category_title'] = $categories[$counter]['category_title'];
                    break;
                }
                $counter += 1;
            }
        }

        $sql = "SELECT lots.id,lots.lot_image, categories.category_title, lots.lot_title, lots.lot_price_start,
                MAX(bets.bet_price) AS bet_price, lots.lot_date_end, lots.lot_date_create,  COUNT(DISTINCT bets.id) AS bets_sum
                FROM lots
                LEFT JOIN categories ON categories.id = lots.category_id
                LEFT JOIN bets ON bets.lot_id = lots.id
                WHERE categories.id = ? AND lots.lot_date_end > NOW()
                GROUP BY lots.id
                ORDER BY lots.lot_date_create DESC
                LIMIT $items_on_page
                OFFSET $offset";

        $lots = db_fetch_data($link, $sql, [$category_search,]);
    }

    $content = include_template('./all-lots.php', [
        'categories' => $categories,
        'session_user' => $session_user,
        'category_search' => $category_all,
        'lots' => $lots,
        'pages' => $pages,
        'current_page' => $current_page,
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
