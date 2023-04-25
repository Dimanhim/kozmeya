<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "partners".
 *
 * @property integer $id
 * @property string $name
 * @property string $images
 * @property integer $vis
 * @property integer $posled
 */
class Partners extends \yii\db\ActiveRecord
{

    public $imagesUploader;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['images'], 'string'],
            [['vis', 'posled'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['images'], 'safe'],
            [['images'], 'file', 'maxFiles' => 1000],
        ];
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
                'attribute' => 'images',
                'format' => 'image',
                'value'=> function($data) { if($data->images != "") return \Yii::$app->functions->getUploadItem($data, "images", "rn", "100x100"); },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'images' => ['type' => 'uploader', 'data' => ["name" => "imagesUploader"]],
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
            'images' => 'Фото',
            'imagesUploader' => 'Фото',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
        ];
    }
}
