<?php include ROOT . '/views/layouts/header.php'; ?>

<section>
    <div class="container">
        <div class="row">

            <br/>

            <h4>Список пользователей</h4>

            <br/>

            <table class="table-bordered table-striped table">
                <tr>
                    <th>Имя</th>
                    <th>Профиль</th>
                    <th>Записи пользователя</th>
                </tr>
                <?php foreach($data as $userData): ?>
                <tr>
                    <td><?php echo $userData['login']; ?></td>
                    <td><a href="/user/<?=$userData['id']; ?>" class="btn-default btn">К профилю</a></td>
                    <?php
                    if(isset($_SESSION['status']) and ($_SESSION['status'] == 1
                       or $_SESSION['status'] == 2 and $userData['status_id'] == 3))
                        $href = '/admin/writes/' . $userData['id'];
                    else
                        $href = '/writes/' . $userData['id'];
                    ?>
                    <td><a href="<?=$href; ?>" class="btn-default btn">К записям</a></td>
                </tr>
                <?php endforeach; ?>
            </table>

        </div>
    </div>
</section>

<?php include ROOT . '/views/layouts/footer.php'; ?>

