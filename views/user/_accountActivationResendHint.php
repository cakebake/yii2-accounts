<?php
use yii\helpers\Html;
?>

<?php if (Yii::$app->getModule('accounts')->enableEmailSignupActivation || Yii::$app->getModule('accounts')->enableEmailEditActivation) : ?>
    <p class="activation-resend hint-block">
        <?= Yii::t('accounts', 'If you have not received an activation email, you can {resendlink} once again.', [
            'resendlink' => Html::a(Yii::t('accounts', 'Resend account activation'), ['account-activation-resend']),
        ]) ?>
    </p>
<?php endif; ?>