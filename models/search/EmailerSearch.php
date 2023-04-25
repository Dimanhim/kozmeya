<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Emailer;

/**
 * NewsSearch represents the model behind the search form about `app\models\News`.
 */
class EmailerSearch extends Emailer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['subject', 'text', 'emails', 'date', 'last_send'], 'safe'],
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
        $query = Emailer::find();

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
            'last_send' => $this->last_send,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'last_send', $this->last_send])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'status', $this->status]);

        if(!isset($params["sort"])) $query->orderBy('last_send DESC');

        return $dataProvider;
    }
}
