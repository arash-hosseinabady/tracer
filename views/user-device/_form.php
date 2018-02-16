<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\UserDevice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-device-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id', ['options' => ['class' => 'col-lg-3']])->widget(Select2::className(), [
        'data' => \webvimark\modules\UserManagement\models\User::getList(),
        'options' => [
            'placeholder' => Yii::t('app', 'Select User'),
        ]
    ]) ?>

    <?= $form->field($model, 'device_id', ['options' => ['class' => 'col-lg-3']])->widget(Select2::className(), [
        'data' => \app\models\Device::getList(),
        'options' => [
            'placeholder' => Yii::t('app', 'Select Device'),
        ]
    ]) ?>

    <div class="form-group col-lg-12">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
