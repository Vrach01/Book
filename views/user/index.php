<?php include ROOT . '/views/layouts/header.php'; ?>

<section>
    <div class="container">
        <div class="row">

            <br/>

            <?php if($accountId == $userData['id']): ?>
            <a href="/user/update" class="btn btn-default back"><i class="fa fa-pencil"></i> Редактировать профиль</a>
            <?php endif; ?>
            <?php
                if(isset($_SESSION['status']) and ($_SESSION['status'] == 1
                or $_SESSION['status'] == 2 and $userData['status_id'] == 3))
                    $href = '/admin/writes/' . $userData['id'];
                else
                    $href = '/writes/' . $userData['id'];
            ?>
            <a href="<?=$href ?>" class="btn btn-default back"><i class="fa fa-book"></i> Записи пользователя</a>

            <h4>Данные о пользователе</h4>

            <br/>

            <table class="table-bordered table-striped table">
                <tr>
                    <th>Имя</th>
                    <th>Дата рождения</th>
                    <th>E-mail</th>
                    <th>Дата регистрации</th>
                    <th>Страна</th>
                    <th>Статус пользователя</th>
                    <th>Статус бана</th>
                </tr>
                    <tr>
                        <td><?php echo $userData['login']; ?></td>
                        <td><?php echo date('d.m.Y',strtotime($userData['birthdate'])); ?></td>
                        <td><?php echo $userData['e-mail']; ?></td>
                        <td><?php echo date('d.m.Y',strtotime($userData['reg_date'])); ?></td>
                        <td><?php echo $userData['country']; ?></td>
                        <td><?php echo User::getUserStatusText($userData['status_id']); ?></td>
                        <td><?php echo User::getBanStatusText($userData['banned']); ?></td>
                    </tr>
            </table>

        </div>
    </div>
</section>

<?php include ROOT . '/views/layouts/footer.php'; ?>

