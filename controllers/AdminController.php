<?php

class AdminController
{

    public function actionIndex()
    {
        // Проверяем, залогинился ли пользователь.
        $accountId = User::checkLogged();
        // Если пользователь не админ, то запрещаем доступ.
        if (!Admin::isAdmin($accountId)) {
            $_SESSION['message'] =
                [
                    'text' => "Доступ запрещён!",
                    'status' => 'error'
                ];
            header('Location: /');
            die();
        }

        // Получаем ID всех пользователей.
        $idArray = User::getEveryID();

        // Массив для данных всех пользователей
        $data = array();

        foreach ($idArray as $userId) {
            // Пропускаем собственную запись.
            if ($_SESSION['user'] == $userId) continue;
            $data[] = User::getUserData($userId);
        }

        require_once(ROOT . '/views/admin/index.php');
        return true;
    }

    /**
     * Action для просмотра записей пользователей, где можно их удалять как админ.
     * @param $userId
     * @param int $page
     * @return bool
     */
    public function actionWrites($userId, $page = 1)
    {

        //Блок удаления записей.
        if (isset($_POST['write'])) {

            $result = Write::deleteOneWrite($_POST['write']);
            if ($result) {
                $_SESSION['message'] =
                    [
                        'text' => "Запись успешно удалена!",
                        'status' => 'success'
                    ];
            } else {
                $_SESSION['message'] =
                    [
                        'text' => "При удалении произошла ошибка!",
                        'status' => 'error'
                    ];
            }

            unset($_POST['write']);
        }

        $userData = User::getUserData($userId);
        $accountId =User::getUserId();

        $writesList = Write::getUserWritesOnPage($page, $userId);

        $total = Write::getUserWritesCount($userId);
        $pagination = new Pagination($total, $page, Main::SHOW_BY_DEFAULT, 'page-');


        require_once(ROOT . '/views/admin/writes.php');
        return true;
    }

    public function actionBan($userId)
    {
        // Проверяем, залогинился ли пользователь.
        $accountId = User::checkLogged();
        // Если пользователь не админ, то запрещаем доступ.
        if (!Admin::isAdmin($accountId)) {
            $_SESSION['message'] =
                [
                    'text' => "Доступ запрещён!",
                    'status' => 'error'
                ];
            header('Location: /');
            die();
        }
        // Меняем статус бана пользователя
        Admin::changeBanStatus($userId);

        $userData = User::getUserData($userId);
        // Сообщаем об успешном изменении статуса бана
        $_SESSION['message'] =
            [
                'text' => "Статус бана пользователя $userData[login] успешно изменён!",
                'status' => 'success'
            ];
        header('Location: /admin');
        die();
    }

    public function actionStatus($userId)
    {
        // Проверяем, залогинился ли пользователь.
        $accountId = User::checkLogged();
        // Если пользователь не админ, то запрещаем доступ.
        if (!Admin::isAdmin($accountId)) {
            $_SESSION['message'] =
                [
                    'text' => "Доступ запрещён!",
                    'status' => 'error'
                ];
            header('Location: /');
            die();
        }

        // Меняем статус бана пользователя, если это делает суперадмин
        if (Admin::isSuperAdmin($accountId)) {

            Admin::changeUserStatus($userId);

            $userData = User::getUserData($userId);
            // Сообщаем об успешном изменении статуса бана
            $_SESSION['message'] =
                [
                    'text' => "Статус пользователя $userData[login] успешно изменён!",
                    'status' => 'success'
                ];
        }
        // Возвращаемся на админ-панель
        header('Location: /admin');
        die();
    }

    public function actionDelete($userId)
    {
        // Проверяем, залогинился ли пользователь.
        $accountId = User::checkLogged();
        // Если пользователь не админ, то запрещаем доступ.
        if (!Admin::isAdmin($accountId)) {
            $_SESSION['message'] =
                [
                    'text' => "Доступ запрещён!",
                    'status' => 'error'
                ];
            header('Location: /');
            die();
        }

        $userData = User::getUserData($userId);
        // Подтверждение получено, значит, удаляем пользователя и его записи.
        if (isset($_POST['yes'])) {

            //Дополнительно проверяем, чтобы статус удаляемого
            //пользователя был ниже, чем у того, кто удаляет
            if (Admin::isSuperAdmin($accountId) or
                Admin::isAdmin($accountId) and !Admin::isAdmin($userId)) {
                $deleteResult = Admin::deleteUser($userId);
                if ($deleteResult) {
                    Admin::deleteUserWrites($userId);
                    $_SESSION['message'] =
                        [
                            'text' => "Аккаунт пользователя $userData[login] и его записи успешно удалены!",
                            'status' => 'success'
                        ];
                    // Возвращаемся на админ-панель
                    header('Location: /admin');
                    die();
                } else {
                    $_SESSION['message'] =
                        [
                            'text' => "Ошибка при удалении пользователя!",
                            'status' => 'error'
                        ];
                    // Возвращаемся на админ-панель
                    header('Location: /admin');
                    die();
                }
            }

            $_SESSION['message'] =
                [
                    'text' => "Недостаточно прав для удаления пользователя!",
                    'status' => 'warning'
                ];
            // Возвращаемся на админ-панель
            header('Location: /admin');
            die();
        }
        if (isset($_POST['no'])) {
            // Возвращаемся на админ-панель
            header('Location: /admin');
            die();
        }
        require_once(ROOT . '/views/admin/delete.php');
        return true;
    }

}