<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var cakebake\accounts\models\search\AdminSearch $searchModel
 */

$this->title = Yii::t('accounts', 'Administrating user accounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a(Yii::t('accounts', 'Create Account'), ['create'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'id' => 'accounts-admin-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
            'id',
            [
                'attribute' => 'username',
                'format' => 'html',
                'value' => function ($model)
                {
                    return Html::a($model['username'], ['view', 'id' => $model['id']]);
                }
            ],
            'email:email',
            [
                'attribute' => 'status',
                'value' => function ($model)
                {
                    return $model->getStatus();
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', $searchModel->getDefinedStatusArray(), ['class' => 'form-control', 'prompt' => Yii::t('accounts', 'Please select')])
            ],
            [
                'attribute' => 'role',
                'value' => function ($model)
                {
                    return $model->getRole();
                },
                'filter' => Html::activeDropDownList($searchModel, 'role', $searchModel->getDefinedRolesArray(), ['class' => 'form-control', 'prompt' => Yii::t('accounts', 'Please select')])
            ],
            'updated_at:RelativeTime',
            'created_at:RelativeTime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
