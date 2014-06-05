<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title =  Yii::t('accounts', '{nicename}´s Profile', [
    'nicename' => Yii::$app->user->getNicename(),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($myID == $model->id) : ?>
        <p>
            <?= Html::a(Yii::t('accounts', 'Update'), ['profile-update'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('accounts', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('accounts', 'Are you sure you want to delete your account?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'username',
            'email:email',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'role',
            'status',
            'updated_at',
            'created_at',
        ],
    ]) ?>

</div>
