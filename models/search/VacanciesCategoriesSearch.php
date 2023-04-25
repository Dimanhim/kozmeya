<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VacanciesCategories;

/**
 * FacesSearch represents the model behind the search form about `app\models\Faces`.
 */
class VacanciesCategoriesSearch extends VacanciesCategories
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'vis', 'posled'], 'integer'],
            [['name'], 'safe'],
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
        $query = VacanciesCategories::find();

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

        $query->andFilterWhere(['like', 'name', $this->name]);

        if(!isset($params["sort"])) $query->orderBy('posled');

        return $dataProvider;
    }
}