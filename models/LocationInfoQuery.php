<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[LocationInfo]].
 *
 * @see LocationInfo
 */
class LocationInfoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return LocationInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LocationInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
