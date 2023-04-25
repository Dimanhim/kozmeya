<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "sliders".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $text
 * @property string $images
 */
class Sliders extends \yii\db\ActiveRecord
{
    public $imagesUploader;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sliders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['text', 'images'], 'string'],
            [['name', 'code'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['images'], 'safe'],
            [['images'], 'file', 'maxFiles' => 1000],
        ];
    }

    public function searchData(){
        return [];
    }

    public function columnsData(){
        $template = '{update}';
        if(\Yii::$app->user->identity->type == "root") {
            $template = '{update} {delete}';
        }

        return [
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['class' => 'rowChecker', 'value' => $model->id];
                }],
            'id',
            'name',

            ['class' => 'yii\grid\ActionColumn','template' => $template],
        ];
    }

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'code' => ['type' => 'hiddenInput', 'data' => []],
            'text' => ['type' => 'textArea', 'data' => ['rows' => 6, 'id' => 'editor']],
            'images' => ['type' => 'uploader_custom', 'data' => ["methodSize" => "ra/250x250/"]],
            '_customfields' => ['type' => 'customfields'],
        ];
    }

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Заголовок',
            'code' => 'Код',
            'text' => 'Описание',
            'images' => 'Фотографии',
            'imagesUploader' => 'Фотографии',
        ];
    }
}
