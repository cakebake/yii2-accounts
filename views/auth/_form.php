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
                    'id' => 'role-permissions-grid',
                    'layout' => "{items}\n{pager}\n{summary}",
                    'tableOptions' => [
                        'class' => 'table table-striped table-hover table-condensed table-bordered',
                    ],
                    'rowOptions' => function ($row, $key, $index, $grid) {
                        return [
                            'class' => $row['isChild'] ? 'success' : null,
                        ];
                    },
                    'columns' => [
                        [
                            'header' =>  Html::checkBox('permissions_all', false, ['class' => 'select-all-on-check']),
                            'format' => 'raw',
                            'value' => function ($row, $key, $index, $column)
                            {
                                return Html::checkbox('assignedChildren[' . $row['name'] . ']', $row['isChild'], ['class' => 'select-on-check', 'id' => $row['name']]);
                            },
                        ],
                        [
                            'attribute' => 'name',
                            'format' => 'raw',
                            'value' => function ($row)
                            {
                                return Html::label($row['name'], $row['name']);
                            }
                        ],
                        //'description:ntext',
                        'ruleName',
                        'createdAt:RelativeTime',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'buttons' => [
                                'delete' => function ($url, $model, $key) {
                                    return null;
                                },
                            ],
                        ],
                    ],
                ]); ?>
                <?php
                    //js for own header "select all checkbox" column
                    $this->registerJs("
                        jQuery('.select-all-on-check').click(function(){
                            $('.select-on-check').prop('checked', $(this).prop('checked'));
                        });
                    ");
                ?>
            <?php endif ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('accounts', 'Create') : Yii::t('accounts', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
