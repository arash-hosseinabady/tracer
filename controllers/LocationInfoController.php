<?php

namespace app\controllers;

use app\models\Device;
use app\models\Helper;
use app\models\UserDevice;
use Yii;
use app\models\LocationInfo;
use app\models\LocationInfoSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LocationInfoController implements the CRUD actions for LocationInfo model.
 */
class LocationInfoController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * Lists all LocationInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LocationInfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionTrace()
    {
        $model = new LocationInfo();
        $output = [];

        if (Yii::$app->request->isAjax) {
            $postData = Yii::$app->request->post();
            $locationInfo = $model::find()
                ->andFilterWhere(['device_id' => $postData['device']])
                ->andFilterWhere(['>=', 'speed', $postData['speed']])
                ->andFilterWhere(['>=', 'created_at', Helper::getUnixTimeFromShamsiDate($postData['fromDate'])])
                ->andFilterWhere(['<=', 'created_at', Helper::getUnixTimeFromShamsiDate($postData['toDate'])])
                ->orderBy(['id' => SORT_DESC])
                ->limit(50)
                ->all();

            if ($locationInfo) {
                foreach ($locationInfo as $key => $value) {
                    $icon = '';
                    if ($key == 0) {
                        $icon = '/img/markers/red_MarkerA.png';
                    } elseif (($key + 1) == count($locationInfo)) {
                        $icon = '/img/markers/green_MarkerA.png';
                    }
                    $output[] = [
                        'device_id' => $value->device_id,
                        'latitude' => $value->latitude,
                        'longitude' => $value->longitude,
                        'time' => $value->dateCreatedAt . ' ' . $value->timeFormatted,
                        'speed' => $value->speed,
//                        'icon' => ($value->speed > $value->device->config['speed']) ? '/img/markers/red_MarkerA.png' : '/img/markers/green_MarkerA.png',
                        'icon' => $icon,
                        'course' => $value->course,
                    ];
                }

                $output = array_reverse($output);
                $output[count($output) - 1]['address'] = '-';
                if ($address = $this->getAddressLocation($output[count($output) - 1]['latitude'], $output[count($output) - 1]['longitude'])) {
                    $output[count($output) - 1]['address'] = $address;
                }
            }
            return Json::encode($output);
        } else {
            $firstDeviceInfo = [];
            $firstDevice = UserDevice::getUserFirstDevice();
            $locationInfo = $model::find()
                ->where(['device_id' => $firstDevice])
                ->orderBy(['id' => SORT_DESC])
                ->limit(50)
                ->all();

            if ($locationInfo) {
                foreach ($locationInfo as $key => $value) {
                    $icon = '';
                    if ($key == 0) {
                        $icon = '/img/markers/red_MarkerA.png';
                    } elseif (($key + 1) == count($locationInfo)) {
                        $icon = '/img/markers/green_MarkerA.png';
                    }
                    $firstDeviceInfo[] = [
                        'device_id' => $value->device_id,
                        'latitude' => $value->latitude,
                        'longitude' => $value->longitude,
                        'time' => $value->createdAt,
                        'speed' => $value->speed,
//                        'icon' => ($value->speed > $value->device->config['speed']) ? '/img/markers/red_MarkerA.png' : '/img/markers/green_MarkerA.png',
                        'icon' => $icon,
                        'course' => $value->course,
                    ];
                }

                $firstDeviceInfo = array_reverse($firstDeviceInfo);
                $firstDeviceInfo[count($firstDeviceInfo) - 1]['address'] = '-';
                if ($address = $this->getAddressLocation($firstDeviceInfo[count($firstDeviceInfo) - 1]['latitude'], $firstDeviceInfo[count($firstDeviceInfo) - 1]['longitude'])) {
                    $firstDeviceInfo[count($firstDeviceInfo) - 1]['address'] = $address;
                }
            }
        }

        return $this->render('trace', [
            'model' => $model,
            'firstDevice' => $firstDevice,
            'firstDeviceInfo' => Json::encode($firstDeviceInfo),
            'lastLocationInfo' => count($firstDeviceInfo) ? $firstDeviceInfo[count($firstDeviceInfo) - 1] : ''
        ]);
    }

    /**
     * Creates a new LocationInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LocationInfo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LocationInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LocationInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LocationInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LocationInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LocationInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getAddressLocation($lat, $lng)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyB-q2u3nMb2RbCDvn3jni7uYHm79u9banY&language=en&latlng=$lat,$lng&sensor=false",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return false;
        } else {
            return json_decode($response)->results[0]->formatted_address;
        }
    }
}
