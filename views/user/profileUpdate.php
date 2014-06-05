<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title =  Yii::t('accounts', 'Update Profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', '{nicename}´s Profile', ['nicename' => Yii::$app->user->getNicename()]), 'url' => ['profile']];
$this->params['breadcrumbs'][] = Yii::t('accounts', 'Update');

?>
<div class="account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
