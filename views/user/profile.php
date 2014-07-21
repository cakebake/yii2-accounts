<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->getNicename() . '´s ' . Yii::t('accounts', 'Profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-profile">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'username',
                'label' => Yii::t('accounts', 'Name'),
                'value' => $model->getNicename(),
            ],
            'email:email',
            [
                'attribute' => 'role',
                'value' => $model->getRole(),
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatus(),
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
