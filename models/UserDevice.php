<?php

namespace app\models;

use webvimark\modules\UserManagement\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_device".
 *
 * @property int $id
 * @property int $user_id
 * @property int $device_id
 * @property int $created_at
 */
class UserDevice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'device_id', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User'),
            'device_id' => Yii::t('app', 'Device'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }

    public function getCreatedAt()
    {
        return Yii::$app->jdate->date('H:i Y-m-d', $this->created_at);
    }

    static function getUserFirstDevice()
    {
        $firstUserDevice = self::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['id' => SORT_ASC])
            ->limit(1)
            ->one();


        return $firstUserDevice['device_id'];
    }
}
