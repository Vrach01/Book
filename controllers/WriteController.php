<?php

class WriteController
{
    public function actionIndex($userId, $page = 1)
    {

        //Блок удаления записей.
        if (isset($_POST['write']) and $_SESSION['user'] == $userId) {

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
        $accountId = User::getUserId();

        $writesList = Write::getUserWritesOnPage($page, $userId);

        $total = Write::getUserWritesCount($userId);
        $pagination = new Pagination($total, $page, Main::SHOW_BY_DEFAULT, 'page-');

        require_once(ROOT . '/views/write/index.php');
        return true;
    }

    public function actionEdit($userId, $writeId)
    {

        $accountId = User::getUserId();
        //Проверка, имеет ли право пользователь изменять запись.
        if($accountId == $userId or Admin::isSuperAdmin($accountId)
            or Admin::isSuperAdmin($accountId) and !Admin::isAdmin($userId)) {

            $writeData = Write::getWriteById($writeId);
            // Переменная для текста записи.
            $writeText = $writeData['text'];

            if (isset($_POST['submit'])) {
                $writeText = $_POST['text'];
                $result = Write::updateWrite($writeId, $writeText);
                //Если обновление успешно выполнено, сообщаем об этом
                if($result) {
                    $_SESSION['message'] =
                        [
                            'text' => "Обновление записи выполнено!",
                            'status' => 'success'
                        ];
                    // Если аккаунт пользователя по статусу выше,
                    // чем рассматриваемый пользователь, используем
                    // ссылку на записи через админку
                    if(Admin::isSuperAdmin($accountId)
                        or Admin::isSuperAdmin($accountId) and !Admin::isAdmin($userId)) {
                        header("Location: /admin/writes/$userId");
                        die();
                    }
                    // Иначе просмотр через пользователя
                    else {
                        header("Location: /writes/$userId");
                        die();
                    }
                }
                else {
                    $_SESSION['message'] =
                        [
                            'text' => "Обновление записи не удалось!",
                            'status' => 'error'
                        ];
                    if(Admin::isSuperAdmin($accountId)
                        or Admin::isSuperAdmin($accountId) and !Admin::isAdmin($userId)) {
                        header("Location: /admin/writes/$userId");
                        die();
                    }
                    // Иначе просмотр через пользователя
                    else {
                        header("Location: /writes/$userId");
                        die();
                    }
                }
            }

            require_once(ROOT . '/views/write/edit.php');
            return true;
        }
        else {
            $_SESSION['message'] =
                [
                    'text' => "Доступ запрещён!",
                    'status' => 'error'
                ];
            header("Location: /writes/$userId");
            die();
        }

    }

}
