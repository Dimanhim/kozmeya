<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "items_vars_values".
 *
 * @property integer $id
 * @property integer $var_id
 * @property string $name
 * @property double $price
 * @property string $images
 * @property integer $vis
 * @property integer $posled
 *
 * @property CartVars[] $cartVars
 * @property Cart[] $carts
 * @property ItemsVars $var
 */
class ItemsVarsValues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'items_vars_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['var_id', 'name'], 'required'],
            [['var_id', 'vis', 'posled'], 'integer'],
            [['price'], 'number'],
            [['name', 'images'], 'string', 'max' => 255],
            [['var_id'], 'exist', 'skipOnError' => true, 'targetClass' => ItemsVars::className(), 'targetAttribute' => ['var_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'var_id' => 'Var ID',
            'name' => 'Name',
            'price' => 'Price',
            'images' => 'Images',
            'vis' => 'Vis',
            'posled' => 'Posled',
        ];
    }

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCartVars()
    {
        return $this->hasMany(CartVars::className(), ['var' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::className(), ['id' => 'cart'])->viaTable('cart_vars', ['var' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVar()
    {
        return $this->hasOne(ItemsVars::className(), ['id' => 'var_id']);
    }

    public function getValue()
    {
        return $this->hasOne(VarsShowtypesValues::className(), ['id' => 'var_value_id']);
    }
}