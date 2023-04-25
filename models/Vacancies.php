<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vacancies".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 * @property string $text
 * @property integer $vis
 * @property integer $posled
 *
 * @property Categories $category
 */
class Vacancies extends \yii\db\ActiveRecord
{
    public $url;
    public $section_id = 11;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vacancies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'name', 'text'], 'required'],
            [['category_id', 'vis', 'posled'], 'integer'],
            [['text'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => VacanciesCategories::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    public function afterFind()
    {
        $this->url = Yii::$app->functions->hierarchyUrl(\Yii::$app->params['allPages'][$this->section_id])."/".$this->id;

        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        \Yii::$app->functions->saveCustomFields($this);
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
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value'=> function($data) { if($data->category) return $data->category->name; },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'category_id' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\VacanciesCategories::find()->orderBy("name DESC")->all(), 'id', 'name')],
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'text' => ['type' => 'textArea', 'data' => ['rows' => 6, 'id' => 'editor']],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
            '_customfields' => ['type' => 'customfields'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория',
            'name' => 'Заголовок',
            'text' => 'Описание',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(VacanciesCategories::className(), ['id' => 'category_id']);
    }
}
