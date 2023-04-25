<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currency".
 *
 * @property integer $id
 * @property string $name
 * @property string $symbol
 * @property double $value
 * @property integer $before
 *
 * @property Items[] $items
 */
class Currency extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'symbol', 'value', 'code_cbr'], 'required'],
            [['value'], 'number'],
            [['before'], 'integer'],
            [['name', 'symbol', 'code_cbr'], 'string', 'max' => 255],
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
            'symbol' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'value' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'code_cbr' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'before' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'symbol' => 'Символ',
            'value' => 'Курс к рублю',
            'before' => 'Отображать перед ценой',
            'code_cbr' => 'Код валюты',
        ];
    }

    public function updateRelations(){}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Items::className(), ['currency_id' => 'id']);
    }
}
