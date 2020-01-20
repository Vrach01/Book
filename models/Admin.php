<?php

class Admin
{

    /**
     * Получает статус бана пользователя
     * @param $userId ID пользователя
     * @return integer Статус бана пользователя
     */
    public static function getBanStatus($userId)
    {
        //Получаем данные пользователя(в том числе и статус бана).
        $userData = User::getUserData($userId);
        return $userData['banned'];
    }

    /**
     * Получает статус пользователя
     * @param $userId ID пользователя
     * @return integer Статус пользователя
     */
    public static function getUserStatus($userId)
    {
        //Получаем статус пользователя.
        $userData = User::getUserData($userId);
        return $userData['status_id'];
    }

    /**
     * Меняет статус бана пользователя (Забанен <=> не забанен)
     * @param $userId ID пользователя
     */
    public static function changeBanStatus($userId)
    {
        $banStatus = Admin::getBanStatus($userId);
        if($banStatus == 1) $revBanStatus = 0;
        else $revBanStatus = 1;

        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE user SET banned = :banStatus WHERE id = :id";

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':banStatus', $revBanStatus, PDO::PARAM_INT);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $result->execute();
    }

    /**
     * Меняет статус пользователя(админ <=> пользователь)
     * @param $userId ID пользователя
     */
    public static function changeUserStatus($userId)
    {
        $userStatus = Admin::getUserStatus($userId);
        if($userStatus == 2) $revUserStatus = 3;
        else $revUserStatus = 2;

        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE user SET status_id = :userStatus WHERE id = :id";

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':userStatus', $revUserStatus, PDO::PARAM_INT);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $result->execute();
    }

    /**
     * Удаляет пользователя по указанному ID.
     * @param $userId ID пользователя
     * @return bool Результат выполнения метода
     */
    public static function deleteUser($userId)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "DELETE FROM user WHERE id = :id";

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Удаляет все записи пользователя с указанным ID
     * @param $userId ID пользователя
     */
    public static function deleteUserWrites($userId)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "DELETE FROM `write` WHERE `login_id` = :id";

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $result->execute();
        return $result->errorInfo();

    }


    /**
     * Возвращает true, если пользователь - админ; false, если нет.
     * @param integer $userId ID пользователя
     * @return bool Результат метода
     */
    public static function isAdmin($userId)
    {
        $userData = User::getUserData($userId);
        if($userData['status_id'] == 1 or $userData['status_id'] == 2) return true;
        return false;
    }

    /**
     * Возвращает true, если пользователь - суперадмин; false, если нет.
     * @param integer $userId ID пользователя
     * @return bool Результат метода
     */
    public static function isSuperAdmin($userId)
    {
        $userData = User::getUserData($userId);
        if($userData['status_id'] == 1) return true;
        return false;
    }

}