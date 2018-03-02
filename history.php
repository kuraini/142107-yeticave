<?php
require_once 'functions.php';
require_once 'init.php';
require_once 'data.php';

session_start();

$lots = [];

$sql = "SELECT
    l.`id`,
    l.`title`,
    l.`start_price`,
    l.`image`,
    UNIX_TIMESTAMP(l.`date_end`) AS date_end,
    c.`name` AS category
FROM `lots` AS l
JOIN `categories` AS c ON l.`category_id` = c.`id`";

$res = mysqli_query($link, $sql);
if ($res) {
    $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
} else {
    $error = mysqli_error($link);
}

$visited_lots = [];

if (isset($_COOKIE['history'])) {
    $visited_lots = json_decode($_COOKIE['history']);
}

if ($error) {
    $page_content = renderTemplate('templates/error.php', [
        'error' => $error
    ]);
} else {
    $page_content = renderTemplate('templates/history.php', [
        'lots' => $lots,
        'visited_lots' => $visited_lots,
        'categories' => $categories
    ]);
}

$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'История просмотров',
    'categories' => $categories
]);

print($layout_content);
?>
