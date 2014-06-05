<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var cakebake\accounts\models\Admin $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => 60])->passwordInput() ?>

    <?= $form->field($model, 'rePassword')->textInput(['maxlength' => 60])->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('accounts', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
