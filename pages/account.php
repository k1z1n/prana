<?php
require_once __DIR__ . '/../includes/path_helper.php';
?>
<div class="banner_account">
    <h1 class="h1_banner_account container">ДОБРО ПОЖАЛОВАТЬ, <?= $USER['username'] . ' ' . $USER['surname'] ?></h1>
</div>
<div class="setting_account_block container">
    <div class="setting_card">

        <a href="<?= url('?page=user_orders') ?>"><span>Мои заказы</span></a>
        <p>Управляйте своими заказами и редактируйте их</p>
    </div>
    <div class="setting_card">
        <a href="<?= url('?page=setting_account') ?>"><span>Найстроки аккаунта</span></a>
        <p>Управляйте профилем и настройками</p>
    </div>
    <div class="setting_card">
        <a href="<?= url('?page=favorite') ?>"><span>Избранное</span></a>
        <p>Все ваши любимые произведения искусства в одном прекрасном месте</p>
    </div>
</div>
<!-- BANNER_ACCOUNT END -->