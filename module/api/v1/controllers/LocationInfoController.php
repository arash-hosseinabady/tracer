<?php
/**
 * Created by PhpStorm.
 * User: Arash
 * Date: 1/28/2018
 * Time: 8:17 PM
 */

namespace app\module\api\v1\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Response;

class LocationInfoController  extends ActiveController
{
    public $modelClass = 'app\models\LocationInfo';

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ]);
    }

//    public function actions()
//    {
//        $actions = parent::actions();
//
//        $actions['send-location'] = [
//            'class' => 'yii\rest\CreateAction',
//            'modelClass' => $this->modelClass,
//            'checkAccess' => [$this, 'checkAccess'],
//            'scenario' => $this->createScenario,
//        ];
//
//        return $actions;
//    }

}