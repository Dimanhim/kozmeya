<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "promocodes".
 *
 * @property integer $id
 * @property string $name
 * @property double $percent
 * @property integer $vis
 */
class Promocodes extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promocodes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            [['value', 'type'], 'number'],
            [['vis'], 'integer'],
            [['name', 'date_from', 'date_to'], 'string', 'max' => 255],
            [['categories', 'brands', 'users', 'items'], 'string'],
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $data = Yii::$app->request->post();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $data = Yii::$app->request->post();

            return true;
        }
        return false;
    }

    public function updateRelations(){
        $data = Yii::$app->request->post();

        \Yii::$app->db->createCommand()->delete('promocodes_conditions', ['promocode_id' => $this->id,])->execute();

        if(isset($data["conditions"]["value"]) && count($data["conditions"]["value"]) > 0) {


            foreach($data["conditions"]["value"] as $index=>$value){
                if($value != "" && $value > 0) {
                    $promoconditions = new PromocodesConditions();
                    $promoconditions->value = $value;
                    $promoconditions->promocode_id = $this->id;
                    $promoconditions->type = $data["conditions"]["type"][$index];
                    $promoconditions->condition = $data["conditions"]["condition"][$index];
                    $promoconditions->save();
                }
            }
        }
    }


    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'rowChecker', 'value' => $model->id];
            }],

            'id',
            'name',
            'value',

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'type' => ['type' => 'dropDownList', 'data' => [1 => '%', 2 => '$']],
            'value' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'date_from' => ['type' => 'widget', 'widget' => \yii\jui\DatePicker::classname(), 'data' => ['language' => 'ru', 'dateFormat' => 'yyyy-MM-dd']],
            'date_to' => ['type' => 'widget', 'widget' => \yii\jui\DatePicker::classname(), 'data' => ['language' => 'ru', 'dateFormat' => 'yyyy-MM-dd']],
            'unlimited' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 0],
            'categories' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'brands' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'users' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'items' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'conditions' => ['type' => 'lines', "field" => "conditions", "data" => [
                "object" => "conditions", "fields" => [
                    ["type" => "hidden", "name" => "id", "placeholder" => ""],
                    ["type" => "select", "name" => "type", "placeholder" => "Тип", "values" => ["result_price" => "Общая стоимость", "result_count" => "Кол-во товаров"]],
                    ["type" => "select", "name" => "condition", "placeholder" => "Условие", "values" => [">" => ">", "<" => "<", ">=" => ">=", "<=" => "<="]],
                    ["type" => "text", "name" => "value", "placeholder" => "Значение"],
                ]
            ]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_from' => 'Дата начала',
            'date_to' => 'Дата окончания',
            'unlimited' => 'Без даты',
            'name' => 'Код',
            'value' => 'Скидка',
            'type' => 'Тип',
            'vis' => 'Активный',
            'conditions' => 'Условия',
            'categories' => 'Категории (ID через запятую)',
            'brands' => 'Коллекции (ID через запятую)',
            'items' => 'Товары (ID через запятую)',
            'users' => 'Пользователи (ID через запятую)',
        ];
    }

    public function getConditions()
    {
        return $this->hasMany(PromocodesConditions::className(), ['promocode_id' => 'id']);
    }
}
