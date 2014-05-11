<?php

use yii\helpers\Html;

$link = \Yii::$app->urlManager->createAbsoluteUrl(['/accounts/user/reset-password', 'token' => $user->password_reset_token]);
?>

<h1>Hello <?= Html::encode($user->username) ?>,</h1>

<p>
    Follow the link below to reset your password:<br />
    <?= Html::a(Html::encode($link), $link) ?>
</p>