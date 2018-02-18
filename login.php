<?php
require_once 'functions.php';
require_once 'data.php';
require_once 'userdata.php';

session_start();

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

    if (!count($errors) && $user = searchUserbyEmail($form['email'], $users)) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = renderTemplate('templates/login.php', [
            'categories' => $categories,
            'form' => $form,
            'errors' => $errors
        ]);
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
