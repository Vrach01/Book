<?php include ROOT . '/views/layouts/header.php'; ?>

    <section>
        <div class="container">
            <div class="row">

                <div class="col-sm-4 col-sm-offset-4 padding-right">

                    <?php if (isset($errors) and is_array($errors)): ?>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li> - <?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="signup-form"><!--sign up form-->
                        <h2>Вход на сайт</h2>
                        <form action="#" method="post">
                            <input type="text" name="login" placeholder="Логин" value="<?php echo $login; ?>"/>
                            <input type="password" name="password" placeholder="Пароль" value=""/>
                            <input type="submit" name="submit" class="btn btn-default" value="Вход" />
                        </form>
                        Нет аккаунта? <a href="/user/register">Зарегистрируйтесь!</a>
                    </div><!--/sign up form-->


                    <br/>
                    <br/>
                </div>
            </div>
        </div>
    </section>

<?php include ROOT . '/views/layouts/footer.php'; ?>