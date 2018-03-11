<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "location_info".
 *
 * @property integer $id
 * @property integer $time
 * @property integer $device_id
 * @property string $latitude
 * @property string $longitude
 * @property string $speed
 * @property string $course
 * @property string $battery_voltage
 * @property string $door
 * @property string $shock_sensor
 * @property integer $motor
 * @property string $command1
 * @property string $command2
 * @property integer $created_at
 *
 * @property Device $device
 * @property $createdAt
 * @property $timeFormatted
 */
class LocationInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time', 'device_id', 'motor', 'created_at'], 'integer'],
            [['latitude', 'longitude', 'speed', 'course', 'battery_voltage', 'door', 'shock_sensor', 'command1', 'command2'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'time' => Yii::t('app', 'Time'),
            'device_id' => Yii::t('app', 'Device'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'speed' => Yii::t('app', 'Speed'),
            'course' => Yii::t('app', 'Course'),
            'battery_voltage' => Yii::t('app', 'Battery Voltage'),
            'door' => Yii::t('app', 'Door'),
            'shock_sensor' => Yii::t('app', 'Shock Sensor'),
            'motor' => Yii::t('app', 'Motor'),
            'command1' => Yii::t('app', 'Command1'),
            'command2' => Yii::t('app', 'Command2'),
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

    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }

    public function getCreatedAt()
    {
        return Yii::$app->jdate->date('Y-m-d H:i:s', $this->created_at);
    }

    public function getTimeFormatted()
    {
        if (strlen($this->time) == 5) {
            return substr($this->time, 0, 1) . ':' . substr($this->time, 1, 2) . ':' . substr($this->time, 3, 2);
        }
        return substr($this->time, 0, 2) . ':' . substr($this->time, 2, 2) . ':' . substr($this->time, 4, 2);
    }
}
