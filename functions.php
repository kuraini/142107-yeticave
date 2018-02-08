<?php
/**
 * Функция форматирования суммы и добавления к ней знака рубля
 *
 * @param integer $price цена за лот, сумма в рублях
 * @return string отформатированная сумма вместе со знаком рубля
 */

function formatSum($price) {
    $price = ceil($price);

    if ($price > 1000) {
        $price = number_format($price, 0, '', ' ');
    }
    $price .= "&nbsp;&#8381;";

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
?>