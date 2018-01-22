<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'superadmin', 'created_at', 'updated_at', 'email_confirmed'], 'integer'],
            [['name', 'phone', 'username', 'auth_key', 'password_hash', 'confirmation_token', 'registration_ip', 'bind_to_ip', 'email'], 'safe'],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'superadmin' => $this->superadmin,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'email_confirmed' => $this->email_confirmed,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'confirmation_token', $this->confirmation_token])
            ->andFilterWhere(['like', 'registration_ip', $this->registration_ip])
            ->andFilterWhere(['like', 'bind_to_ip', $this->bind_to_ip])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
