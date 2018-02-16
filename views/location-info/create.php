<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LocationInfo */

$this->title = Yii::t('app', 'Create Location Info');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Location Infos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
