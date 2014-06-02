<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var cakebake\accounts\models\search\AdminSearch $searchModel
 */

$this->title = Yii::t('accounts', 'Manage accounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('accounts', 'Create Account'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('accounts', 'Delete Selected'), ['delete-selected'], [
            'class' => 'accounts-admin-grid-bulk-action btn btn-danger',
            'data' => [
                'confirm' => Yii::t('accounts', 'Are you sure you want to delete these items?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

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
            'created_at:RelativeTime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

<?php $this->registerJs("
    jQuery('.accounts-admin-grid-bulk-action').click(function(event){
        event.preventDefault();
        var keys = jQuery('#accounts-admin-grid').yiiGridView('getSelectedRows');
        if (keys.length !== 0) {
            jQuery.ajax({
                url: jQuery(this).attr('href'),
                type: 'post',
                data: {_csrf: yii.getCsrfToken(), ids: keys}
            });
        } else {
            alert('" . Yii::t('accounts', 'No items were selected.') . "');
            return false;
        }
    });
"); ?>