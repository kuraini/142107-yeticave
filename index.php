<?php
require_once 'functions.php';
require_once 'mysql_helper.php';
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
JOIN `categories` AS c ON l.`category_id` = c.`id`
WHERE date_end >= NOW()
ORDER BY l.`date_start` DESC LIMIT 9";

$res = mysqli_query($link, $sql);
if ($res) {
    $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $page_content = renderTemplate('templates/index.php', [
        'lots' => $lots
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
