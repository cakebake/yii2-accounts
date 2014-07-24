<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="accounts-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-edit',
        'enableAjaxValidation' => true,
        'options' => [
            'autocomplete' => 'off',
            'autocapitalize' => 'off',
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => 60])->label(!$model->isNewRecord ? Yii::t('accounts', 'New Password') : null)->passwordInput() ?>

    <?= $form->field($model, 'rePassword')->textInput(['maxlength' => 60])->passwordInput() ?>

    <?= !$model->isNewRecord ? $form->field($model, 'curPassword')->textInput(['maxlength' => 60])->hint(Yii::t('accounts', 'To change your password, you must enter your current password. Otherwise you can leave this field blank.'))->passwordInput() : null ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('accounts', 'Create') : Yii::t('accounts', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= !$model->isNewRecord ? Html::a(Yii::t('accounts', 'Delete Account'), ['delete', 'u' => $model->username], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('accounts', 'Are you sure you want to delete this account?'),
                'method' => 'post',
            ],
        ]) : null ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
