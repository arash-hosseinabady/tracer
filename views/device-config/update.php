<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DeviceConfig */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Device Config',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Device Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="device-config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
