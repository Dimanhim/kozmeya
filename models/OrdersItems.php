<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders_items".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $item_id
 * @property string $name
 * @property double $price
 * @property integer $qty
 * @property string $item_params_json
 * @property string $var_params_json
 *
 * @property Orders $order
 * @property Items $item
 * @property OrdersItemsVars[] $ordersItemsVars
 * @property ItemsVarsValues[] $vars
 */
class OrdersItems extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'item_id'], 'required'],
            [['order_id', 'item_id', 'qty'], 'integer'],
            [['price'], 'number'],
            [['item_params_json', 'var_params_json', 'color', 'size'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Items::className(), 'targetAttribute' => ['item_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Заказ',
            'item_id' => 'Товар',
            'name' => 'Наименование',
            'price' => 'Цена',
            'qty' => 'Кол-во',
            'item_params_json' => 'Item Params Json',
            'var_params_json' => 'Var Params Json',
        ];
    }

    public function updateRelations(){}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Items::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersItemsVars()
    {
        return $this->hasMany(OrdersItemsVars::className(), ['order_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVars()
    {
        return $this->hasMany(ItemsVarsValues::className(), ['id' => 'var_id'])->viaTable('orders_items_vars', ['order_item_id' => 'id']);
    }
}
