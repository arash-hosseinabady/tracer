<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "device_config".
 *
 * @property integer $id
 * @property integer $device_id
 * @property string $speed
 *
 * @property Device $device
 */
class DeviceConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id'], 'integer'],
            [['speed'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'device_id' => Yii::t('app', 'Device ID'),
            'speed' => Yii::t('app', 'Speed'),
        ];
    }

    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }
}
