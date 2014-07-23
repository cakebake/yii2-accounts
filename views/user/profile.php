<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title =  Yii::t('accounts', '{nicename}´s Profile', [
    'nicename' => $model->getNicename(),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-profile">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('accounts', 'Edit Account'), ['edit', 'u' => $model->username], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('accounts', 'Delete Account'), ['delete', 'u' => $model->username], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('accounts', 'Are you sure you want to delete this account?'),
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
