<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brands".
 *
 * @property integer $id
 * @property string $name
 * @property string $images
 * @property integer $vis
 * @property integer $posled
 */
class Deliverycities extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'deliverycities';
    }

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['name'], 'string'],
            [['price'], 'number'],
            [['vis', 'posled'], 'integer'],
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
            'country_id' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\Deliverycountries::find()->orderBy("name DESC")->all(), 'id', 'name')],
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'price' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
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
            'country_id' => 'Страна',
            'name' => 'Название',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
            'price' => 'Стоимость доставки',
        ];
    }

    public function getCountry()
    {
        return $this->hasOne(Deliverycountries::className(), ['id' => 'country_id']);
    }
}
