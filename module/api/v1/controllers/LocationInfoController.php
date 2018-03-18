<?php
/**
 * Created by PhpStorm.
 * User: Arash
 * Date: 1/28/2018
 * Time: 8:17 PM
 */

namespace app\module\api\v1\controllers;

use app\models\LocationInfo;
use app\models\Device;
use app\models\Log;
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

    public function actions()
    {
//        print_r(Yii::$app->request);die;
//        $actions = parent::actions();

        $actions['send-location'] = [
            'class' => 'yii\rest\CreateAction',
            'modelClass' => $this->modelClass,
            'checkAccess' => [$this, 'checkAccess'],
            'scenario' => $this->createScenario,
        ];

        return $actions;
    }

    public function actionInsert()
    {
        $logModel = new Log();
        $logModel->request = json_encode( Yii::$app->request->post());
        $logModel->save();
        if (Yii::$app->request->post('data')) {
            $postData = explode(',', Yii::$app->request->post('data'));

            if ($postData[3] == 'A') {
                $model = new LocationInfo();
                $model->device_id = Device::findOne(['serial' => $postData[0]])->id;
                $model->time = intval($postData[2]);
                $model->latitude = (string)(substr($postData[4], 0, 2) + (substr($postData[4], 2) / 60));
                $model->longitude = (string)(substr($postData[6], 0, 3) + (substr($postData[6], 3) / 60));
                $model->speed = (string)($postData[8] * 1.85);
                $model->course = $postData[9];
                if ($model->save()) {
                    return 'valid';
                }
                return $model->errors;
            }
        }

        return 'request invalid!';
    }

}