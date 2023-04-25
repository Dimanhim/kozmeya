<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "items_props".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $prop_id
 * @property string $value
 * @property integer $show
 *
 * @property Props $prop
 * @property Items $item
 */
class ItemsProps extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'items_props';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'prop_id', 'value'], 'required'],
            [['item_id', 'prop_id', 'show'], 'integer'],
            [['value'], 'string'],
            [['prop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Props::className(), 'targetAttribute' => ['prop_id' => 'id']],
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
            'item_id' => 'Item ID',
            'prop_id' => 'Prop ID',
            'value' => 'Value',
            'show' => 'Show',
        ];
    }

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProp()
    {
        return $this->hasOne(Props::className(), ['id' => 'prop_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Items::className(), ['id' => 'item_id']);
    }
}
