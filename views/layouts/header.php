<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Гостевая книга</title>
    <link rel="stylesheet" href="/template/css/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/template/css/bootstrap/css/bootstrap-theme.css">
    <link rel="stylesheet" href="/template/css/bootstrap.min.css">
    <link rel="stylesheet" href="/template/css/font-awesome.min.css">
    <link rel="stylesheet" href="/template/css/styles.css">
</head>
<body>
    <header id="header">
        <div class="header_top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="pull-left">
                            <ul class="nav navbar-nav">
                                <li><a href="/"><i class="fa fa-windows"></i> Главная</a></li>
                                <li><a href="/usersList"><i class="fa fa-users"></i> Пользователи</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="pull-right">
                            <ul class="nav navbar-nav">
                                <?php if(isset($_SESSION['status']) and $_SESSION['status'] != 3): ?>
                                    <li><a href="/admin"><i class="fa fa-beer"></i> Админ-панель</a></li>
                                <?php endif; ?>
                                <?php if(isset($_SESSION['user'])):?>
                                    <li><a href="/user/<?=$_SESSION['user']; ?>"><i class="fa fa-user"></i> Аккаунт(<?=$_SESSION['login']; ?>)</a></li>
                                    <li><a href="/user/logout"><i class="fa fa-unlock"></i> Выход</a></li>
                                <?php else : ?>
                                    <li><a href="/user/login"><i class="fa fa-lock"></i> Вход</a></li>
                                <?php endif;?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php FlashMessage::showFlashMessage(); ?>
            </div>
        </div>
    </header>