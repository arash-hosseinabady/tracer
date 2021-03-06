<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Device */
/* @var $deviceConfig app\models\DeviceConfig */

$this->title = Yii::t('app', 'Create Device');
//$this->params['breadcrumbs'][] = ['label' => 'Devices', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'deviceConfig' => $deviceConfig,
    ]) ?>

</div>
