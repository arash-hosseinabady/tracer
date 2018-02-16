<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserDeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'User Devices');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-device-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'user_id',
                'value' => 'user.username',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'name' => 'UserDeviceSearch[user_id]',
                    'attribute' => 'user_id',
                    'data' => \webvimark\modules\UserManagement\models\User::getList(),
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    'options' => [
                        'placeholder' => Yii::t('app', 'Select User'),
                    ]
                ])
            ],
            [
                'attribute' => 'device_id',
                'value' => 'device.name',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'name' => 'UserDeviceSearch[device_id]',
                    'attribute' => 'device_id',
                    'data' => \app\models\Device::getList(),
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                    'options' => [
                        'placeholder' => Yii::t('app', 'Select Device'),
                    ]
                ])
            ],
            [
                'attribute' => 'created_at',
                'value' => 'createdAt',
                'filter' => false,
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
</div>
