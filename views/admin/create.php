<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var cakebake\accounts\models\Admin $model
 */

$this->title = Yii::t('accounts', 'Create Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Administrating user accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-admin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
