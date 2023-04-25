<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "filters_values_props".
 *
 * @property integer $filter_value_id
 * @property integer $prop_id
 *
 * @property Props $prop
 * @property FiltersValues $filterValue
 */
class FiltersValuesProps extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'filters_values_props';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filter_value_id', 'prop_id'], 'required'],
            [['filter_value_id', 'prop_id'], 'integer'],
            [['prop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Props::className(), 'targetAttribute' => ['prop_id' => 'id']],
            [['filter_value_id'], 'exist', 'skipOnError' => true, 'targetClass' => FiltersValues::className(), 'targetAttribute' => ['filter_value_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filter_value_id' => 'Фильтр',
            'prop_id' => 'Характеристика',
            'from_value' => 'Значение от',
            'to_value' => 'Значение до',
        ];
    }

    public function updateRelations(){}

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
    public function getFilterValue()
    {
        return $this->hasOne(FiltersValues::className(), ['id' => 'filter_value_id']);
    }
}
