<?php
require_once 'functions.php';
require_once 'data.php';

$lot = $lots[$_GET['id']] ?? null;

if (!$lot) {
    http_response_code(404);
}

$page_content = renderTemplate('templates/lot.php', [
    'lot' => $lot,
    'time_left' => $time_left
]);
$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => $lot['name'] ? htmlspecialchars($lot['name']) : 'Лот не найден',
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);

print($layout_content);
?>
