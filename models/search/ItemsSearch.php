<?php

namespace app\models\search;

use app\models\FiltersValues;
use app\models\FiltersValuesProps;
use app\models\ItemsVarsValues;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Items;

/**
 * ItemsSearch represents the model behind the search form about `app\models\Items`.
 */
class ItemsSearch extends Items
{
    public $filtersData = [];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
                [['id'], 'integer'],
                [['name', 'alias'], 'safe'],
                [['price'], 'number'],
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
        \Yii::$app->params['filter'] = ["join" => "", "sql" => ""];

        $query = Items::find();

        $dataProvider = new ActiveDataProvider([
                'query' => $query,
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $params["filters"] = $this->filtersData;

        if(isset($params["filters"]["s"])) {
            $queryFormat = Yii::$app->functions->latToCyr($params["filters"]["s"]);

            $query->where(
                    "items.id LIKE ".\Yii::$app->db->quoteValue("%".$params["filters"]["s"]."%")."
                OR brands.name LIKE ".\Yii::$app->db->quoteValue("%".$params["filters"]["s"]."%")." OR brands.name LIKE ".\Yii::$app->db->quoteValue("%".$queryFormat."%")."
                OR items.text LIKE ".\Yii::$app->db->quoteValue("%".$params["filters"]["s"]."%")." OR items.text LIKE ".\Yii::$app->db->quoteValue("%".$queryFormat."%")."
                OR items.name LIKE ".\Yii::$app->db->quoteValue("%".$params["filters"]["s"]."%")." OR items.name LIKE ".\Yii::$app->db->quoteValue("%".$queryFormat."%")
            );

            $query->joinWith("brand");
        }

        if(isset($params["filters"]["categories"])) {
            $query->andFilterWhere(['in', 'categories.id', $params["filters"]["categories"]]);
            $query->joinWith("categories");

            \Yii::$app->params['filter']["sql"] .= " AND ci.category_id IN (" . implode(",", $params["filters"]["categories"]) . ") ";
            \Yii::$app->params['filter']["join"] .= " LEFT JOIN categoriestoitems ci ON ci.item_id = t.item_id ";
        }

        if(isset($params["filters"]["colors"])) {
            $query->andFilterWhere(['in', 'colors.id', $params["filters"]["colors"]]);
            $query->joinWith("colors");
        }

        if(isset($params["filters"]["sizes"])) {
            $query->andFilterWhere(['in', 'sizes.id', $params["filters"]["sizes"]]);
            $query->joinWith("sizes");
        }

        if(isset($params["filters"]["brands"])) {
            $query->andFilterWhere(['in', 'items.brand_id', $params["filters"]["brands"]]);

            \Yii::$app->params['filter']["sql"] .= " AND i.brand_id IN (" . implode(",", $params["filters"]["brands"]) . ") ";
        }

        if(isset($params["filters"]["countries"])) {
            $query->andFilterWhere(['in', 'brands.country_id', $params["filters"]["countries"]]);
            $query->joinWith("brand");

            \Yii::$app->params['filter']["join"] .= " LEFT JOIN brands b ON b.id = t.brand_id ";
            \Yii::$app->params['filter']["sql"] .= " AND b.country_id IN (" . implode(",", $params["filters"]["countries"]) . ") ";
        }

        if(isset($params["filters"]["statuses"])) {
            $query->andFilterWhere(['in', 'items.status_id', $params["filters"]["statuses"]]);

            \Yii::$app->params['filter']["sql"] .= " AND i.status_id IN (" . implode(",", $params["filters"]["statuses"]) . ") ";
        }

        if(isset($params["filters"]["between_price"]) && $params["filters"]["between_price"] != "")
        {
            $prices = array_filter(explode(",", $params["filters"]["between_price"]));
            if(count($prices) == 2) {
                $params["filters"]["prices"]["from"] = $prices[0];
                $params["filters"]["prices"]["to"] = $prices[1];
            }
        }

        if(isset($params["filters"]["prices"]))
        {
            if (isset($params["filters"]["prices"]['from'], $params["filters"]["prices"]['to']) && $params["filters"]["prices"]['from'] >= 0 && $params["filters"]["prices"]['to'] > 0)
            {
                $query->andFilterWhere(['between', 'items._system_dynamic_price', $params["filters"]["prices"]['from'], $params["filters"]["prices"]['to']]);

                \Yii::$app->params['filter']["sql"] .= " AND (i._system_dynamic_price BETWEEN '".$params["filters"]["prices"]['from']."' AND '".$params["filters"]["prices"]['to']."') ";
            }
        }

        $propsJoins = [];

        if(isset($params["filters"]["props"])) {
            $sqlProps = "";

            foreach($params["filters"]["props"] as $filter_id => $values) {
                $sqlProps .= " AND (";
                foreach($values as $filter_value_id){
                    if(is_array($filter_value_id) && (isset($filter_value_id["from"]) || isset($filter_value_id["to"]))) {
                        $values_props = FiltersValuesProps::find()->where(["filter_value_id" => key($values)])->all();
                        if($values_props) {
                            foreach($values_props as $value_prop){
                                if(!isset($propsJoins[$value_prop->prop_id])) $query->joinWith("props as p".$value_prop->prop_id);
                                $propsJoins[$value_prop->prop_id] = $value_prop->prop_id;
                            }

                            foreach($values_props as $value_prop) {
                                $sqlProps .= "`p".$value_prop->prop_id."`.`prop_id` = '" . $value_prop->prop_id . "' AND ";
                                $sqlProps .= "CAST(`p".$value_prop->prop_id."`.`value` AS DECIMAL(12,2)) between '" . $filter_value_id["from"] . "' AND '" . $filter_value_id["to"] . "'";
                            }
                        }
                    }
                    else {
                        $values_props = FiltersValuesProps::find()->where(["filter_value_id" => $filter_value_id])->all();

                        if($values_props) {
                            foreach($values_props as $value_prop){
                                if(!isset($propsJoins[$value_prop->prop_id])) $query->joinWith("props as p".$value_prop->prop_id);
                                $propsJoins[$value_prop->prop_id] = $value_prop->prop_id;
                            }

                            foreach($values_props as $value_prop){
                                $sqlProps .= "(`p".$value_prop->prop_id."`.`prop_id` = '".$value_prop->prop_id."' AND ";
                                if($value_prop->to_value != "") {
                                    $sqlProps .= "CAST(`p".$value_prop->prop_id."`.`value` AS DECIMAL(12,2)) between ".Yii::$app->db->quoteValue($value_prop->from_value)." AND ".Yii::$app->db->quoteValue($value_prop->to_value);
                                }
                                else {
                                    $sqlProps .= "`p".$value_prop->prop_id."`.`value` LIKE ".Yii::$app->db->quoteValue($value_prop->from_value);
                                }
                                $sqlProps .= ") OR ";
                            }
                        }
                    }
                }

                $sqlProps = trim(trim($sqlProps), "OR").")";
            }

            \Yii::$app->params['filter']["sql"] .= $sqlProps;

            $sqlProps = trim(trim($sqlProps), "AND");

            $query->andWhere($sqlProps);
        }

        if(isset($params["filters"]["props_id"])) {
            $sqlProps = "";

            foreach($params["filters"]["props_id"] as $prop_id => $values) {
                if(!isset($propsJoins[$prop_id])) $query->joinWith("props as p".$prop_id);
                $propsJoins[$prop_id] = $prop_id;

                $sqlProps .= " AND ( `p".$prop_id."`.`prop_id` = '".$prop_id."' AND (";
                foreach ($values as $value) {
                    $sqlProps .= " OR `p".$prop_id."`.`value` = ".Yii::$app->db->quoteValue($value)." ";
                }

                $sqlProps = str_replace('( OR', '(', $sqlProps);
                $sqlProps .= ") ) ";
            }

            \Yii::$app->params['filter']["sql"] .= $sqlProps;

            $query->andWhere(trim(trim($sqlProps), "AND"));
        }

        if(count($propsJoins)) foreach ($propsJoins as $prop_id) {
            \Yii::$app->params['filter']["join"] .= " LEFT JOIN items_props as p".$prop_id." ON p".$prop_id.".item_id = i.id ";
        }

        if(isset($params["filters"]["vars"])) {

            $sqlVars = "";
            foreach($params["filters"]["vars"] as $filter_id => $values) {
                foreach($values as $value){
                    $filtervalue = FiltersValues::findOne($value);
                    if($filtervalue) {
                        $sqlVars .= " (`items_vars`.`showtype_id` = '".$filtervalue->var_showtype_id."' AND `items_vars_values`.`var_value_id` = '".$filtervalue->var_showtype_value_id."') OR";
                    }
                }
            }

            $query->andWhere(trim(trim($sqlVars), "OR"));

            $query->joinWith(["vars", "vars.values"]);
        }

        if(isset($params["filters"]["day_item"])) {
            $query->andFilterWhere(['=', 'items.day_item', $params["filters"]["day_item"]]);

            \Yii::$app->params['filter']["sql"] .= " AND i.day_item = '".$params["filters"]["day_item"]."' ";
        }

        if(isset($params["filters"]["special"])) {
            $query->andFilterWhere(['=', 'items.special', $params["filters"]["special"]]);

            \Yii::$app->params['filter']["sql"] .= " AND i.special = '".$params["filters"]["special"]."' ";
        }

        $query->andFilterWhere(['=', 'items.parent', 0]);

        if(isset($params["filters"]["best"])) {
            $query->andFilterWhere(['=', 'items.best', $params["filters"]["best"]]);

            \Yii::$app->params['filter']["sql"] .= " AND i.best = '".$params["filters"]["best"]."' ";
        }

        $query->andFilterWhere(['like', 'items.id', $this->id]);
        $query->andFilterWhere(['like', 'items.name', $this->name]);
        $query->andFilterWhere(['like', 'items.alias', $this->alias]);
        $query->andFilterWhere(['like', 'items.price', $this->price]);
        $query->andFilterWhere(['=', 'items.special', $this->special]);
        $query->andFilterWhere(['=', 'items.vis', $this->vis]);

        if(!isset($params["sort"])) $query->orderBy('items.posled');
        //else $query->orderBy('items.'.$params["sort"]);

        $query->groupBy('items.id');

        return $dataProvider;
    }
}
