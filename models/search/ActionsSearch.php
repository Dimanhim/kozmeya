<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Actions;

/**
 * NewsSearch represents the model behind the search form about `app\models\News`.
 */
class ActionsSearch extends Actions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'count_view', 'vis', 'posled'], 'integer'],
            [['name', 'alias', 'small', 'text', 'date', 'images'], 'safe'],
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
        $query = Actions::find();

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
            'date' => $this->date,
            'count_view' => $this->count_view,
            'vis' => $this->vis,
            'posled' => $this->posled,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'small', $this->small])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'images', $this->images]);

        if(!isset($params["sort"])) $query->orderBy('date DESC');

        return $dataProvider;
    }
}
