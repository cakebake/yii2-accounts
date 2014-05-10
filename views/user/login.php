<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Login';
?>
<div class="accounts-user-login">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="panel-title"><?= Html::encode($this->title) ?></p>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>
                    <?= $form->field($model, 'username') ?>
                    <?= $form->field($model, 'password')->passwordInput()->hint('(forgot password)') ?>
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
