<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

?>

<div class="accounts-auth-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col col-md-4">
            <h2><?= Yii::t('accounts', 'Details') ?></h2>
            <?= $form->field($model, 'name')->textInput(['maxlength' => 64])->hint(Yii::t('accounts', 'May only consist of letters, numbers, underscores and dashes.')) ?>
            <?= $form->field($model, 'description')->textarea() ?>
        </div>
        <div class="col col-md-8">
            <?php if (!empty($possibleChildren)) : ?>
                <h2><?= Yii::t('accounts', 'Permissions') ?></h2>
                <?= GridView::widget([
                    'dataProvider' => $possibleChildren,
                    'layout' => "{items}\n{pager}\n{summary}",
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
//                            'checkboxOptions' => [
//                                'checked' => function ($row, $assignedChildren)
//                                {
//                                    return $row->name == 'createAccount';
//                                    //return array_key_exists($row->name, $assignedChildren);
//                                }
//                            ],
//                            'checkboxOptions' => function ($row)
//                            {
//                                global $assignedChildren;
//                                DebugBreak();
//                                return [
//                                    'checked' => array_key_exists($row->name, $assignedChildren) ? 'checked' : null,
//                                ];
//                            }
                        ],
                        [
                            'attribute' => 'name',
                            'format' => 'raw',
                            'value' => function ($row)
                            {
                                return Html::a($row->name, ['view', 'id' => $row->name]);
                            }
                        ],
                        'description:ntext',
                        'ruleName',
                        'createdAt:RelativeTime',
                    ],
                ]); ?>
            <?php endif ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('accounts', 'Create') : Yii::t('accounts', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
