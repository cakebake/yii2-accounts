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
            'autocorrect' => 'off',
        ],
    ]); ?>

    <?php if ($model->hasProfileData()) : ?>
        <div class="panel panel-profile panel-default">
            <div class="panel-heading">
                <?= Yii::t('accounts', 'Profile') ?>
            </div>
            <div class="panel-body">
                <?php foreach ($model->profileData['field_value'] as $key => $val) : ?>

                    <?= $form->field($profileData, $key)->textInput() ?>

                <?php endforeach ?>
            </div>
        </div>
    <?php endif ?>

    <div class="panel panel-settings panel-default">
        <div class="panel-heading">
            <?= Yii::t('accounts', 'Settings') ?>
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'status')->dropDownList($model->getDefinedStatusArray(), ['prompt' => Yii::t('accounts', 'Please select')]) ?>
            <?= $form->field($model, 'role')->dropDownList($model->getDefinedRolesArray(), ['prompt' => Yii::t('accounts', 'Please select')]) ?>
        </div>
    </div>

    <div class="panel panel-identity <?= $model->isNewRecord ? 'panel-primary' : 'panel-danger' ?>">
        <div class="panel-heading">
            <?= Yii::t('accounts', 'Identity') ?>
        </div>
        <div class="panel-body">
            <?php if (!$model->isNewRecord && Yii::$app->getModule('accounts')->enableEmailEditActivation) : ?>
                <p><?= Yii::t('accounts', 'If you change the following information, the account needs to be reactivated. For this purpose, an activation email will be sent.') ?></p>
            <?php endif ?>
            <?= $form->field($model, 'username')->textInput(['maxlength' => 60]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => 60]) ?>
        </div>
    </div>

    <div class="panel panel-password <?= $model->isNewRecord ? 'panel-primary' : 'panel-danger' ?>">
        <div class="panel-heading">
            <?= Yii::t('accounts', 'Password') ?>
        </div>
        <div class="panel-body">
            <?php if (!$model->isNewRecord) : ?>
                <p><?= Yii::t('accounts', 'To change the password, you must enter the current password. Otherwise you can leave this fields blank.') ?></p>
            <?php endif ?>
            <?= $form->field($model, 'password', ['enableAjaxValidation' => false])->textInput(['maxlength' => 60])->label(!$model->isNewRecord ? Yii::t('accounts', 'New Password') : null)->passwordInput() ?>
            <?= $form->field($model, 'rePassword', ['enableAjaxValidation' => false])->textInput(['maxlength' => 60])->passwordInput() ?>
            <?php if (!$model->isNewRecord) : ?>
                <?= $form->field($model, 'curPassword', ['enableAjaxValidation' => false])->textInput(['maxlength' => 60])->passwordInput() ?>
            <?php endif ?>
        </div>
    </div>

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
