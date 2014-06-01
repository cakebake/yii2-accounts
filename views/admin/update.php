<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var cakebake\accounts\models\Admin $model
 */

$this->title = $model->getNicename();
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Manage accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getNicename(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('accounts', 'Update Account');
?>
<div class="accounts-admin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
