<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property integer $id
 * @property string $name
 * @property integer $vis
 * @property integer $posled
 * @property integer $online
 *
 * @property Orders[] $orders
 */
class Payments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['vis', 'posled', 'online'], 'integer'],
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
            'online' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 0],
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
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
            'online' => 'Онлайн оплата',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['payment_id' => 'id']);
    }

    public function getDeliveries()
    {
        return $this->hasMany(Deliveries::className(), ['id' => 'delivery_id'])->viaTable('deliveriespayments', ['payment_id' => 'id']);
    }
}
