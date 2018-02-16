<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Device */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="device-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'serial', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sim_number', ['options' => ['class' => 'col-lg-3']])->textInput(['maxlength' => true]) ?>

    <div class="form-group col-lg-12">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
