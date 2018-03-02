<?php
require_once 'init.php';

date_default_timezone_set('Europe/Moscow');

$sql_categories = "SELECT * FROM `categories` ORDER BY `id`";
$categories = selectAll($link, $sql_categories);

