<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title =  Yii::t('accounts', 'Forgot your password?');
?>
<div class="accounts-forgot-password">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?= cakebake\accounts\widgets\Alert::widget(); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="panel-title"><?= Html::encode($this->title) ?></p>
                </div>
                <div class="panel-body">
                    <p><?=  Yii::t('accounts', 'Please provide the email, which belongs to your account. We will send out the instructions to restore your password.') ?></p>
                    <?php $form = ActiveForm::begin(['id' => 'forgot-password-form']); ?>
                        <?= $form->field($model, 'email') ?>
                        <?= Html::submitButton(Yii::t('accounts', 'Send'), ['class' => 'btn btn-primary']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
