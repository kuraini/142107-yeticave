<?php
require_once 'vendor/autoload.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'data.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $errors = [];
    $required_fields = [
        'email' => 'Введите e-mail',
        'password' => 'Введите пароль',
        'name' => 'Введите имя',
        'contacts' => 'Напишите как с вами связаться'
    ];

    foreach ($required_fields as $field => $error_message) {
        if (empty($form[$field])) {
            $errors = $error_message;
        }
        if ($field == 'email') {
            if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = 'Email должен быть корректным';
            }
        }
    }

    if (!isset($errors['email'])) {
        $sql = "SELECT `email` FROM `users` WHERE `email` = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$form['email']]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            if (mysqli_num_rows($res)) {
                $errors['email'] = 'Пользователь с таким email уже существует';
            }
        } else {
            $error = mysqli_error($link);
        }
    }

    if (!empty($_FILES['avatar']['name'])) {
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $folder = 'img/avatars/';
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        $path = $folder . $_FILES['avatar']['name'];
        $file_type = mime_content_type($tmp_name);

        if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {
            $errors['avatar'] = 'Изображение должно быть в формате jpg или png';
        } else {
            move_uploaded_file($tmp_name, $path);
            $form['avatar'] = $path;
        }
    }

    if (empty($_FILES['avatar']['name']) && empty($_FILES['avatar']['tmp_name'])) {
        $form['avatar'] = 'img/no-avatar.png';
    }

    if (!count($errors)) {
        $name = $form['name'];
        $email = $form['email'];
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
        $avatar = $form['avatar'] ?? '';
        $contacts = $form['contacts'];
        $sql = "INSERT INTO `users` (
            `name`,
            `email`,
            `password`,
            `avatar`,
            `contacts`,
            `date_reg`
        )
        VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = db_get_prepare_stmt($link, $sql, [$name, $email, $password, $avatar, $contacts]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            redirectTo('/login.php');
        } else {
            $error = mysqli_error($link);
        }
    } elseif ($error) {
        $page_content = renderTemplate('templates/error.php', ['error' => $error]);
    } else {
        $page_content = renderTemplate('templates/sign-up.php', [
            'form' => $form,
            'errors' => $errors,
            'categories' => $categories
        ]);
    }
} else {
    if ($error) {
        $page_content = renderTemplate('templates/error.php', ['error' => $error]);
    } else {
        $page_content = renderTemplate('templates/sign-up.php', ['categories' => $categories]);
    }
}

$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'Регистрация',
    'categories' => $categories
]);

print($layout_content);
?>
