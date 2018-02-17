<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "location_info".
 *
 * @property int $id
 * @property int $time
 * @property int $device_id
 * @property int $latitude
 * @property int $longitude
 * @property int $speed
 * @property int $course
 * @property int $command1
 * @property int $command2
 * @property int $created_at
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
            ['device_id', 'required'],
            [['time', 'device_id', 'created_at'], 'integer'],
            [['latitude', 'longitude', 'speed', 'course', 'command1', 'command2'], 'string'],
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
        return Yii::$app->jdate->date('Y-m-d H:i:s', $this->time);
    }
}
