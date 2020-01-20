<?php include_once(ROOT . '/views/layouts/header.php');?>

<div id="wrapper">
    <h2>Записи пользователя <?=$userData['login']; ?></h2>
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
                <?php if($accountId == $userId): ?>
                    <span class="btn-link"><a href="/writes/<?=$userId ?>/edit-<?=$write['id'];?>"><i class="fa fa-pencil"></i></a></span>
                <?php endif;?>
                <?php if(isset($_SESSION['user']) and $_SESSION['user'] == $userId): ?>
                <span>
                    <form action="" method="post">
                        <input type="hidden" name="write" value="<?=$write['id']; ?>">
                        <input type="submit" class="btn-default" value="удалить">
                    </form>
                </span>
                <?php endif;?>
            </p>
            <p><?=$write['text']; ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?php include_once(ROOT . '/views/layouts/footer.php');?>
