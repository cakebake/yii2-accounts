<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Signup';
?>
<div class="accounts-user-signup">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <p class="panel-title"><?= Html::encode($this->title) ?></p>
                </div>
                <div class="panel-body">
                    <p>Please fill out the following fields to signup.</p>
                    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                        <?= $form->field($model, 'username') ?>
                        <?= $form->field($model, 'email') ?>
                        <?= $form->field($model, 'password')->passwordInput() ?>
                        <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
