<?php

use yii\helpers\Html;

$link = \Yii::$app->urlManager->createAbsoluteUrl(['/accounts/user/reset-password', 'token' => $user->password_reset_token]);
?>

<h1><?= Yii::t('accounts', 'Hello {nicename}, ', ['nicename' => Html::encode($user->getNicename())]) ?></h1>

<p>
    <?= Yii::t('accounts', 'Follow the link below to reset your password:') ?><br />
    <?= Html::a(Html::encode($link), $link) ?>
</p>