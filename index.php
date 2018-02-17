<?php
require_once 'functions.php';
require_once 'data.php';

$page_content = renderTemplate('templates/index.php', [
    'lots' => $lots
]);
$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'Главная',
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);

print($layout_content);
?>
