<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "promocodes_conditions".
 *
 * @property integer $id
 * @property integer $promocode_id
 * @property string $type
 * @property double $value
 *
 * @property Promocodes $promocode
 */
class PromocodesConditions extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promocodes_conditions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promocode_id'], 'required'],
            [['promocode_id'], 'integer'],
            [['type', 'condition'], 'string'],
            [['value'], 'number'],
            [['promocode_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promocodes::className(), 'targetAttribute' => ['promocode_id' => 'id']],
        ];
    }

    public function updateRelations(){}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promocode_id' => 'Промо-код',
            'type' => 'Тип',
            'value' => 'Значение',
            'condition' => 'Условие',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromocode()
    {
        return $this->hasOne(Promocodes::className(), ['id' => 'promocode_id']);
    }
}
