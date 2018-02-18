<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LocationInfo */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Location Info',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Location Infos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="location-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
