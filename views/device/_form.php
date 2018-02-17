<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Device */
/* @var $deviceConfig app\models\DeviceConfig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="device-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?= $form->field($model, 'name', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'serial', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sim_number', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => true]) ?>
    </div>

    <div class="row">
        <?= $form->field($deviceConfig, 'speed', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => true]) ?>
    </div>

    <div class="form-group col-lg-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
