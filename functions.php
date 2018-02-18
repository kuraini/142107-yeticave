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
        ob_start('ob_gzhandler');
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
    $ts = strtotime($date);
    $time_difference = $ts - time();
    $hours = floor($time_difference / SECONDS_IN_HOUR);
    $minutes = floor($time_difference / SECONDS_IN_MINUTE) % SECONDS_IN_MINUTE;
    $time_left = sprintf('%2d:%02d', $hours, $minutes);

    return $time_left;
}

/**
 * Функция поиска пользователя по email
 *
 * @param string $email email введенный пользователем
 * @param array $users массив с зарегистрированными пользователями
 * @return array|null массив с данными пользователя или null
 */

function searchUserByEmail($email, $users) {
    $result = null;
    foreach ($users as $user) {
        if ($user['email'] == $email) {
            $result = $user;
            break;
        }
    }

    return $result;
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
?>
