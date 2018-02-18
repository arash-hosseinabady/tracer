<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LocationInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="location-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'time')->textInput() ?>

    <?= $form->field($model, 'device_id')->textInput() ?>

    <?= $form->field($model, 'latitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'longitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'speed')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'course')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'battery_voltage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'door')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shock_sensor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'motor')->textInput() ?>

    <?= $form->field($model, 'command1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'command2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
