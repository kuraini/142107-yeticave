<?php
require_once 'vendor/autoload.php';
require_once 'functions.php';
require_once 'init.php';

$winners = [];

$sql = "SELECT
		b.`lot_id`,
		b.`user_id`,
		l.`title`,
		u.`name`,
		u.`email`,
		MAX(b.`price`) AS price
	FROM `bets` AS b
	JOIN `lots` AS l ON b.`lot_id` = l.`id`
	JOIN `users` AS u ON b.`user_id` = u.`id`
    WHERE l.`date_end` <= NOW() AND l.`winner_id` IS NULL
    GROUP BY b.`lot_id`, b.`user_id`";

$stmt = db_get_prepare_stmt($link, $sql);

if ($stmt) {
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $winners = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
}

if ($winners) {
	foreach ($winners as $winner) {
		$lot_id = $winner['lot_id'] ?? '';
		$lot_title = $winner['title'] ?? '';
		$user_id = $winner['user_id'] ?? '';
		$user_name = $winner['name'] ?? '';
		$user_email = $winner['email'] ?? '';

		$sql = "UPDATE `lots` SET `winner_id` = ? WHERE `id` = ?";

		$stmt = db_get_prepare_stmt($link, $sql, [$user_id, $lot_id]);
		mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

		if ($res) {
			$content = renderTemplate('templates/email.php', [
				'lot_id' => $lot_id,
				'lot_title' => $lot_title,
				'user_name' => $user_name
            ]);

			$transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl'))
				->setUsername('doingsdone@mail.ru')
                ->setPassword('rds7BgcL');

            $mailer = new Swift_Mailer($transport);

			$message = (new Swift_Message('Ваша ставка победила'))
			    ->setTo($user_email)
			    ->setBody($content)
			    ->setFrom('doingsdone@mail.ru');

            $mailer->send($message);
		}
	}
}
?>
