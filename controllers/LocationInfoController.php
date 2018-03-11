<?php

namespace app\controllers;

use Yii;
use app\models\LocationInfo;
use app\models\LocationInfoSearch;
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
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
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

        if (Yii::$app->request->isAjax) {
            $postData = Yii::$app->request->post();
            $output = [];
            $locationInfo = $model::findAll(['device_id' => $postData['device']]);
            if ($locationInfo) {
                foreach ($locationInfo as $value) {
                    $output[] = [
                        'device_id' => $value->device_id,
                        'latitude' => $value->latitude,
                        'longitude' => $value->longitude,
                        'time' => $value->timeFormatted,
                        'speed' => $value->speed,
                        'icon' => ($value->speed > $value->device->config['speed']) ? '/img/markers/red_MarkerA.png' : '/img/markers/green_MarkerA.png',
                        'course' => $value->course,
                    ];
                }
            }
            return Json::encode($output);
        }

        return $this->render('trace', [
            'model' => $model,
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
}
