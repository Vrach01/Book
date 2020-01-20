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
                            <h2>Регистрация на сайте</h2>
                            <form action="#" method="post">
                                <input type="text" name="login" placeholder="Имя" value="<?php echo $login; ?>"/>
                                <input type="email" name="email" placeholder="E-mail" value="<?php echo $email; ?>"/>
                                <label>Дата рождения:</label>
                                <input type="date" name="birthdate" value="<?php echo $birthdate; ?>" min="1900.01.01" max="<?php
                                echo date('Y.m.d',time());?>"/>
                                <select class="form-control" name="country[]">
                                    <?=Option::getOption('Belarus',$country); ?>
                                    <?=Option::getOption('Russia',$country); ?>
                                    <?=Option::getOption('Ukraine',$country); ?>
                                    <?=Option::getOption('United Kingdom',$country); ?>
                                    <?=Option::getOption('USA',$country); ?>
                                    <?=Option::getOption('DEUTSCHLAND',$country); ?>
                                </select><br>
                                <input type="submit" name="submit" class="btn btn-default" value="Изменить данные" />
                            </form>
                        </div><!--/sign up form-->

                    <br/>
                    <br/>
                </div>
            </div>
        </div>
    </section>

<?php include ROOT . '/views/layouts/footer.php'; ?>