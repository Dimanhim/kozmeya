<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

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
class Sizes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $valuesData = [];

    public static function tableName()
    {
        return 'sizes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['vis', 'posled'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function afterFind()
    {
        $this->valuesData = ($this->values != "" ? Json::decode($this->values, false) : []);

        parent::afterFind();
    }

    public function updateRelations(){
        $data = Yii::$app->request->post();

        $this->values = "";

        $values = [];

        if(isset($data["values"]["name"]) && count($data["values"]["name"]) > 0) {
            foreach ($data["values"]["name"] as $index => $name) {
                if($name != "") {
                    $values[$index] = [
                        'name' => $name,
                        'group_id' => $data["values"]["group_id"][$index]
                    ];
                }
            }
        }

        $this->values = Json::encode($values);

        \Yii::$app->langs->saveTranslates($this);
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
            'values' => ['type' => 'lines', "field" => "values", "data" => [
                "object" => "valuesData", "fields" => [
                    ["type" => "select", "name" => "group_id", "placeholder" => "Группа", "values" => yii\helpers\ArrayHelper::map(\app\models\SizesGroups::find()->orderBy("posled")->all(), 'id', 'name')],
                    ["type" => "text", "name" => "name", "placeholder" => "Значение (через точку с запятой)"],
                ]
            ]],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
        ];
    }

    public function getItems()
    {
        return $this->hasMany(Items::className(), ['id' => 'item_id'])->viaTable('items_sizes', ['size_id' => 'id']);
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
            'values' => 'Значения',
        ];
    }
}
