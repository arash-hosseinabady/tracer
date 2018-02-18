<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LocationInfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="location-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'time') ?>

    <?= $form->field($model, 'device_id') ?>

    <?= $form->field($model, 'latitude') ?>

    <?= $form->field($model, 'longitude') ?>

    <?php // echo $form->field($model, 'speed') ?>

    <?php // echo $form->field($model, 'course') ?>

    <?php // echo $form->field($model, 'battery_voltage') ?>

    <?php // echo $form->field($model, 'door') ?>

    <?php // echo $form->field($model, 'shock_sensor') ?>

    <?php // echo $form->field($model, 'motor') ?>

    <?php // echo $form->field($model, 'command1') ?>

    <?php // echo $form->field($model, 'command2') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
