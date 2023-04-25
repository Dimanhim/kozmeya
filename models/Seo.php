<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seo".
 *
 * @property integer $id
 * @property string $url
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $h1
 * @property string $text
 */
class Seo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['url', 'description', 'text'], 'string'],
            [['title', 'keywords', 'h1'], 'string', 'max' => 255],
            [['url'], 'unique'],
        ];
    }

    public function updateRelations(){}

    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'rowChecker', 'value' => $model->id];
            }],

            'id',
            'url',
            'title',

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'url' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'title' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'h1' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'description' => ['type' => 'textArea', 'data' => ['rows' => 6]],
            'keywords' => ['type' => 'textArea', 'data' => ['rows' => 6]],
            'text' => ['type' => 'textArea', 'data' => ['rows' => 6, 'id' => 'editor']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'URL',
            'title' => 'Meta title',
            'description' => 'Meta description',
            'keywords' => 'Meta keywords',
            'h1' => 'H1',
            'text' => 'Текст',
        ];
    }
}
