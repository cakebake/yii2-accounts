<?php

use yii\helpers\Html;

$this->title = Yii::t('accounts', 'Update {modelName}', [
    'modelName' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Accounts'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = ['label' => $model->getTypeTitle($model->type, true), 'url' => ['index', 'type' => $model->type]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-auth-update">

    <h1><?= Html::encode($this->title) ?> <small><?= $model->typeTitle ?></small></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'type' => $model->type,
        'possibleChildren' => $possibleChildren,
    ]) ?>

</div>
