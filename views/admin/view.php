<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var cakebake\accounts\models\Admin $model
 */

$this->title = $model->getNicename();
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Manage accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-admin-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('accounts', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('accounts', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('accounts', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            //'auth_key',
            //'password_hash',
            'password_reset_token',
            [
                'attribute' => 'status',
                'value' => $model->getStatus(),
            ],
            [
                'attribute' => 'role',
                'value' => $model->getRole(),
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'html',
                'value' => $model->getUpdatedTime() . ' <span class="text-muted">(' . $model->updated_at . ')</span>',
            ],
            [
                'attribute' => 'created_at',
                'format' => 'html',
                'value' => $model->getCreatedTime() . ' <span class="text-muted">(' . $model->created_at . ')</span>',
            ],
        ],
    ]) ?>

</div>
