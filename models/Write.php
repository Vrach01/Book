<?php

/**
 * Class Write - модель для работы с записями пользователей.
 */
class Write
{
    // Кол-во записей на странице
    const SHOW_BY_DEFAULT = 5;

    /**
     * Получает SHOW_BY_DEFAULT штук записей из БД для текущей страницы
     * @param  integer $page Номер отображаемой страницы
     * @return mixed Массив записей
     */
    public static function getUserWritesOnPage($page,$userId)
    {
        $db = Db::getConnection();


        $fromWrite = ($page - 1) * self::SHOW_BY_DEFAULT;
        $writesOnPage = $page * self::SHOW_BY_DEFAULT;

        $sql = "SELECT write.id as id, write.time as 'time', 
        user.login as `name`, write.text as text FROM `write` LEFT JOIN  
        `user` ON write.login_id=user.id WHERE write.login_id = :id ORDER BY `time` DESC LIMIT :fromWrite, :writesOnPage";
        // Получение и возврат результатов. Используется подготовленный запрос.
        $result = $db->prepare($sql);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $result->bindParam(':fromWrite', $fromWrite, PDO::PARAM_INT);
        $result->bindParam(':writesOnPage', $writesOnPage, PDO::PARAM_INT);

        $result->execute();

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

    /**
     * Удаляет запись по её ID.
     * @param integer $writeId ID записи.
     * @return bool Результат выполнения метода
     */
    public static function deleteOneWrite($writeId)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "DELETE FROM `write` WHERE id = :id";

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $writeId, PDO::PARAM_INT);
        return $result->execute();
    }

    /** Получает кол-во всех записей из БД
     * @return mixed
     */
    public static function getUserWritesCount($userId)
    {
        $db = Db::getConnection();

        $sql = "SELECT COUNT(*) AS count FROM `write` WHERE login_id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос.
        $result = $db->prepare($sql);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);

        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        $result = $result->fetch();

        return $result['count'];
    }

    public static function getWriteById($writeId)
    {
        $db = Db::getConnection();

        $sql = "SELECT * FROM `write` WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос.
        $result = $db->prepare($sql);
        $result->bindParam(':id', $writeId, PDO::PARAM_INT);

        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }

    /**
     * Обновляет выбранную запись
     * @param integer $writeId ID записи
     * @param string $text Текст записи
     * @return bool Результат метода
     */
    public static function updateWrite($writeId, $text)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE `write` SET text = :text WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $writeId, PDO::PARAM_INT);
        $result->bindParam(':text', $text, PDO::PARAM_STR);
        return $result->execute();
    }

}