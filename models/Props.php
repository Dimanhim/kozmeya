<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "props".
 *
 * @property integer $id
 * @property string $name
 * @property integer $vis
 * @property integer $posled
 *
 * @property ItemsProps[] $itemsProps
 */
class Props extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'props';
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

        \Yii::$app->db->createCommand()->delete('props_categories', ['prop_id' => $this->id,])->execute();

        if(isset($data["categories"]) && count($data["categories"]) > 0) {
            foreach($data["categories"] as $category_id){
                $propscategories = new PropsCategories();
                $propscategories->category_id = $category_id;
                $propscategories->prop_id = $this->id;
                $propscategories->save();
            }
        }

        \Yii::$app->db->createCommand()->delete('props_values', ['prop_id' => $this->id,])->execute();

        if(isset($data["values"]["name"]) && count($data["values"]["name"]) > 0) {
            foreach($data["values"]["name"] as $index=>$value_name){
                if($value_name != "") {
                    $propsvalues = new PropsValues();
                    $propsvalues->name = $value_name;
                    $propsvalues->prop_id = $this->id;
                    $propsvalues->save();
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
            [['name'], 'required'],
            [['vis', 'posled'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
                'attribute' => 'categories',
                'format' => 'raw',
                'value'=> function($data) {
                    $value = "";
                    if($data->categories) foreach($data->categories as $k=>$v){
                        $value .= "<div>".$v->name."</div>";
                    }

                    return $value;
                },
            ],
            [
                'attribute' => 'values',
                'format' => 'raw',
                'value'=> function($data) {
                    $value = "";
                    if($data->values) foreach($data->values as $k=>$v){
                        $value .= "<div>".$v->name."</div>";
                    }

                    return $value;
                },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
            'categories' => ['type' => 'recursive_checkbox', 'data' => ['label' => 'Категории', 'fieldname' => 'name', 'data' => \app\models\Categories::hierarchy()]],
            'values' => ['type' => 'lines', "field" => "values", "data" => [
                "object" => "values", "fields" => [
                    ["type" => "hidden", "name" => "id", "placeholder" => ""],
                    ["type" => "text", "name" => "name", "placeholder" => "Значение"],
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
            'name' => 'Наименование',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
            'categories' => 'Категории',
            'values' => 'Значения'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemsProps()
    {
        return $this->hasMany(ItemsProps::className(), ['prop_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['id' => 'category_id'])->viaTable('props_categories', ['prop_id' => 'id']);
    }

    public function getValues()
    {
        return $this->hasMany(PropsValues::className(), ['prop_id' => 'id']);
    }
}
