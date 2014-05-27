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
            [['username', 'email'], 'string'],
            [['username', 'email'], 'filter', 'filter' => 'trim'],
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
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'role' => $this->role,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
