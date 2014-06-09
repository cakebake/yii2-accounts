<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('accounts', 'Reset your password');
?>
<div class="accounts-user-reset-password">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?= cakebake\accounts\widgets\Alert::widget(); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="panel-title"><?= Html::encode($this->title) ?></p>
                </div>
                <div class="panel-body">
                    <p><?= Yii::t('accounts', 'Please choose your new password.') ?></p>
                    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                        <?= $form->field($model, 'password')->passwordInput() ?>
                        <?= Html::submitButton(Yii::t('accounts', 'Save'), ['class' => 'btn btn-primary']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
