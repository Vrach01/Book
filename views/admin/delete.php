<?php include_once(ROOT . '/views/layouts/header.php'); ?>
<div id="wrapper">
    <section>
        <div class="row">

            <br/>

            <h2> Вы уверены, что хотите удалить пользователя `<?= $userData['login']; ?>`?</h2>

            <br/>
        </div>
        <div class="row">
            <form method="post">
                <input type="submit" name="yes" class="btn btn-info btn-block col-sm-4" value="Да">
                <input type="submit" name="no" class="btn btn-info btn-block col-sm-4" value="Нет">
            </form>
        </div>
    </section>
</div>
<?php include_once(ROOT . '/views/layouts/footer.php'); ?>
