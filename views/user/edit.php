<?php

use yii\helpers\Html;

$this->title = Yii::t('accounts', 'Edit {nicename}´s Account', ['nicename' => $model->getNicename()]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', '{nicename}´s Profile', ['nicename' => $model->getNicename()]), 'url' => ['profile', 'u' => $model->username]];
$this->params['breadcrumbs'][] = Yii::t('accounts', 'Edit Account');
?>

<div class="accounts-edit">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'profileModel' => $profileModel,
    ]) ?>
</div>
