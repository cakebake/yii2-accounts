<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('accounts', 'Accounts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('accounts', 'Create Account'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('accounts', 'Delete Selected'), ['delete-selected'], [
            'class' => 'accounts-index-grid-bulk-action btn btn-danger',
            'data' => [
                'confirm' => Yii::t('accounts', 'Are you sure you want to delete these items?'),
                //'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= cakebake\accounts\widgets\Alert::widget(); ?>

    <?= GridView::widget([
        'id' => 'accounts-index-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
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
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php $this->registerJs("
    $(document).on('click', '.accounts-index-grid-bulk-action', function(e){
        e.preventDefault();
        var keys = $('#accounts-index-grid').yiiGridView('getSelectedRows');

        if (keys.length == 0) {
            alert('" . Yii::t('accounts', 'No items were selected.') . "');
            return false;
        } else {
            $.ajax({
                url: $(this).attr('href'),
                type: 'POST',
                data: {
                    _csrf: yii.getCsrfToken(),
                    ids: keys
                },
                success: function(data) {
                    //console.log(data);
                },
                error: function(data) {
                    //console.log(data);
                }
            });
        }
    });
    "); ?>

</div>
