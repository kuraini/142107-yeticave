<?php
define('SECONDS_IN_MINUTE', 60);
define('SECONDS_IN_HOUR', 3600);
define('SECONDS_IN_DAY', 86400);

/**
 * Функция форматирования суммы и добавления к ней знака валюты
 *
 * @param integer $price цена за лот, сумма в рублях
 * @param string $currency знак валюты, значение по умолчанию - знак рубля
 * @return string отформатированная сумма вместе со знаком валюты
 */

function formatSum($price, $currency = '&nbsp;&#8381;') {
    $price = ceil($price);

    if ($price > 1000) {
        $price = number_format($price, 0, '', ' ');
    }
    $price .= $currency;

    return $price;
 }

 /**
  * Функция-шаблонизатор
  *
  * @param string $path путь к файлу шаблона
  * @param array $data массив с данными для этого шаблона
  * @return string сгенерированный HTML-код шаблона
  */

function renderTemplate($path, $data) {
    $output = "";
    if (file_exists($path)) {
        ob_start();
        extract($data, EXTR_SKIP);
        require_once($path);
        $output = ob_get_clean();
    }

    return $output;
}

/**
 * Функция склонения слов в зависимости от числа
 *
 * @param integer $number число в зависимости от которого будем склонять слово
 * @param array $forms_of_word массив из форм слова в порядке: для 1, для 2, для 5
 * @param string форма слова
 */

function doDeclination($number, $forms_of_word) {
    $keys = [2, 0, 1, 1, 1, 2];
    $mod = $number % 100;
    $key = ($mod > 7 && $mod < 20) ? 2 : $keys[min($mod % 10, 5)];

    return $forms_of_word[$key];
}

/**
 * Функция форматирования времени ставки
 *
 * @param string $ts метка времени
 * @param string $func ссылка на функцию склонения слов
 * @param string время добавления ставки
 */

function formatTime($ts, $func = 'doDeclination') {
    $time_difference = time() - $ts;
    $forms_for_minutes = ['минута', 'минуты', 'минут'];
    $forms_for_hours = ['час', 'часа', 'часов'];

    if ($time_difference > SECONDS_IN_DAY) {
        $date = date('d.m.y в H:i', $ts);

        return $date;
    } else {
        $hours = floor($time_difference / SECONDS_IN_HOUR);
        $minutes = floor($time_difference / SECONDS_IN_MINUTE);

        if ($time_difference > SECONDS_IN_HOUR) {
            $time = $hours . ' ' . $func($hours, $forms_for_hours);
        } else {
            $time = $minutes . ' ' . $func($minutes, $forms_for_minutes);
        }

        return $time;
    }
}

/**
 * Функция считает сколько времени осталось до конца ставки
 *
 * @param string $date дата когда заканчивается время аукциона на лот
 * @return string оставшееся время в формате "чч:мм";
 */

function countRemainingTime($date) {
    $time_difference = $date - time();
    $hours = floor($time_difference / SECONDS_IN_HOUR);
    $minutes = floor($time_difference / SECONDS_IN_MINUTE) % SECONDS_IN_MINUTE;
    $time_left = sprintf('%2d:%02d', $hours, $minutes);

    if ($time_difference <= 0) {
        return 'Время истекло';
    }

    return $time_left;
}

/**
 * Функция переадресации
 *
 * @param string $path путь по умолчанию на главную страницу
 */

function redirectTo($path = '/') {
    header("Location: $path");
    exit();
}

/**
 * Функция проверки на аутентификацию
 *
 * @return boolean если пользователь аутентифицирован, то возвращает true
 */

function isAuth() {
    if (!empty($_SESSION['user'])) {
        return true;
    }
    return false;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

/**
 * Функция для получения данных из БД
 *
 * @param mysqli $link ресурс соединения
 * @param string $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 * @return array массив с данными
 */

function selectAll($link, $sql, $data = []) {
    $select_data = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $select_data = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $select_data;
}


/**
 * Функция для получения одной записи из БД
 *
 * @param mysqli $link ресурс соединения
 * @param string $sql SQL запрос
 * @return array массив с данными
 */

function selectOne($link, $sql) {
    $select_data = [];
    $res = mysqli_query($link, $sql);
    if ($res) {
        $select_data = mysqli_fetch_assoc($res);
    }

    return $select_data;
}
?>
