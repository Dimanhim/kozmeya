<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "items_vars".
 *
 * @property integer $id
 * @property integer $item_id
 * @property integer $showtype_id
 * @property string $name
 * @property string $text
 * @property integer $vis
 * @property integer $posled
 *
 * @property VarsShowtypes $showtype
 * @property Items $item
 * @property ItemsVarsValues[] $itemsVarsValues
 */
class ItemsVars extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'items_vars';
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);


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

        \Yii::$app->db->createCommand()->delete('items_vars_values', ['var_id' => $this->id,])->execute();

        if(isset($data["values"]["id"]) && count($data["values"]["id"]) > 0) {
            foreach($data["values"]["id"] as $index=>$id){
                if(isset($data["values"]["name"][$index]) && $data["values"]["name"][$index] != "") {
                    $values = new ItemsVarsValues();
                    $values->id = $id;
                    $values->var_id = $this->id;
                    $values->var_value_id = (isset($data["values"]["var_value_id"][$index]) ? $data["values"]["var_value_id"][$index] : 0);
                    $values->name = (isset($data["values"]["name"][$index]) ? $data["values"]["name"][$index] : "");
                    $values->price = (isset($data["values"]["price"][$index]) && $data["values"]["price"][$index] > 0 ? $data["values"]["price"][$index] : 0);
                    $values->vis = (isset($data["values"]["vis"][$index]) && $data["values"]["vis"][$index] == 1 ? 1 : 0);
                    $values->posled = (isset($data["values"]["posled"][$index]) && $data["values"]["posled"][$index] > 0 ? $data["values"]["posled"][$index] : 999);
                    $values->images = (isset($data["values"]["images_hidden"][$index]) ? $data["values"]["images_hidden"][$index] : "");

                    \Yii::$app->functions->uploaderSimple($values, "values", "images", $index);

                    $values->save();
                }

            }
        }


        \Yii::$app->langs->saveTranslates($this);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'showtype_id', 'name'], 'required'],
            [['item_id', 'showtype_id', 'vis', 'posled'], 'integer'],
            [['text'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['showtype_id'], 'exist', 'skipOnError' => true, 'targetClass' => VarsShowtypes::className(), 'targetAttribute' => ['showtype_id' => 'id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Items::className(), 'targetAttribute' => ['item_id' => 'id']],
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
            [
                'attribute' => 'item_id',
                'format' => 'raw',
                'value'=> function($data) { if($data->item) return $data->item->name; },
            ],
            [
                'attribute' => 'showtype_id',
                'format' => 'raw',
                'value'=> function($data) { if($data->showtype) return $data->showtype->name; },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        $values = [0 => "Не выбрано"];
        $values = $values + yii\helpers\ArrayHelper::map(\app\models\VarsShowtypesValues::find()->where("id <> 0".(isset($this->showtype_id) && $this->showtype_id != "" ? " AND showtype_id = '".$this->showtype_id."'" : ""))->orderBy("name DESC")->all(), 'id', 'name');

        return [
            'item_id' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\Items::find()->orderBy("name DESC")->all(), 'id', 'name')],
            'showtype_id' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\VarsShowtypes::find()->orderBy("name DESC")->all(), 'id', 'name')],
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'text' => ['type' => 'textArea', 'data' => ['rows' => 6, 'id' => 'editor']],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
            'values' => ['type' => 'lines', "field" => "values", "data" => [
                "object" => "values", "fields" => [
                    ["type" => "hidden", "name" => "id", "placeholder" => ""],
                    ["type" => "text", "name" => "name", "placeholder" => "Название"],
                    ["type" => "text", "name" => "price", "placeholder" => "Цена"],
                    ["type" => "file", "name" => "images", "placeholder" => "Изображение"],
                    ["type" => "select", "name" => "var_value_id", "placeholder" => "Значение", "values" => $values],
                    ["type" => "text", "name" => "posled", "placeholder" => "Сортировка"],
                    ["type" => "checkbox", "name" => "vis", "placeholder" => "Показывать"],
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
            'item_id' => 'Товар',
            'showtype_id' => 'Тип',
            'name' => 'Заголовок',
            'text' => 'Описание',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
            'values' => 'Значения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShowtype()
    {
        return $this->hasOne(VarsShowtypes::className(), ['id' => 'showtype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Items::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValues()
    {
        return $this->hasMany(ItemsVarsValues::className(), ['var_id' => 'id']);
    }
}
