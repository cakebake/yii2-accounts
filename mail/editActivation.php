<?php

use yii\helpers\Html;

$link = \Yii::$app->urlManager->createAbsoluteUrl(['/accounts/user/account-activation', 'email' => $user->email, 'auth_key' => $user->auth_key]);
?>

<h1><?= Yii::t('accounts', 'Hello {nicename}, ', ['nicename' => Html::encode($user->getNicename())]) ?></h1>

<p>
    <?= Yii::t('accounts', 'your identity settings have been updated. To login again, ') ?>
    <?= Yii::t('accounts', 'follow the link below to activate your account:') ?><br />
    <?= Html::a(Html::encode($link), $link) ?>
</p>