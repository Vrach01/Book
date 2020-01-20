<?php

/**
 * Класс User - модель для работы с пользователями
 */
class User
{

    /**
     * Проверяет, есть ли пользователь с указанным ID
     * @param integer $userId Введённое ID
     * @return bool Результат метода
     */
    public static function isUserExists($userId)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "SELECT COUNT(*) as count FROM user WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $isExists = $result->fetch();
        //Если такой пользователь есть, возвращаем true.
        if ($isExists['count']) return true;
        //Иначе возвращаем false.
        return false;
    }

    /**
     * Получает идентификаторы всех пользователей
     * @return array Массив с ID всех пользователей
     */
    public static function getEveryID()
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "SELECT id FROM user";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->execute();

        // Массив ID всех пользователей.
        $idArray = array();

        while ($userData = $result->fetch()) {
            $idArray[] = $userData['id'];
        }

        return $idArray;
    }

    /**
     * Получает данные пользователя по ID
     * @param integer $userId ID пользователя
     * @return mixed Данные пользователя
     */
    public static function getUserData($userId)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "SELECT * FROM user WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $result->execute();

        $userData = $result->fetch();
        return $userData;
    }

    /**
     * Регистрация пользователя
     * @param string $login <p>Логин</p>
     * @param string $email <p>E-mail</p>
     * @param string $password <p>Пароль</p>
     * @param string $birthdate <p>Дата рождения</p>
     * @param string $country <p>Страна пользователя</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function register($login, $email, $hashPassword, $birthdate, $country, $reg_date)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO user (login, `e-mail`, password, birthdate, reg_date, country) '
            . 'VALUES (:login, :email, :password, :birthdate, :reg_date, :country)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $hashPassword, PDO::PARAM_STR);
        $result->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
        $result->bindParam(':reg_date', $reg_date, PDO::PARAM_STR);
        $result->bindParam(':country', $country, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Обновляет данные на основе изменённых полей.
     * @param integer $userId ID пользователя
     * @param string $login Логин
     * @param string $email Почта
     * @param string $birthdate Дата рождения
     * @param string $country Страна проживания
     * @return bool Результат работы метода
     */
    public static function update($userId, $login, $email, $birthdate, $country)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE user SET login = :login, `e-mail` = :email, 
                birthdate = :birthdate, country = :country WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $userId, PDO::PARAM_INT);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
        $result->bindParam(':country', $country, PDO::PARAM_STR);

        return $result->execute();
    }

    /**
     * Проверяем существует ли пользователь с заданными $login и $password
     * @param string $login <p>Логин</p>
     * @param string $password <p>Пароль</p>
     * @return mixed <p>User id or false</p>
     */
    public static function checkUserData($login, $password)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM user WHERE login = :login';
        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->execute();

        // Обращаемся к записи
        $user = $result->fetch();

        if (password_verify($password, $user['password'])) {
            // Если запись существует, возвращаем id пользователя
            return $user['id'];
        }
        return false;
    }

    /**
     * Проверяет логин: от 4 до 10 символов, латинские буквы и цифры
     * @param string $login <p>Логин</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkLogin($login)
    {
        $isValid = preg_match('#^[a-zA-Z0-9]{4,10}$#', $login);
        if ($isValid) {
            return true;
        }
        return false;
    }

    /** Хэширование пароля методикой MD5
     * @param string $password <p>Нехэшированный пароль</p>
     * @return bool|string <p>Хэшированный пароль</p>
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Проверяет пароль: не меньше, чем 6 символов
     * @param string $password <p>Пароль</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkPassword($password)
    {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет повторно введённый пароль: должен совпадать с исходным паролем
     * @param string $password <p>Исходный пароль</p>
     * @param string $repeatPassword <p>Повторный пароль</p>
     * @return bool <p>Результат выполнения метода</p>
     */
    public static function checkRepeatPassword($password, $repeatPassword)
    {
        if ($password == $repeatPassword) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет email
     * @param string $email <p>E-mail</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет дату рождения: она не должна быть пустой
     * @param string $birthdate <p>Дата рождения</p>
     * @return bool <p>Результат выполнения метода</p>
     */
    public static function checkBirthdate($birthdate)
    {
        if (!empty($birthdate)) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет не занят ли email другим пользователем
     * @param type $email <p>E-mail</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkLoginExists($login)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT COUNT(*) FROM user WHERE login = :login';

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }

    public static function auth($userId, $userLogin, $userStatus, $banStatus)
    {
        // Записываем идентификатор пользователя в сессию
        $_SESSION['user'] = $userId;
        $_SESSION['login'] = $userLogin;
        $_SESSION['status'] = $userStatus;
        $_SESSION['ban'] = $banStatus;
    }

    public static function isBanned($userId)
    {
        //Получаем данные пользователя(в том числе и статус бана).
        $userData = User::getUserData($userId);

        if ($userData['banned'] == 1) {
            $_SESSION['message'] =
                [
                    'text' => "Извините, но в текущий момент вы забанены на сайте!",
                    'status' => 'error'
                ];
            header("Location: /");
            die();
        }
        else {
            return $userData['banned'];
        }
    }

    /**
     * Возвращает ID пользователя, если он авторизирован. Иначе отправляет null.
     * @return mixed|null Идентификатор пользователя(если авторизирован) или null
     */
    public static function getUserId()
    {
        // Если сессия есть, вернем идентификатор пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return null;
    }

    /**
     * Возвращает идентификатор пользователя, если он авторизирован.<br/>
     * Иначе перенаправляет на страницу входа и сообщает о статусе пользователя
     * @return string <p>Идентификатор пользователя</p>
     */
    public static function checkLogged()
    {
        // Если сессия есть, вернем идентификатор пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        //
        $_SESSION['message'] =
            [
                'text' => "Пожалуйста, авторизируйтесь!",
                'status' => 'warning'
            ];
        header("Location: /user/login");
        die();
    }

    public static function getUserStatusText($userStatus)
    {
        switch ($userStatus) {
            case '1':
                return 'СуперАдмин';
                break;
            case '2':
                return 'Админ';
                break;
            case '3':
                return 'Пользователь';
                break;
        }
    }

    public static function getBanStatusText($banStatus)
    {
        switch ($banStatus) {
            case '0':
                return 'Нет';
                break;
            case '1':
                return 'Забанен';
                break;
        }
    }
}