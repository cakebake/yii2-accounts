<?php

use yii\helpers\Html;

$this->title = Yii::t('accounts', 'Create {type}', [
    'type' => $model->getTypeTitle($type),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Accounts'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = ['label' => $model->getTypeTitle($type, true), 'url' => ['index', 'type' => $type]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="accounts-auth-create type-<?= $type ?>">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
    ]) ?>

</div>
