<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "redirects".
 *
 * @property integer $id
 * @property string $url
 * @property string $redirect_url
 * @property integer $code
 */
class Redirects extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'redirects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'redirect_url'], 'required'],
            [['url', 'redirect_url'], 'string'],
            [['code'], 'integer'],
            [['url'], 'unique'],
            ['url', 'compare', 'compareAttribute' => 'redirect_url', 'operator' => '!='],
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
            'redirect_url',

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'url' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'redirect_url' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'code' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
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
            'redirect_url' => 'Редирект',
            'code' => 'Код ответа',
        ];
    }
}
