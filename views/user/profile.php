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
                'label' => Yii::t('accounts', 'Updated'),
                'format' => 'html',
                'value' => '<abbr title="' . $model->updated_at . '">' . $model->getUpdatedTime() . '</abbr> ' . Yii::t('accounts', 'by') . ' ' . $model->getProfileLinkbyId($model->updated_by, Yii::t('accounts', 'Nobody')),
                'visible' => Yii::$app->user->can('manager'),
            ],
            [
                'attribute' => 'created_at',
                'label' => Yii::t('accounts', 'Created'),
                'format' => 'html',
                'value' => '<abbr title="' . $model->created_at . '">' . $model->getCreatedTime() . '</abbr> ' . Yii::t('accounts', 'by') . ' ' . $model->getProfileLinkbyId($model->created_by, Yii::t('accounts', 'Nobody')),
                'visible' => Yii::$app->user->can('manager'),
            ],
        ], $model->detailViewProfileData),
    ]) ?>

</div>
