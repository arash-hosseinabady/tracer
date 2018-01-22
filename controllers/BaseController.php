<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * Description of BaseController
 *
 * @author hatef
 */
class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
        ];
    }
}
