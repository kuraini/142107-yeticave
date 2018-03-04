<?php
require_once 'vendor/autoload.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'data.php';

session_start();

$visited_lots = [];

if (isset($_COOKIE['history'])) {
    $visited_lots = json_decode($_COOKIE['history']);
}

$lots = [];

$cur_page = intval($_GET['page'] ?? 1);
$page_items = 9;
$items_count = count($visited_lots);
$pages_count = ceil($items_count / $page_items);
$offset = ($cur_page - 1) * $page_items;
$pages = range(1, $pages_count);
$sql = "SELECT
    l.`id`,
    l.`title`,
    l.`start_price`,
    l.`image`,
    UNIX_TIMESTAMP(l.`date_end`) AS date_end,
    c.`name` AS category
FROM `lots` AS l
JOIN `categories` AS c ON l.`category_id` = c.`id`
WHERE l.`id` IN (" . implode(",", $visited_lots) .
") ORDER BY l.`date_start` DESC LIMIT $page_items OFFSET $offset";

$res = mysqli_query($link, $sql);
if ($res) {
    $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($link);
}
if ($error) {
    $page_content = renderTemplate('templates/error.php', [
        'error' => $error
    ]);
} else {
    $page_content = renderTemplate('templates/history.php', [
        'lots' => $lots,
        'visited_lots' => $visited_lots,
        'categories' => $categories,
        'page_name' => '/history.php',
        'pages' => $pages,
        'pages_count' => $pages_count,
        'cur_page' => $cur_page
    ]);
}

$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'История просмотров',
    'categories' => $categories
]);

print($layout_content);
?>
