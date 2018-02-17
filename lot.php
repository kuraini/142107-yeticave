<?php
require_once 'functions.php';
require_once 'data.php';

$lot = $lots[$_GET['id']] ?? null;
$visited_lots = [];

if (isset($_COOKIE['history'])) {
    $visited_lots = json_decode($_COOKIE['history']);
}

if (isset($_GET['id'])) {
    $lot_id = $_GET['id'];

    if (!in_array($lot_id, $visited_lots)) {
        array_push($visited_lots, $lot_id);
        setcookie('history', json_encode($visited_lots), strtotime('week'));
    }
}

if (!$lot) {
    http_response_code(404);
}

$page_content = renderTemplate('templates/lot.php', [
    'lot' => $lot,
    'categories' => $categories,
    'visited_lots' => $visited_lots
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
