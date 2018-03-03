<?php
require_once 'functions.php';
require_once 'init.php';
require_once 'data.php';

session_start();

$cur_page = intval($_GET['page'] ?? 1);
$page_items = 9;
$sql_count = "SELECT COUNT(*) AS cnt FROM `lots` WHERE `date_end` > NOW()";
$result = mysqli_query($link, $sql_count);
$items_count = mysqli_fetch_assoc($result)['cnt'];
$pages_count = ceil($items_count / $page_items);
$offset = ($cur_page - 1) * $page_items;
$pages = range(1, $pages_count);

$lots = [];

$sql = "SELECT
    l.`id`,
    l.`title`,
    l.`start_price`,
    l.`image`,
    UNIX_TIMESTAMP(l.`date_end`) AS date_end,
    c.`name` AS category
FROM `lots` AS l
JOIN `categories` AS c ON l.`category_id` = c.`id`
WHERE date_end >= NOW()
ORDER BY l.`date_start` DESC LIMIT $page_items OFFSET $offset";

$res = mysqli_query($link, $sql);
if ($res) {
    $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $page_content = renderTemplate('templates/index.php', [
        'lots' => $lots,
        'pages_name' => '/index.php',
        'pages' => $pages,
        'pages_count' => $pages_count,
        'cur_page' => $cur_page
    ]);
} else {
    $error = mysqli_error($link);
    $page_content = renderTemplate('templates/error.php', [
        'error' => $error
    ]);
}

$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'Главная',
    'categories' => $categories
]);

print($layout_content);
?>
