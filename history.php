<?php
require_once 'functions.php';
require_once 'init.php';
require_once 'data.php';

session_start();

$visited_lots = [];

if (isset($_COOKIE['history'])) {
    $visited_lots = json_decode($_COOKIE['history']);
}

$page_content = renderTemplate('templates/history.php', [
    'lots' => $lots,
    'visited_lots' => $visited_lots,
    'categories' => $categories
]);
$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'История просмотров',
    'categories' => $categories
]);

print($layout_content);
?>
