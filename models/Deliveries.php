<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deliveries".
 *
 * @property integer $id
 * @property string $name
 * @property integer $price
 * @property integer $vis
 * @property integer $posled
 *
 * @property Orders[] $orders
 */
class Deliveries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deliveries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price'], 'required'],
            [['price', 'vis', 'posled'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'rowChecker', 'value' => $model->id];
            }],

            'id',
            'name',

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'price' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
            'prices' => ['type' => 'lines', "field" => "prices", "data" => [
                "object" => "prices", "fields" => [
                    ["type" => "hidden", "name" => "id", "placeholder" => ""],
                    ["type" => "text", "name" => "cart_price", "placeholder" => "Общая стоимость"],
                    ["type" => "select", "name" => "condition", "placeholder" => "Условие", "values" => [">" => ">", "<" => "<", ">=" => ">=", "<=" => "<="]],

                    ["type" => "text", "name" => "price", "placeholder" => "Стоимость доставки"],
                ]
            ]],
            'payments' => ['type' => 'checkboxListExtended', 'data' => ['values' => yii\helpers\ArrayHelper::map(\app\models\Payments::find()->orderBy("name DESC")->all(), 'id', 'name')]]
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $data = Yii::$app->request->post();

    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $data = Yii::$app->request->post();

            return true;
        }
        return false;
    }

    public function updateRelations(){
        $data = Yii::$app->request->post();

        \Yii::$app->db->createCommand()->delete('deliveries_prices', ['delivery_id' => $this->id,])->execute();

        if(isset($data["prices"]["price"])) {
            foreach ($data["prices"]["price"] as $index => $price) {
                if ($price != "") {
                    $prices = new DeliveriesPrices();
                    $prices->delivery_id = $this->id;
                    $prices->price = $price;
                    $prices->condition = (isset($data["prices"]["condition"][$index]) && $data["prices"]["condition"][$index] != "" ? $data["prices"]["condition"][$index] : ">");
                    $prices->cart_price = (isset($data["prices"]["cart_price"][$index]) && $data["prices"]["cart_price"][$index] != "" ? $data["prices"]["cart_price"][$index] : 0);
                    $prices->save();
                }
            }
        }

        \Yii::$app->db->createCommand()->delete('deliveriespayments', ['delivery_id' => $this->id,])->execute();

        if(isset($data["payments"])) {
            foreach ($data["payments"] as $payment_id) {
                if ($payment_id != "") {
                    $deliveriespayments = new DeliveriesPayments();
                    $deliveriespayments->delivery_id = $this->id;
                    $deliveriespayments->payment_id = $payment_id;
                    $deliveriespayments->save();
                }
            }
        }

        \Yii::$app->langs->saveTranslates($this);
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'price' => 'Цена',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
            'prices' => 'Условия',
            'payments' => 'Доступные методы оплаты',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payments::className(), ['id' => 'payment_id'])->viaTable('deliveriespayments', ['delivery_id' => 'id']);
    }

    public function getPrices()
    {
        return $this->hasMany(DeliveriesPrices::className(), ['delivery_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['delivery_id' => 'id']);
    }
}
