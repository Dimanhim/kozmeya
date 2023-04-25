<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Reviews;

/**
 * ReviewsSearch represents the model behind the search form about `app\models\Reviews`.
 */
class ReviewsSearch extends Reviews
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vis', 'posled', 'main'], 'integer'],
            [['date', 'name', 'post', 'text', 'images', 'video'], 'safe'],
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
        $query = Reviews::find();

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
            'vis' => $this->vis,
            'posled' => $this->posled,
            'main' => $this->main,
        ]);

        if(isset($params["hasvideo"]) && $params["hasvideo"] == 1){
            $query->andWhere("video != ''");
        }

        if(isset($params["hasvideo"]) && $params["hasvideo"] == 0){
            $query->andWhere("video = ''");
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'post', $this->post])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'images', $this->images])
            ->andFilterWhere(['like', 'video', $this->video]);

        if(!isset($params["sort"])) $query->orderBy('date DESC');

        return $dataProvider;
    }
}