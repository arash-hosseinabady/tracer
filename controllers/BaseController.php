<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;

/**
 * Description of BaseController
 *
 * @author hatef
 */
class BaseController extends Controller
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user-management/auth/login');
        }
    }

    public function behaviors()
    {
        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
        ];
    }
}
