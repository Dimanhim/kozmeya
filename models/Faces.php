<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "faces".
 *
 * @property integer $id
 * @property string $name
 * @property string $post
 * @property string $vk
 * @property string $fb
 * @property string $phone
 * @property string $email
 * @property string $images
 * @property string $text
 * @property integer $vis
 * @property integer $posled
 */
class Faces extends \yii\db\ActiveRecord
{
    public $imagesUploader;
    public $bigimagesUploader;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faces';
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        \Yii::$app->functions->saveCustomFields($this);
    }

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['images', 'text'], 'string'],
            [['vis', 'posled'], 'integer'],
            [['name', 'post', 'vk', 'fb', 'phone', 'email'], 'string', 'max' => 255],
            [['images', 'bigimages'], 'safe'],
            [['images', 'bigimages'], 'file', 'maxFiles' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */

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
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'post' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'text' => ['type' => 'textArea', 'data' => ['rows' => 6, 'id' => 'editor']],
            'images' => ['type' => 'uploader', 'data' => ["name" => "imagesUploader"]],
            'bigimages' => ['type' => 'uploader', 'data' => ["name" => "bigimagesUploader"]],
            'vk' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'fb' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'phone' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'email' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
            '_customfields' => ['type' => 'customfields'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО',
            'post' => 'Должность',
            'vk' => 'Вконтакте',
            'fb' => 'Facebook',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'images' => 'Превью',
            'imagesUploader' => 'Превью',
            'bigimages' => 'Фото',
            'bigimagesUploader' => 'Фото',
            'text' => 'Описание',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
        ];
    }

    public function getItems()
    {
        return $this->hasMany(Items::className(), ['face_id' => 'id']);
    }
}
