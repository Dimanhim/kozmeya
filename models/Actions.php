<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Json;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $small
 * @property string $text
 * @property string $date
 * @property string $images
 * @property integer $count_view
 * @property integer $vis
 * @property integer $posled
 */
class Actions extends \yii\db\ActiveRecord
{
    public $imagesUploader;
    public $url;
    public $section_id = 15;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'actions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['small', 'text', 'images'], 'string'],
            [['date'], 'safe'],
            [['count_view', 'vis', 'posled'], 'integer'],
            [['name', 'alias'], 'string', 'max' => 255],
            [['images'], 'safe'],
            [['images'], 'file', 'maxFiles' => 1000],
            [['alias'], 'unique'],
            [['alias'], 'match', 'pattern'=>'/^[-a-zA-Z0-9_\s]+$/'],
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        if (isset($this->alias) && $this->alias == "") {
            $this->alias = \Yii::$app->functions->setAlias($this->name);
        }

        \Yii::$app->functions->saveCustomFields($this);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $data = Yii::$app->request->post();

            if(isset($data["items_conditions"])) $this->items_conditions = Json::encode($data["items_conditions"]);

            return true;
        }

        return false;
    }

    public function afterFind()
    {
        $this->url = Yii::$app->functions->hierarchyUrl(\Yii::$app->params['allPages'][$this->section_id])."/".$this->alias;

        parent::afterFind();
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
                'attribute' => 'images',
                'format' => 'image',
                'value'=> function($data) { if($data->images != "") return \Yii::$app->functions->getUploadItem($data, "images", "ra", "100x100"); },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'date' => ['type' => 'widget', 'widget' => \yii\jui\DatePicker::classname(), 'data' => ['language' => 'ru','dateFormat' => 'yyyy-MM-dd']],
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'alias' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'small' => ['type' => 'textArea', 'data' => ['rows' => 6]],
            'text' => ['type' => 'textArea', 'data' => ['rows' => 6, 'id' => 'editor']],
            'images' => ['type' => 'uploader', 'data' => ["name" => "imagesUploader"]],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
            'items_conditions' => ['type' => 'catalog_conditions', 'data' => ($this->items_conditions != "" ? Json::decode($this->items_conditions, false) : [])],
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
            'name' => 'Заголовок',
            'alias' => 'Алиас',
            'small' => 'Кратко',
            'text' => 'Описание',
            'date' => 'Дата',
            'images' => 'Фото',
            'imagesUploader' => 'Фото',
            'count_view' => 'Количество просмотров',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
        ];
    }

    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['post_id' => 'id'])->andOnCondition(['model' => \Yii::$app->functions->getModelName($this)]);
    }
}
