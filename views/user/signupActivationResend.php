<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title =  Yii::t('accounts', 'Resend account activation');
?>
<div class="accounts-signup-activation-resend">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?= cakebake\accounts\widgets\Alert::widget(); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="panel-title"><?= Html::encode($this->title) ?></p>
                </div>
                <div class="panel-body">
                    <p><?=  Yii::t('accounts', 'Please fill in the email field with your address. It must be the address that you have entered at the registration.') ?></p>
                    <?php $form = ActiveForm::begin(['id' => 'signup-activation-resend-form']); ?>
                        <?= $form->field($model, 'email') ?>
                        <?= Html::submitButton(Yii::t('accounts', 'Send'), ['class' => 'btn btn-primary']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
