<?php
require_once 'functions.php';
require_once 'data.php';

session_start();

if (!isAuth()) {
    http_response_code(403);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST;
    $errors = [];

    $required_fields = [
        'lot-name' => 'Введите наименование лота',
        'category' => 'Выберите категорию',
        'message' => 'Напишите описание лота',
        'lot-rate' => 'Введите начальную цену',
        'lot-step' => 'Введите шаг ставки',
        'lot-date' => 'Введите дату завершения торгов'
    ];

    foreach ($required_fields as $field => $error_message) {
        if (empty($_POST[$field])) {
            $errors[$field] = $error_message;
        }
        if ($field == 'lot-rate') {
            if (!filter_var($_POST[$field], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $errors[$field] = 'Начальная цена должна быть числом больше нуля';
            }
        }
        if ($field == 'lot-step') {
            if (!filter_var($_POST[$field], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $errors[$field] = 'Шаг ставки должен быть числом больше нуля';
            }
        }
        if ($field == 'lot-date') {
            if (!strtotime($_POST['lot-date'])) {
                $errors[$field] = 'Введите дату в формате дд.мм.гггг';
            }
            if (strtotime($_POST['lot-date']) < strtotime('tomorrow')) {
                $errors[$field] = 'Дата должна быть больше текущей хотя бы на 1 день';
            }
        }
    }

    if (!empty($_FILES['image']['name'])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tmp_name = $_FILES['image']['tmp_name'];
        $path = 'img/' . $_FILES['image']['name'];
        $file_type = finfo_file($finfo, $tmp_name);

        if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {
            $errors['image'] = 'Изображение должно быть в формате jpg или png';
        } else {
            move_uploaded_file($tmp_name, $path);
            $lot['image'] = $path;
        }
    } else {
        $errors['image'] = 'Загрузите файл в формате jpg или png';
    }

    if (count($errors)) {
        $page_content = renderTemplate('templates/add-lot.php', [
            'categories' => $categories,
            'lot' => $lot,
            'errors' => $errors
        ]);
    } else {
        $page_content = renderTemplate('templates/lot.php', [
            'categories' => $categories,
            'lot' => $lot,
            'bets' => $bets
        ]);
    }
} else {
    $page_content = renderTemplate('templates/add-lot.php', [
        'categories' => $categories
    ]);
}

$layout_content = renderTemplate('templates/layout.php', [
    'content' => $page_content,
    'title' => 'Добавление лота',
    'categories' => $categories
]);

print($layout_content);
?>
