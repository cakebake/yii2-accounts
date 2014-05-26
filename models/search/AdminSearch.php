<?php

namespace cakebake\accounts\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cakebake\accounts\models\Admin;

/**
 * AdminSearch represents the model behind the search form about `cakebake\accounts\models\Admin`.
 */
class AdminSearch extends Admin
{
    public function rules()
    {
        return [
            [['id', 'role', 'status'], 'integer'],
            [['username', 'email', 'auth_key', 'password_hash', 'password_reset_token', 'updated_at', 'created_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Admin::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'role' => $this->role,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token]);

        return $dataProvider;
    }
}
