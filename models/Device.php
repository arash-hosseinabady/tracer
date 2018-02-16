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
}
