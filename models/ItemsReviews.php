<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "items_reviews".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $name
 * @property string $email
 * @property string $text
 * @property string $images
 * @property integer $rating
 * @property integer $vis
 * @property integer $posled
 *
 * @property Items $item
 */
class ItemsReviews extends \yii\db\ActiveRecord
{
    public $imagesUploader;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'items_reviews';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'name', 'text'], 'required'],
            [['item_id', 'rating', 'vis', 'posled'], 'integer'],
            [['text', 'images'], 'string'],
            [['name', 'email'], 'string', 'max' => 255],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Items::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['date'], 'safe'],
            [['images'], 'safe'],
            [['images'], 'file', 'maxFiles' => 1000],
        ];
    }

    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['class' => 'rowChecker', 'value' => $model->id];
                }],

            'id',
            [
                'attribute' => 'item_id',
                'format' => 'raw',
                'value'=> function($data) { if($data->item != "") return $data->item->name; },
            ],
            'name',
            'text',

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'item_id' => ['type' => 'mindsearch', 'data' => ['modelClass' => 'app\models\Items', 'fields' => ['id', 'name'], 'searchfields' => ['id', 'name']]],
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'email' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'text' => ['type' => 'textArea', 'data' => ['rows' => 6, 'id' => 'editor']],
            'images' => ['type' => 'uploader', 'data' => ["name" => "imagesUploader"]],
            'rating' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
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
            'item_id' => 'Товар',
            'name' => 'Ф.И.О',
            'email' => 'E-mail',
            'text' => 'Текст',
            'images' => 'Изображения',
            'imagesUploader' => 'Изображения',
            'rating' => 'Рейтинг',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Items::className(), ['id' => 'item_id']);
    }
}
