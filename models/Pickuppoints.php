<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pickuppoints".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property double $lat
 * @property double $long
 * @property integer $vis
 * @property integer $posled
 *
 * @property Orders[] $orders
 */
class Pickuppoints extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pickuppoints';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['address'], 'string'],
            [['lat', 'long'], 'number'],
            [['vis', 'posled'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

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
            'address' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'latlong' => ['type' => 'latlong', 'data' => ['lat' => 'lat', 'long' => 'long']],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
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
            'address' => 'Адрес',
            'lat' => 'Широта',
            'long' => 'Долгота',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['pickup_point_id' => 'id']);
    }
}
