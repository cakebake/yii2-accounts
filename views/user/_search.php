<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="accounts-search panel panel-default">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'form-horizontal',
        ],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-9\">{input}</div>\n<div class=\"col-md-9\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-3 control-label'],
        ],
    ]); ?>

    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'status')->dropDownList($model->getDefinedStatusArray(), ['prompt' => Yii::t('accounts', 'Please select')]) ?>
                <?= $form->field($model, 'role')->dropDownList($model->getDefinedRolesArray(), ['prompt' => Yii::t('accounts', 'Please select')]) ?>
            </div>
        </div>
    </div>

    <div class="panel-footer text-right">
        <?= Html::submitButton(Yii::t('accounts', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('accounts', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
