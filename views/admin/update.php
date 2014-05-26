<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var cakebake\accounts\models\Admin $model
 */

$this->title = Yii::t('accounts', 'Update {modelClass}: ', [
    'modelClass' => 'Admin',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Admins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('accounts', 'Update');
?>
<div class="admin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
