<?php include_once(ROOT . '/views/layouts/header.php');?>

<div id="wrapper">
    <h1>Гостевая книга</h1>
    <div>
        <nav>
            <!-- Постраничная навигация-->
            <?=$pagination->get(); ?>
            <!-- Постраничная навигация-->
        </nav>
    </div>
    <?php foreach($writesList as $write) :  ?>
    <div class="note">
        <p>
            <span class="date"><?=$write['time']; ?></span>
            <span class="name"><?=$write['name']; ?></span>
        </p>
        <p><?=$write['text']; ?></p>
    </div>
    <?php endforeach; ?>
    <?php if(isset($_SESSION['user'])): ?>
    <div id="form">
        <form action="" method="POST">
            <p><textarea name="text" class="form-control" placeholder="Ваш отзыв"></textarea></p>
            <p><input type="submit" name="submit" class="btn btn-info btn-block" value="Сохранить"></p>
        </form>
    </div>
    <?php endif;?>
</div>

<?php include_once(ROOT . '/views/layouts/footer.php');?>
