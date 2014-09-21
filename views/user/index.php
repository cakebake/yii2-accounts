<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('accounts', 'Accounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="clearfix">
        <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('accounts', 'Create Account'), ['create'], ['class' => 'btn btn-success pull-right']) ?>
        <?= Html::button('<span class="glyphicon glyphicon-search"></span> ' . Yii::t('accounts', 'Advanced search'), ['id' => 'toggle-advanced-search', 'class' => 'btn btn-default']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> ' . Yii::t('accounts', 'Refresh / Reset Filters'), ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <?php $this->registerJs("
    $('.accounts-index .accounts-search').hide();
    $(document).on('click', '#toggle-advanced-search', function(e){
       //e.preventDefault();
       $('.accounts-index .accounts-search').slideToggle();
    });
    "); ?>

    <?= cakebake\accounts\widgets\Alert::widget(); ?>

    <?= $this->render('_search', [
        'model' => $searchModel
    ]); ?>

    <?= GridView::widget([
        'id' => 'accounts-index-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'username',
                'label' => Yii::t('accounts', 'Name'),
                'format' => 'html',
                'value' => function ($searchModel)
                {
                    return Html::a($searchModel->getNicename(), ['profile', 'u' => $searchModel->username]);
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
            'created_at:RelativeTime',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-user"></span>', ['profile', 'u' => $model->username], [
                            'title' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-cog"></span>', ['edit', 'u' => $model->username], [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>
