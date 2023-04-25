<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sid', 'item_id'], 'required'],
            [['qty'], 'integer'],
            [['price'], 'number'],
            [['color', 'size'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [];
    }

    public function updateRelations(){}

    public function getItem()
    {
        return $this->hasOne(Items::className(), ['id' => 'item_id']);
    }

    public function getCartVars()
    {
        return $this->hasMany(CartVars::className(), ['cart_id' => 'id']);
    }

    public function getVars()
    {
        return $this->hasMany(ItemsVarsValues::className(), ['id' => 'var_id'])->viaTable('cart_vars', ['cart_id' => 'id']);
    }
}
