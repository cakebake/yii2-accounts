<?php

use yii\helpers\Html;

$this->title = Yii::t('accounts', 'Create Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'profileData' => $profileData,
    ]) ?>
</div>
