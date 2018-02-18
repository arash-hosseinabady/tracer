<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LocationInfo;

/**
 * LocationInfoSearch represents the model behind the search form about `app\models\LocationInfo`.
 */
class LocationInfoSearch extends LocationInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'time', 'device_id', 'motor', 'created_at'], 'integer'],
            [['latitude', 'longitude', 'speed', 'course', 'battery_voltage', 'door', 'shock_sensor', 'command1', 'command2'], 'safe'],
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
        $query = LocationInfo::find();

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
            'time' => $this->time,
            'device_id' => $this->device_id,
            'motor' => $this->motor,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'longitude', $this->longitude])
            ->andFilterWhere(['like', 'speed', $this->speed])
            ->andFilterWhere(['like', 'course', $this->course])
            ->andFilterWhere(['like', 'battery_voltage', $this->battery_voltage])
            ->andFilterWhere(['like', 'door', $this->door])
            ->andFilterWhere(['like', 'shock_sensor', $this->shock_sensor])
            ->andFilterWhere(['like', 'command1', $this->command1])
            ->andFilterWhere(['like', 'command2', $this->command2]);

        return $dataProvider;
    }
}
