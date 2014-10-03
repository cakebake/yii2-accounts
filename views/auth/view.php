<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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
        <br />
    <?php endif ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'name',
            //'type',
            'typeTitle',
            //'description:ntext',
            'rule_name',
            'data:ntext',
            'created_at:RelativeTime',
            'updated_at:RelativeTime',
        ],
    ]) ?>

    <?php if (Yii::$app->getModule('accounts')->showCodeUsageHints) : ?>
        <div class="usage-hint">
            <h4><?= Yii::t('accounts', 'Example usage in the source code:') ?></h4>
            <code>if (\Yii::$app->user->can('<?= $model->name ?>')) { ... }</code>
        </div>
    <?php endif ?>

</div>
