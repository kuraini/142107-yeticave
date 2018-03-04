<?php
require_once 'vendor/autoload.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'data.php';

session_start();

$user = $_SESSION['user'] ?? '';
$user_id = $_SESSION['user']['id'] ?? '';
$bet = null;
$bet_id = null;
$lot_id = intval($_GET['id'] ?? null);

$sql_for_lot = "SELECT
                    l.`title`,
                    l.`image`,
                    l.`description`,
                    l.`start_price`,
                    l.`step`,
                    UNIX_TIMESTAMP(l.`date_end`) AS date_end,
                    l.`author_id`,
                    c.`name` AS category
                FROM `lots` AS l
                JOIN `categories` AS c ON l.`category_id` = c.`id`
                WHERE l.`id` = $lot_id";

$sql_for_bets = "SELECT
                    u.`name`,
                    UNIX_TIMESTAMP(b.`date`) AS date_add,
                    b.`price`
                FROM `bets` AS b
                JOIN `users` AS u ON b.`user_id` = u.`id`
                WHERE `lot_id` = $lot_id
                ORDER BY b.`date` DESC";

$sql_for_max_bet = "SELECT MAX(`price`) as max_price
                    FROM `bets`
                    WHERE `lot_id` = $lot_id";

$sql_for_user_bet = "SELECT `user_id`
                    FROM `bets`
                    WHERE `lot_id` = $lot_id AND `user_id` = $user_id";

$bets = selectAll($link, $sql_for_bets);
$lot = selectOne($link, $sql_for_lot);
$get_max_bet = selectOne($link, $sql_for_max_bet);
$max_bet = $get_max_bet ? $get_max_bet['max_price'] : $lot['step'];

$canAddBet = true;

if (!$user || $user_id == $lot['author_id'] ||
    time() <= strtotime($lot['date_end']) ||
    mysqli_num_rows(mysqli_query($link, $sql_for_user_bet))) {
    $canAddBet = false;
}

if ($max_bet > $lot['start_price']) {
    $lot['start_price'] = $max_bet;
}

$min_bet = $lot['start_price'] + $lot['step'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bet = intval($_POST['price']);

    if (empty($bet)) {
        header("Location: /lot.php?id=$lot_id");
        exit();
    } else {
        if ($bet >= ($lot['start_price'] + $lot['step'])) {
            mysqli_query($link, "START TRANSACTION");

            $sql1 = "INSERT INTO `bets`
                (
                    `date`,
                    `price`,
                    `user_id`,
                    `lot_id`
                )
                VALUES (NOW(), $bet, $user_id, $lot_id)";

            $sql2 = "UPDATE `lots` SET `start_price` = $bet WHERE `id` = $lot_id";

            $r1 = mysqli_query($link, $sql1);
            if ($r1) {
                $bet_id = mysqli_insert_id($link);
            }
            $r2 = mysqli_query($link, $sql2);

            $sql3 = "INSERT INTO `users_bets` (`user_id`, `bet_id`) VALUES ($user_id, $bet_id)";
            $r3 = mysqli_query($link, $sql3);

            if ($r1 && $r2) {
                if ($r3) {
                    mysqli_query($link, "COMMIT");
                } else {
                    mysqli_query($link, "ROLLBACK");
                }
            } else {
                mysqli_query($link, "ROLLBACK");
            }

            header("Location: /lot.php?id=$lot_id");
            exit();
        }
    }
}

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
    exit();
}

if ($error) {
    $page_content = renderTemplate('templates/error.php', ['error' => $error]);
} else {
    $page_content = renderTemplate('templates/lot.php', [
        'lot' => $lot,
        'lot_id' => $lot_id,
        'bets' => $bets,
        'min_bet' => $min_bet,
        'categories' => $categories,
        'visited_lots' => $visited_lots,
        'canAddBet' => $canAddBet
    ]);
}

$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => $lot['title'] ? htmlspecialchars($lot['title']) : 'Лот не найден',
    'categories' => $categories
]);

print($layout_content);
?>
