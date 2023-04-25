<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders_items_vars".
 *
 * @property integer $order_item_id
 * @property integer $var_id
 *
 * @property ItemsVarsValues $var
 * @property OrdersItems $orderItem
 */
class OrdersItemsVars extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_items_vars';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_item_id', 'var_id'], 'required'],
            [['order_item_id', 'var_id'], 'integer'],
            [['var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemsVarsValues::className(), 'targetAttribute' => ['var_id' => 'id']],
            [['order_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdersItems::className(), 'targetAttribute' => ['order_item_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_item_id' => 'Order Item ID',
            'var_id' => 'Var ID',
        ];
    }

    public function updateRelations(){}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVar()
    {
        return $this->hasOne(ItemsVarsValues::className(), ['id' => 'var_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem()
    {
        return $this->hasOne(OrdersItems::className(), ['id' => 'order_item_id']);
    }
}
