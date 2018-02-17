<?php
require_once 'functions.php';
require_once 'data.php';

session_start();

$page_content = renderTemplate('templates/index.php', [
    'lots' => $lots
]);
$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'Главная',
    'categories' => $categories
]);

print($layout_content);
?>
