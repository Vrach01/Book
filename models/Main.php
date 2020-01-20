<?php

class Main
{
    // Кол-во записей на странице
    const SHOW_BY_DEFAULT = 5;

    /**
     * Получает SHOW_BY_DEFAULT штук записей из БД для текущей страницы
     * @param  integer $page Номер отображаемой страницы
     * @return mixed Массив записей
     */
    public static function getWritesOnPage($page)
    {
        $db = Db::getConnection();


        $fromWrite = ($page - 1) * self::SHOW_BY_DEFAULT;
        $writesOnPage = $page * self::SHOW_BY_DEFAULT;

        $result = $db->query("SELECT write.id as id, write.time as 'time', 
        user.login as `name`, write.text as text FROM `write` LEFT JOIN  
        `user` ON write.login_id=user.id ORDER BY `time` DESC LIMIT $fromWrite,$writesOnPage");
        $i = 0;
        while($row = $result->fetch()) {
            $writesList[$i]['id'] = $row['id'];
            $writesList[$i]['time'] = $row['time'];
            $writesList[$i]['name'] = $row['name'];
            $writesList[$i]['text'] = $row['text'];
            $i++;
        }
        return $writesList;
    }

    /** Получает кол-во всех записей из БД
     * @return mixed
     */
    public static function getWritesCount()
    {
        $db = Db::getConnection();

        $result = $db->query("SELECT COUNT(*) AS count FROM `write`");

        $result->setFetchMode(PDO::FETCH_ASSOC);

        $result = $result->fetch();

        return $result['count'];
    }

    /**
     * Добавляет запись в гостевую книгу
     * @param integer $userId ID пользователя
     * @param string $text Текст записи
     */
    public static function addWrite($userId, $text)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "INSERT INTO `write` (`login_id`, `text`) VALUES (:id, :text)";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $result->bindParam(':text', $text, PDO::PARAM_STR);
        $result->execute();
    }

}