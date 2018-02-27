<?php
require_once 'init.php';

date_default_timezone_set('Europe/Moscow');

$sql_categories = "SELECT `name` FROM `categories` ORDER BY `id`";
$sql_lots = "SELECT
    l.`id`,
    l.`title`,
    l.`category_id`,
    l.`image`,
    l.`description`,
    l.`start_price`,
    l.`step`,
    l.`author_id`,
    UNIX_TIMESTAMP(l.`date_end`) AS date_end,
    c.`name` AS category
FROM `lots` AS l
JOIN `categories` AS c ON l.`category_id` = c.`id`
ORDER BY l.`id`";

$categories = selectData($link, $sql_categories);
$lots = selectData($link, $sql_lots);

$bets = [
    ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) .' minute')],
    ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) .' hour')],
    ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime('-' . rand(25, 50) .' hour')],
    ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')]
];
