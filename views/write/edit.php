<?php include ROOT . '/views/layouts/header.php'; ?>

    <div id="wrapper">
        <section>
            <div class="container">
                <div class="row">

                    <div class="col-sm-4">

                        <h2>Изменение записи</h2>
                        <div id="form">
                            <form action="" method="POST">
                                <p><textarea name="text" class="form-control-noResize" placeholder="Ваш отзыв"><?=$writeText; ?></textarea></p>
                                <p><input type="submit" name="submit" class="btn btn-info btn-block" value="Сохранить">
                                </p>
                            </form>
                        </div>


                        <br/>
                        <br/>
                    </div>
                </div>
            </div>
        </section>
    </div>


<?php include ROOT . '/views/layouts/footer.php'; ?>