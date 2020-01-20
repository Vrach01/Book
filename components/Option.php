<?php

abstract class Option
{
    /**Строит поле option с учётом выбранного поля
     * @param string $name название поля
     * @param string $field название, взятое из поля $_POST['country'][0]
     * @return string HTML-код элемента option
     */
    public static function getOption($name, $field = '')
    {
        if ($field == $name) $selected = 'selected';
        else $selected = '';
        return "<option $selected>$name</option>";
    }
}