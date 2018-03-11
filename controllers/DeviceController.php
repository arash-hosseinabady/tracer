<?php

namespace app\controllers;

use app\models\DeviceConfig;
use app\models\UserDevice;
use Yii;
use app\models\Device;
use app\models\DeviceSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeviceController implements the CRUD actions for Device model.
 */
class DeviceController extends BaseController
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
     * Lists all Device models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Device model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Device model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Device();
        $deviceConfig = new DeviceConfig();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $modelUserDevice = new UserDevice();
            $modelUserDevice->user_id = Yii::$app->user->id;
            $modelUserDevice->device_id = $model->id;
            $modelUserDevice->save();

            $deviceConfig->device_id = $model->id;
            if ($deviceConfig->load(Yii::$app->request->post()) and $deviceConfig->save()) {
                return $this->redirect('index');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'deviceConfig' => $deviceConfig,
        ]);
    }

    /**
     * Updates an existing Device model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $deviceConfig = DeviceConfig::findOne(['device_id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($deviceConfig->load(Yii::$app->request->post()) && $deviceConfig->save()) {
                return $this->redirect('index');
            }
        }

        return $this->render('update', [
            'model' => $model,
            'deviceConfig' => $deviceConfig,
        ]);
    }

    /**
     * Deletes an existing Device model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Device model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Device the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Device::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
