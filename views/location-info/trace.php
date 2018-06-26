<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\pdp\PersianDatePicker;
use yii\web\View;
use kartik\spinner\Spinner;

/* @var $this yii\web\View */
/* @var $model app\models\LocationInfo */

$this->registerCssFile('/css/mapbox-gl.css', ['position' => View::POS_END]);
$this->registerJsFile('/js/mapbox-gl.js', ['position' => View::POS_HEAD]);
?>

<div class="location-info-view">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>

    <div class="row">
        <?= $form->field($model, 'device_id', ['options' => ['class' => 'col-lg-3']])->widget(Select2::className(), [
            'data' => \app\models\Device::getUserDevice(),
            'options' => [
                'placeholder' => Yii::t('app', 'Select Device'),
                'value' => $firstDevice
            ]
        ])->label(false) ?>

        <?= $form->field($model, 'from_date', ['options' => ['class' => 'col-lg-2']])->widget(
            PersianDatePicker::className(), [
            'options' => [
                'placeholder' => Yii::t('app', 'From Date'),
            ]
        ])->label(false) ?>

        <?= $form->field($model, 'to_date', ['options' => ['class' => 'col-lg-2']])->widget(
            PersianDatePicker::className(), [
            'options' => [
                'placeholder' => Yii::t('app', 'To Date'),
            ]
        ])->label(false) ?>

        <?= $form->field($model, 'speed', ['options' => ['class' => 'col-lg-2']])->textInput([
            'type' => 'number',
            'min' => '0',
            'placeholder' => Yii::t('app', 'Speed'),
        ])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary', 'id' => 'search']) ?>
        </div>
    </div>

    <div class="alert alert-danger" id="device-selection"
         style="display: none;"><?= Yii::t('app', 'Please select a device!') ?></div>

    <div class="alert alert-danger" id="alert-nodata"
         style="display: none;"><?= Yii::t('app', 'No Data!') ?></div>

    <?php ActiveForm::end(); ?>

    <div class="row">
        <div class="col-lg-3">
            <div class="panel panel-info panel-last-location"
                 style="display: <?= isset($lastLocationInfo['speed']) ? 'block' : 'none' ?>">
                <div class="panel-heading"><?= Yii::t('app', 'Last location info') ?></div>
                <div class="panel-body">
                    <p><?= Yii::t('app', 'Speed') ?> : <span
                                id="last-location-speed"><?= isset($lastLocationInfo['speed']) ? $lastLocationInfo['speed'] : '' ?></span>
                    </p>
                    <p><?= Yii::t('app', 'Course') ?> : <span
                                id="last-location-course"><?= isset($lastLocationInfo['course']) ? $lastLocationInfo['course'] : '' ?></span>
                    </p>
                    <p><?= Yii::t('app', 'Time') ?> : <span
                                id="last-location-time"><?= isset($lastLocationInfo['time']) ? $lastLocationInfo['time'] : '' ?></span>
                    </p>
                    <p><?= Yii::t('app', 'Address') ?> : <span
                                id="last-location-address"><?= isset($lastLocationInfo['address']) ? $lastLocationInfo['address'] : '' ?></span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div id="map_wrapper" style="height: 600px;">
                <div id="load-spin" class="well col-lg-12" style="height: 100%; display: none;direction: ltr">
                    <div class="col-lg-12" style="margin-top: 250px;"></div>
                    <?= Spinner::widget([
                        'preset' => Spinner::LARGE,
                        'color' => 'blue',
                        'align' => 'center',
                        'caption' => 'Loading map &hellip;',
                    ]) ?>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$css = <<< CSS
#map_canvas {
    width: 100%;
    height: 100%;
}
CSS;
//$this->registerCss($css);
?>

<?php
$js = <<< JS
        var firstDevice = JSON.parse('$firstDeviceInfo');

JS;
$this->registerJs($js, View::POS_HEAD);
$this->registerJsFile('/js/trace.js', ['position' => View::POS_END, 'depends' => 'yii\web\JqueryAsset']);
?>