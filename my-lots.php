<?php
require_once 'functions.php';
require_once 'init.php';
require_once 'data.php';

session_start();

if (!isAuth()) {
    http_response_code(403);
    exit();
}

$user_id = $_SESSION['user']['id'] ?? '';

$sql = "SELECT
    b.`lot_id` AS lot_id,
    l.`title` AS lot_title,
    l.`image` AS lot_image,
    l.`start_price` AS price,
    l.`winner_id`,
    UNIX_TIMESTAMP(l.`date_end`) AS date_end,
    u.`contacts`,
    UNIX_TIMESTAMP(b.`date`) AS date_add,
    c.`name` AS category
FROM `lots` AS l
JOIN `categories` AS c ON l.`category_id` = c.`id`
JOIN `users` AS u ON l.`author_id` = u.`id`
JOIN `bets` AS b ON lot_id = l.`id`
WHERE b.`user_id` = $user_id";

$bets = selectAll($link, $sql);

if (!empty($bets)) {
    foreach ($bets as $bet) {
        if ($bet['winner_id'] == $user_id) {
            $bet['is_winner'] = 'rates__item--win';
            $bet['date_end'] = 'Ставка выиграла';
            $bet['timer'] = 'timer--win';
        }
        if ((integer)$bet['date_end'] < time() && $bet['winner_id'] != $user_id) {
            $bet['rates_end'] = 'rates__item--end';
            $bet['date_end'] = 'Торги окончены';
            $bet['timer'] = 'timer--end';
        }
        if ((time() - (integer)$bet['date_end']) <= 86400) {
            $bet['timer'] = 'timer--finishing';
        }
    }
    unset($bet);
}

var_dump($bets);
$page_content = renderTemplate('templates/my-lots.php', [
    'categories' => $categories,
    'bets' => $bets
]);

$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'Мои ставки',
    'categories' => $categories
]);

print($layout_content);
?>
