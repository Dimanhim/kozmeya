<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 */
class CartVars extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart_vars';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cart_id', 'var_id'], 'required'],
            [['cart_id'], 'var_id'],
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

    public function getCart()
    {
        return $this->hasOne(Cart::className(), ['id' => 'cart_id']);
    }

    public function getVar()
    {
        return $this->hasOne(ItemsVarsValues::className(), ['id' => 'var_id']);
    }
}
