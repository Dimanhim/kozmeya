<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Orders;

/**
 * FacesSearch represents the model behind the search form about `app\models\Faces`.
 */
class OrdersSearch extends Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'deleted'], 'integer'],
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
        $query = Orders::find();

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

        if(isset($params["filters"]["s"])) {
            $query->where(
                "orders.id LIKE ".\Yii::$app->db->quoteValue("%".$params["filters"]["s"]."%")."
                OR orders.phone LIKE ".\Yii::$app->db->quoteValue("%".$params["filters"]["s"]."%")."
                OR orders.email LIKE ".\Yii::$app->db->quoteValue("%".$params["filters"]["s"]."%")."
                OR orders.name LIKE ".\Yii::$app->db->quoteValue("%".$params["filters"]["s"]."%")
            );
        }

        if(isset($params["filters"]["date"])) {
            $date = explode("/", $params["filters"]["date"]);
            if(count($date) > 1) {
                $query->andFilterWhere(['between', 'date', date("Y-m-d", strtotime($date[0])), date("Y-m-d", strtotime($date[1]))]);
            }
            else {
                $query->andFilterWhere(['LIKE', 'orders.date', date("Y-m-d", strtotime($params["filters"]["date"]))]);
            }
        }

        if(isset($params["filters"]["statuses"])) {
            $query->andFilterWhere(['in', 'orders.status_id', $params["filters"]["statuses"]]);
        }

        if(isset($params["filters"]["deliveries"])) {
            $query->andFilterWhere(['in', 'orders.delivery_id', $params["filters"]["deliveries"]]);
        }

        if(isset($params["filters"]["payments"])) {
            $query->andFilterWhere(['in', 'orders.payment_id', $params["filters"]["payments"]]);
        }

        if(isset($params["filters"]["deleted"])) {
            $query->andFilterWhere(['=', 'orders.deleted', $params["filters"]["deleted"]]);
        }
        else {
            $query->andFilterWhere(['=', 'orders.deleted', 0]);
        }


        if(!isset($params["sort"])) $query->orderBy('orders.date DESC');

        return $dataProvider;
    }
}