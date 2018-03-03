<?php
require_once 'functions.php';
require_once 'init.php';
require_once 'data.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search = trim($_GET['search'] ?? '');

    if ($search) {
        $cur_page = intval($_GET['page'] ?? 1);
        $page_items = 9;

        $sql = "SELECT COUNT(*) AS cnt
                FROM `lots` AS l
                WHERE MATCH(l.`title`, l.`description`) AGAINST(?)
                AND `date_end` > NOW()";

        $stmt = db_get_prepare_stmt($link, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $items_count = mysqli_fetch_assoc($res)['cnt'];
        $pages_count = ceil($items_count / $page_items);
        $offset = ($cur_page - 1) * $page_items;
        $pages = range(1, $pages_count);

        $sql = "SELECT
                l.`id`,
                l.`title`,
                l.`image`,
                l.`start_price`,
                UNIX_TIMESTAMP(l.`date_end`) AS date_end,
                c.`name` AS category
                FROM `lots` AS l
                JOIN `categories` AS c ON l.`category_id` = c.`id`
                WHERE MATCH(l.`title`, l.`description`) AGAINST(?)
                AND `date_end` > NOW()
                ORDER BY `date_start` DESC LIMIT $page_items OFFSET $offset";

        $stmt = db_get_prepare_stmt($link, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res) {
            $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
            $page_content = renderTemplate('templates/search.php', [
                'search' => $search,
                'categories' => $categories,
                'lots' => $lots,
                'page_name' => '/search.php',
                'pages' => $pages,
                'pages_count' => $pages_count,
                'cur_page' => $cur_page
            ]);
        } else {
            $error = mysqli_error($link);
            $page_content = renderTemplate('templates/error.php', ['error' => $error]);
        }
    } else {
        $page_content = renderTemplate('templates/search.php', ['categories' => $categories]);
    }
} else {
    $page_content = renderTemplate('templates/search.php', ['categories' => $categories]);
}

$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'Поиск',
    'categories' => $categories
]);

print($layout_content);
?>
