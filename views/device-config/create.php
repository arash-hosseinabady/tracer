<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DeviceConfig */

$this->title = Yii::t('app', 'Create Device Config');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Device Configs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
