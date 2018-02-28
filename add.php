<?php
require_once 'functions.php';
require_once 'init.php';
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
        'title' => 'Введите наименование лота',
        'category_id' => 'Выберите категорию',
        'description' => 'Напишите описание лота',
        'start_price' => 'Введите начальную цену',
        'step' => 'Введите шаг ставки',
        'date_end' => 'Введите дату завершения торгов'
    ];

    foreach ($required_fields as $field => $error_message) {
        if (empty($_POST[$field])) {
            $errors[$field] = $error_message;
        }
        if ($field == 'start_price') {
            if (!filter_var($_POST[$field], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $errors[$field] = 'Начальная цена должна быть числом больше нуля';
            }
        }
        if ($field == 'step') {
            if (!filter_var($_POST[$field], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
                $errors[$field] = 'Шаг ставки должен быть числом больше нуля';
            }
        }
        if ($field == 'date_end') {
            if (!strtotime($_POST['date_end'])) {
                $errors[$field] = 'Введите дату в формате дд.мм.гггг';
            }
            if (strtotime($_POST['date_end']) < strtotime('tomorrow')) {
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
        $title = $lot['title'];
        $cat = $lot['category_id'];
        $img = $lot['image'];
        $descr = $lot['description'];
        $price = $lot['start_price'];
        $step = $lot['step'];
        $end = $lot['date_end'];
        $author = $_SESSION['user']['id'];

        $sql = "INSERT INTO `lots` (
            `title`,
            `category_id`,
            `image`,
            `description`,
            `start_price`,
            `step`,
            `date_start`,
            `date_end`,
            `author_id`
        )
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)";

        $stmt = db_get_prepare_stmt($link, $sql, [$title, $cat, $img, $descr, $price, $step, $end, $author]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            $lot_id = mysqli_insert_id($link);
            header("Location: lot.php?id=" . $lot_id);
        } else {
            $error = mysqli_error($link);
            $page_content = renderTemplate('templates/error.php', ['error' => $error]);
        }

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
