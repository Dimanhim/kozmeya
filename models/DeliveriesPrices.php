<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "deliveries_prices".
 *
 * @property integer $id
 * @property integer $delivery_id
 * @property double $price
 * @property string $condition
 *
 * @property Deliveries $delivery
 */
class DeliveriesPrices extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deliveries_prices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_id', 'price', 'condition', 'cart_price'], 'required'],
            [['delivery_id'], 'integer'],
            [['price'], 'number'],
            [['condition'], 'string', 'max' => 255],
            [['delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deliveries::className(), 'targetAttribute' => ['delivery_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_id' => 'Delivery ID',
            'price' => 'Price',
            'condition' => 'Condition',
        ];
    }

    public function updateRelations(){}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Deliveries::className(), ['id' => 'delivery_id']);
    }
}
