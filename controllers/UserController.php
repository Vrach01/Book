<?php

class UserController
{
    /** Профиль пользователя
     * @param mixed $userId Идентификатор пользователя рассматриваемого профиля
     */
    public function actionIndex($userId = null)
    {
        // Получаем ID самого пользователя
        $accountId = User::getUserId();
        //Если пользователя с указанным ID не существует...
        if (!User::isUserExists($userId)) {
            //Дописать ошибку 404.
            header('Location: /');
            die();
        }

        // Если в URL не указан ID другого пользователя
        // и сам пользователь не авторизирован, возвращаемся
        // на главную страницу.
        if ($userId !== null) $userData = User::getUserData($userId);
        else if ($accountId !== null) $userData = User::getUserData($accountId);
        else {
            //Дописать ошибку 404.
            header('Location: /');
            die();
        }
        require_once(ROOT . '/views/user/index.php');
        return true;
    }

    public function actionList()
    {
        // Получаем ID всех пользователей.
        $idArray = User::getEveryID();

        // Массив для данных всех пользователей
        $data = array();

        foreach ($idArray as $userId) {
            $data[] = User::getUserData($userId);
        }

        require_once(ROOT . '/views/user/list.php');
        return true;
    }

    /**
     * Авторизация пользователя
     */
    public function actionLogin()
    {
        //Если пользователь уже вошёл, то возвращаем его на главную страницу
        if (isset($_SESSION['user'])) {
            header('Location: /');
            die();
        }
        $login = false;
        $password = false;

        // Если форма была отправлена...
        if (isset($_POST['submit'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];

            //Список ошибок
            $errors = false;

            // Валидация полей
            if (!User::checkLogin($login)) {
                $errors[] = 'Неправильный email!';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов!';
            }

            // Проверяем существует ли пользователь
            $userId = User::checkUserData($login, $password);

            if ($userId == false) {
                // Если данные неправильные - показываем ошибку
                $errors[] = 'Неправильные данные для входа на сайт!';
            } else {
                // Если данные правильные,

                // Получаем данные пользователя.
                $userData = User::getUserData($userId);
                // Запоминаем статус пользователя и его статус бана
                $userStatus = $userData['status_id'];
                // Если пользователь забанен, то сообщаем
                // ему об этом и не даём войти на аккаунт.
                $banStatus = User::isBanned($userId);

                // Запоминаем пользователя (сессия)
                User::auth($userId, $login, $userStatus, $banStatus);

                //Записываем сообщение о том, что пользователь авторизировался
                $_SESSION['message'] =
                    [
                        'text' => "Вы успешно авторизировались! Добро пожаловать, " . $login . '!',
                        'status' => 'success'
                    ];

                // Перенаправляем пользователя на главную страницу
                header("Location: /");
                die();
            }
        }


        require_once(ROOT . '/views/user/login.php');
        return true;
    }

    /**
     * Регистрация пользователя
     */
    public function actionRegister()
    {
        //Если пользователь уже вошёл, то возвращаем его на главную страницу
        if (isset($_SESSION['user'])) {
            header('Location: /');
            die();
        }

        // Переменные для формы
        $login = false;
        $email = false;
        $birthdate = false;
        $country = false;

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы
            $login = $_POST['login'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $repeatPassword = $_POST['repeatPassword'];
            $birthdate = $_POST['birthdate'];
            $country = $_POST['country'][0];

            // Флаг ошибок
            $errors = false;

            // Валидация полей
            if (!User::checkLogin($login)) {
                $errors[] = 'Имя не должно быть короче 2-х символов!';
            }
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email!';
            }
            if (!User::checkBirthdate($birthdate)) {
                $errors[] = 'Дата не должна быть пустой!';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов!';
            }
            if (!User::checkRepeatPassword($password, $repeatPassword)) {
                $errors[] = 'Пароли не совпали!';
            }
            if (User::checkLoginExists($login)) {
                $errors[] = 'Такой логин уже используется!';
            }

            if ($errors == false) {
                // Если ошибок нет
                // Регистрируем пользователя
                User::register($login, $email, password_hash($password, PASSWORD_DEFAULT), $birthdate, $country, date('Y-m-d', time()));

                // Получаем ID зарегистрированного пользователя
                $userId = User::checkUserData($login, $password);

                // Получаем данные пользователя.
                $userData = User::getUserData($userId);

                // Запоминаем статус пользователя и его статус бана
                $userStatus = $userData['status_id'];


                // Если пользователь забанен, то сообщаем
                // ему об этом и не даём войти на аккаунт.
                $banStatus = User::isBanned($userId);

                // Cразу логиним зарегистрированного пользователя
                User::auth($userId, $login, $userStatus, $banStatus);

                //Записываем сообщение о том, что пользователь зарегистрировался
                $_SESSION['message'] =
                    [
                        'text' => "Вы успешно зарегистрировались! Добро пожаловать, " . $login . '!',
                        'status' => 'success'
                    ];
                // Перенаправляем пользователя на главную страницу
                header("Location: /");
                die();
            }
        }

        // Подключаем вид
        require_once(ROOT . '/views/user/register.php');
        return true;
    }

    public function actionUpdate()
    {
        // Проверяем, зарегистрирован ли пользователь
        $accountId = User::checkLogged();

        //Получаем данные о пользователе
        $userData = User::getUserData($accountId);

        // Переменные для формы(берём данные из БД)
        $login = $userData['login'];
        $email = $userData['e-mail'];
        $birthdate = $userData['birthdate'];
        $country = $userData['country'];

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы
            $login = $_POST['login'];
            $email = $_POST['email'];
            $birthdate = $_POST['birthdate'];
            $country = $_POST['country'][0];

            // Флаг ошибок
            $errors = false;

            // Валидация полей
            if (!User::checkLogin($login)) {
                $errors[] = 'Имя не должно быть короче 2-х символов!';
            }
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email!';
            }
            if (!User::checkBirthdate($birthdate)) {
                $errors[] = 'Дата не должна быть пустой!';
            }

            if ($errors == false) {
                // Если ошибок нет,
                // Меняем данные пользователя
                User::update($accountId, $login, $email, $birthdate, $country);

                //Записываем сообщение о том, что данные поменялись
                $_SESSION['message'] =
                    [
                        'text' => "Данные пользователя " . $login . " были успешно изменены!",
                        'status' => 'success'
                    ];
                // Перенаправляем пользователя на главную страницу
                header("Location: /user/$accountId");
                die();
            }
        }


        // Подключаем вид
        require_once(ROOT . '/views/user/update.php');
        return true;
    }

    /**
     * Выход из аккаунта
     */
    public function actionLogout()
    {
        //Если пользователь авторизирован...
        if (isset($_SESSION['user'])) {
            //Обнуляем данные пользователя в сессии.
            unset($_SESSION['user']);
            unset($_SESSION['login']);
            unset($_SESSION['status']);
            unset($_SESSION['ban']);
            //Сообщение о выходе из аккаунта
            $_SESSION['message'] =
                [
                    'text' => "Вы вышли из аккаунта!",
                    'status' => 'success'
                ];
        }
        //Перенаправляем пользователя на главную страницу
        header('Location: /');
        return true;
    }
}