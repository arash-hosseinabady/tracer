<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "device".
 *
 * @property int $id
 * @property string $name
 * @property string $serial
 * @property string $sim_number
 *
 * @property DeviceConfig $config
 */
class Device extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'serial', 'sim_number'], 'string', 'max' => 32],
            ['serial', 'unique'],
            ['sim_number', 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'serial' => Yii::t('app', 'Serial'),
            'sim_number' => Yii::t('app', 'Sim Number'),
        ];
    }

    static function getList()
    {
        $list = self::find()
            ->asArray()
            ->all();

        return ArrayHelper::map($list, 'id', 'name');
    }

    static function getUserDevice()
    {
        $list = self::find()
            ->innerJoin(UserDevice::tableName(), 'device.id = user_device.device_id')
            ->where(['user_device.user_id' => Yii::$app->user->id])
            ->select(['device.id', 'device.name'])
            ->asArray()
            ->all();

        return ArrayHelper::map($list, 'id', 'name');
    }

    public function getConfig()
    {
        return $this->hasOne(DeviceConfig::className(), ['device_id' => 'id']);
    }

    public function afterDelete()
    {
        DeviceConfig::deleteAll(['device_id' => $this->id]);
        parent::afterDelete();
    }
}
