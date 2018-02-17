<?php
require_once 'functions.php';
require_once 'data.php';

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
    'categories' => $categories,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar
]);

print($layout_content);
?>
