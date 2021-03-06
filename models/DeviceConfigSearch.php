<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DeviceConfig;

/**
 * DeviceConfigSearch represents the model behind the search form about `app\models\DeviceConfig`.
 */
class DeviceConfigSearch extends DeviceConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'device_id'], 'integer'],
            [['speed'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DeviceConfig::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'device_id' => $this->device_id,
        ]);

        $query->andFilterWhere(['like', 'speed', $this->speed]);

        return $dataProvider;
    }
}
