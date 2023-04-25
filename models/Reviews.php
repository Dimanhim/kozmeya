<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "reviews".
 *
 * @property integer $id
 * @property string $date
 * @property string $name
 * @property string $post
 * @property string $text
 * @property string $images
 * @property string $video
 * @property integer $vis
 * @property integer $posled
 * @property integer $main
 */
class Reviews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $imagesUploader;

    public static function tableName()
    {
        return 'reviews';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['name', 'text'], 'required'],
            [['text', 'images', 'video', 'email'], 'string'],
            [['vis', 'posled', 'main'], 'integer'],
            [['name', 'post'], 'string', 'max' => 255],
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

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'email' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'date' => ['type' => 'widget', 'widget' => \yii\jui\DatePicker::classname(), 'data' => ['language' => 'ru','dateFormat' => 'yyyy-MM-dd']],
            //'post' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'text' => ['type' => 'textArea', 'data' => ['rows' => 6, 'id' => 'editor']],
            'images' => ['type' => 'uploader', 'data' => ["name" => "imagesUploader"]],
            //'video' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'rating' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'rating_1' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'rating_2' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'rating_3' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'main' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 0],
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
            'date' => 'Дата',
            'name' => 'ФИО',
            'email' => 'E-mail',
            'post' => 'Должность',
            'text' => 'Описание',
            'images' => 'Фото',
            'imagesUploader' => 'Фото',
            'video' => 'Видео (Youtube ID)',
            'rating' => 'Общий рейтинг',
            'rating_1' => 'Рейтинг (Качество товаров)',
            'rating_2' => 'Рейтинг (Качество общения)',
            'rating_3' => 'Рейтинг (Качество доставки)',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
            'main' => 'Отображать на главной',
        ];
    }
}
