<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var cakebake\accounts\models\Admin $model
 */

$this->title = Yii::t('accounts', 'Create {modelClass}', [
    'modelClass' => 'Admin',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('accounts', 'Admins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
