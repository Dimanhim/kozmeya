<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Faces;

/**
 * FacesSearch represents the model behind the search form about `app\models\Faces`.
 */
class FacesSearch extends Faces
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vis', 'posled'], 'integer'],
            [['name', 'post', 'vk', 'fb', 'phone', 'email', 'images', 'text'], 'safe'],
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
        $query = Faces::find();

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
            'vis' => $this->vis,
            'posled' => $this->posled,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'post', $this->post])
            ->andFilterWhere(['like', 'vk', $this->vk])
            ->andFilterWhere(['like', 'fb', $this->fb])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'images', $this->images])
            ->andFilterWhere(['like', 'text', $this->text]);

        if(!isset($params["sort"])) $query->orderBy('posled');

        return $dataProvider;
    }
}