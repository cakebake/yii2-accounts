<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Accounts'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = ['label' => $model->getTypeTitle($model->type, true), 'url' => ['index', 'type' => $model->type]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-auth-view type-<?= $model->type ?>">

    <p class="pull-right">
        <?= Html::a(Yii::t('accounts', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('accounts', 'Delete'), ['delete', 'id' => $model->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('accounts', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <h1><?= Html::encode($this->title) ?> <small><?= $model->typeTitle ?></small></h1>

    <?php if (!empty($model->description)) : ?>
        <div class="description">
            <?= $model->description ?>
        </div>
    <?php endif ?>

    <?php if (Yii::$app->getModule('accounts')->showCodeUsageHints) : ?>
        <p class="usage-hint">
            <span><?= Yii::t('accounts', 'Example usage:') ?></span>
            <code>&lt;?php if (\Yii::$app-&gt;user-&gt;can('<?= $model->name ?>')) : ?&gt;</code>
        </p>
    <?php endif ?>

    <div class="row">
        <div class="col col-md-4">
            <h2><?= Yii::t('accounts', 'Details') ?></h2>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => Yii::$app->getModule('accounts')->showCodeUsageHints ? Html::textInput(null, $model->name, ['readonly' => 'readonly', 'onclick' => 'this.focus();this.select();', 'class' => 'form-control', 'style' => 'cursor:pointer;']) : $model->name,
                    ],
                    'typeTitle',
                    'rule_name',
                    'data:ntext',
                    'created_at:RelativeTime',
                    'updated_at:RelativeTime',
                ],
            ]) ?>
        </div>

        <div class="col col-md-8">
            <?php if (isset($assigned['permissions'])) : ?>
                <h2><?= Yii::t('accounts', 'Assigned Permissions') ?></h2>
                <?= GridView::widget([
                    'dataProvider' => $assigned['permissions'],
                    'layout' => "{items}\n{pager}\n{summary}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
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

</div>
