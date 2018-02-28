<?php
require_once 'functions.php';
require_once 'init.php';
require_once 'data.php';

//session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $errors = [];
    $required_fields = [
        'email' => 'Введите email',
        'password' => 'Введите пароль'
    ];

    foreach ($required_fields as $field => $message) {
        if (empty($form[$field])) {
            $errors[$field] = $message;
        }
        if ($field == 'email') {
            if (!filter_var($form[$field], FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = 'Email должен быть корректным';
            }
        }
    }

    if (!count($errors)) {
        $sql = "SELECT * FROM `users` WHERE `email` = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$form['email']]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            if (mysqli_num_rows($res)) {
                $user = mysqli_fetch_assoc($res);
                if (password_verify($form['password'], $user['password'])) {
                    $_SESSION = [];
                    session_start();
                    $_SESSION['user'] = $user;
                } else {
                    $errors['password'] = 'Вы ввели неверный пароль';
                }
            } else {
                $errors['email'] = 'Такой пользователь не найден';
            }
        } else {
            $error = mysqli_error($link);
        }
    }

    if (count($errors)) {
        $page_content = renderTemplate('templates/login.php', [
            'categories' => $categories,
            'form' => $form,
            'errors' => $errors
        ]);
    } elseif ($error) {
        $page_content = renderTemplate('templates/error.php', ['error' => $error]);
    } else {
        redirectTo();
    }
} else {
    if (!isAuth()) {
        $page_content = renderTemplate('templates/login.php', [
            'categories' => $categories,
            'form' => $form,
            'errors' => $errors
        ]);
    } else {
        $page_content = "<h2>Вход выполнен</h2>";
    }
}

$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'Вход на сайт',
    'categories' => $categories
]);

print($layout_content);
?>
