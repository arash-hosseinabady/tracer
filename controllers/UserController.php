<?php
/**
 * Created by PhpStorm.
 * User: Arash
 * Date: 1/28/2018
 * Time: 8:58 PM
 */

namespace app\controllers;


use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'webvimark\modules\UserManagement\models\User';
}