<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('accounts', 'Account Login');
?>
<div class="accounts-user-login">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?= cakebake\accounts\widgets\Alert::widget(); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="panel-title"><?= Html::encode($this->title) ?></p>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>

                    <?= $form->field($model, 'username')->label(Yii::t('accounts', 'Username or Email')) ?>

                    <?= $form->field($model, 'password')->passwordInput()->hint('<span class="glyphicon glyphicon-hand-right"></span> ' . Html::a(Yii::t('accounts', 'Forgot your password?'), ['/accounts/user/forgot-password'])) ?>

                    <?php if (Yii::$app->user->enableAutoLogin) : ?>
                        <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    <?php endif; ?>

                    <?= Html::submitButton(Yii::t('accounts', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
