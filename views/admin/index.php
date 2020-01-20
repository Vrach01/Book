<?php include_once(ROOT . '/views/layouts/header.php');?>

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
                    <th>Статус пользователя</th>
                    <th>Статус бана</th>
                    <th>Удалить пользователя</th>
                    <th>Изменить статус бана</th>
                    <?php if (Admin::isSuperAdmin($accountId)): ?>
                    <th>Изменить статус пользователя</th>
                    <?php endif;?>
                </tr>
                <?php foreach($data as $userData): ?>
                    <tr>
                        <td><?php echo $userData['login']; ?></td>
                        <td><a href="/user/<?=$userData['id']; ?>" class="btn-default btn">К профилю</a></td>
                        <?php if(Admin::isSuperAdmin($accountId) or
                            Admin::isAdmin($accountId) and !Admin::isAdmin($userData['id'])): ?>
                            <td><a href="/admin/writes/<?=$userData['id']; ?>" class="btn-default btn">К записям</a></td>
                        <?php else: ?>
                            <td><a href="/writes/<?=$userData['id']; ?>" class="btn-default btn">К записям</a></td>
                        <?php endif;?>
                        <td><?php echo User::getUserStatusText($userData['status_id']); ?></td>
                        <td><?php echo User::getBanStatusText($userData['banned']); ?></td>
                        <?php if (Admin::isSuperAdmin($accountId) or
                        Admin::isAdmin($accountId) and !Admin::isAdmin($userData['id'])): ?>
                        <td><a href="/admin/delete/<?=$userData['id']; ?>" class="btn-default btn">Удалить</a></td>
                        <td><a href="/admin/ban/<?=$userData['id']; ?>" class="btn-default btn">Изменить</a></td>
                        <?php endif;?>
                        <?php if (Admin::isSuperAdmin($accountId)): ?>
                        <td><a href="/admin/changeStatus/<?=$userData['id']; ?>" class="btn-default btn">Изменить</a></td>
                        <?php endif;?>
                    </tr>
                <?php endforeach; ?>
            </table>

        </div>
    </div>
</section>

<?php include_once(ROOT . '/views/layouts/footer.php');?>
