<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = $searchModel->getTypeTitle($type, true);
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Accounts'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-auth-index type-<?= $type ?>">

    <div class="pull-right">
        <?= Html::a('<span class="glyphicon glyphicon-refresh"></span> ' . Yii::t('accounts', 'Reset all'), ['index', 'type' => $type], ['class' => 'btn btn-default']) ?>
        <div class="btn-group">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('accounts', 'Create {type}', [
                'type' => $searchModel->getTypeTitle($type),
            ]), ['create', 'type' => $type], ['class' => 'btn btn-success']) ?>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <?php foreach ($searchModel->typeDefinition as $tKey => $tTitle) : ?>
                    <li>
                        <?= Html::a(Yii::t('accounts', 'Create {type}', [
                            'type' => $tTitle,
                        ]), ['create', 'type' => $tKey]) ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>

    <h1><?= Yii::t('accounts', 'Role based access control') ?> <small>RBAC</small></h1>

    <ul class="nav nav-tabs" role="tablist">
        <?php foreach ($searchModel->typeDefinition as $tKey => $tTitle) : ?>
            <li class="<?= ($tKey == $type) ? 'active' : null ?>">
                <?= Html::a($searchModel->getTypeTitle($tKey, true), ['index', 'type' => $tKey]) ?>
            </li>
        <?php endforeach ?>
    </ul>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <br />

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'format' => 'html',
                'value' => function ($searchModel)
                {
                    return $searchModel->itemLink;
                }
            ],
            //'type',
            'description:ntext',
            //'rule_name',
            //'data:ntext',
            'created_at:RelativeTime',
            // 'updated_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
