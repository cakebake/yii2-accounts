<?php
use yii\helpers\Html;
?>

<?php if (Yii::$app->getModule('accounts')->enableEmailSignupActivation) : ?>
    <p class="activation-resend hint-block">
        <?= Yii::t('accounts', 'If you are already registered, you have to activate your account first, before you can login. If you have not received an activation email, you can {resendlink} once again.', [
            'resendlink' => Html::a(Yii::t('accounts', 'Resend account activation'), ['signup-activation-resend']),
        ]) ?>
    </p>
<?php endif; ?>