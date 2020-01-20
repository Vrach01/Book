<?php

class MainController
{
    public function actionIndex($page = 1)
    {

        if(isset($_POST['submit']) and isset($_SESSION['user'])) {
            $userId = $_SESSION['user'];
            $text = $_POST['text'];
            Main::addWrite($userId, $text);

            // Записываем сообщение о успешном добавлении записи.
            $_SESSION['message'] =
                [
                    'text' =>"Запись успешно добавлена!",
                    'status' => 'success'
                ];
            unset($_POST['submit']);
        }

        $writesList = Main::getWritesOnPage($page);

        $total = Main::getWritesCount();
        $pagination = new Pagination($total, $page, Main::SHOW_BY_DEFAULT, 'page-');


        require_once(ROOT . '/views/main/index.php');
        return true;
    }
}