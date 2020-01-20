<?php

abstract class FlashMessage
{

    public static function showFlashMessage()
    {
        if (isset($_SESSION['message'])):?>

            <div class="row">
                <div class="col-sm-12">
                    <div class="text-center">
                        <?php if ($_SESSION['message']['status'] == 'success'): ?>
                            <div class="success"><i class="fa fa-info-circle"></i> <?= $_SESSION['message']['text']; ?></div>
                        <?php elseif ($_SESSION['message']['status'] == 'myWarning'): ?>
                            <div class="warning"><i class="fa fa-warning"></i> <?= $_SESSION['message']['text']; ?></div>
                        <?php else: ?>
                            <div class="error"><i class="fa fa-ban"></i> <?= $_SESSION['message']['text']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
            unset($_SESSION['message']);

        endif;
    }
}


