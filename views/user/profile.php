<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('accounts', '{nicename}´s Profile', ['nicename' => $model->getNicename()]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-profile">

    <?= cakebake\accounts\widgets\Alert::widget(); ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('manager')) : ?>
    <p>
        <?= Html::a(Yii::t('accounts', 'Edit Account'), ['edit', 'u' => $model->username], ['class' => 'btn btn-primary']) ?>
        <?= Yii::$app->user->can('admin') ? Html::a(Yii::t('accounts', 'Delete Account'), ['delete', 'u' => $model->username], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('accounts', 'Are you sure you want to delete this account?'),
                'method' => 'post',
            ],
        ]) : null ?>
    </p>
    <?php endif ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => ArrayHelper::merge([
            [
                'attribute' => 'username',
                'label' => Yii::t('accounts', 'Name'),
                'value' => $model->getNicename(),
            ],
            'email:email',
            [
                'attribute' => 'role',
                'value' => $model->getRole(),
                'visible' => Yii::$app->user->can('manager'),
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatus(),
                'visible' => Yii::$app->user->can('manager'),
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'html',
                'value' => $model->getUpdatedTime() . ' <span class="text-muted">(' . $model->updated_at . ')</span>',
                'visible' => Yii::$app->user->can('manager'),
            ],
            [
                'attribute' => 'created_at',
                'format' => 'html',
                'value' => $model->getCreatedTime() . ' <span class="text-muted">(' . $model->created_at . ')</span>',
                'visible' => Yii::$app->user->can('manager'),
            ],
        ], $model->detailViewProfileData),
    ]) ?>

</div>
