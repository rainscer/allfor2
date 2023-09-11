<?php namespace App\Models;
/**
 *
 * Класс для локализованного форматирования даты
 */

class Date {

    protected static $time;

    public function __construct($time = false) {
        self::setTime($time);
    }

    /**
     * работаем с текущей датой
     * @return Date
     */
    public static function now() {
        return new self();
    }
    /**
     * парсим время
     * @param bool $time
     * @return Date
     */
    public static function parse($time = false) {
        return new self($time);
    }
    /**
     * Устанавливаем время в формате timestamp
     * @param bool $time
     */
    private static function setTime($time = false)
    {
        if ($time === false) {
            $time = time();
        }
        if (!is_numeric($time) && is_string($time)) {
            $time = strtotime($time);
        }
        if ($time instanceof \Carbon\Carbon) {
            $time = $time->timestamp;
        }
        self::$time = $time;
    }
    /**
     * Заменяет аббревиатуры на нужные значения
     *
     * @param $format - формат даты "j mmm Y H:i"
     * @param $num - порядковый номер в массиве
     * @param $arr - массив соответсвия, какой элемент массива соответсвует той или иной аббревиатуре
     */
    private static function replace(&$format, $num, $arr) {

        foreach ($arr as $key => $val) {
            $word = trans('date.'.$val)[$num];
            $format = str_replace($key, $word, $format);
            $format = str_replace(mb_strtoupper($key, 'UTF-8'), mb_ucfirst($word, 'UTF-8'), $format);
        }

    }

    /**
     * Форматирует дату в заданном формате
     * поддерживаемые аббревиатуры:
     * dd, ddd - день недели
     * mm, mmm, mmmm - месяц
     * для получения первой буквы заглавной, используем DD, MMM
     *
     * @param string $format
     * @return bool|string
     */
    public function format($format = 'Y-m-d H:i:s') {
        $num_day = date('N', self::$time);
        self::replace($format, $num_day, array(
            'ddd' => 'fweekday',
            'dd' => 'sweekday',
        ));

        $num_month = date('n', self::$time);
        self::replace($format, $num_month, array(
            'mmmm' => 'fmonth',
            'mmm' => 'month',
            'mm' => 'smonth',
        ));

        return date($format, self::$time);
    }

}