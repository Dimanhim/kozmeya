<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "langs".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $flag
 * @property integer $vis
 *
 * @property Translates[] $translates
 * @property TranslatesGlobal[] $translatesGlobals
 */
class Langs extends \yii\db\ActiveRecord
{
    public $flagUploader;
    public $disableTranslates = true;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'langs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['flag'], 'string'],
            [['vis', 'default', 'currency_id'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
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
            'name',
            [
                'attribute' => 'flag',
                'format' => 'image',
                'value'=> function($data) { if($data->flag != "") return \Yii::$app->functions->getUploadItem($data, "flag", "ra", "20x20"); },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'code' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'flag' => ['type' => 'uploader', 'data' => ["name" => "flagUploader"]],
            'currency_id' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\Currency::find()->orderBy("name DESC")->all(), 'id', 'name'), 'prompt'=>'-'],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
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
            'currency_id' => 'Валюта по умолчанию (Для цен)',
            'code' => 'Код',
            'flag' => 'Флаг',
            'flagUploader' => 'Флаг',
            'vis' => 'Активный',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslates()
    {
        return $this->hasMany(Translates::className(), ['land_id' => 'id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslatesGlobals()
    {
        return $this->hasMany(TranslatesGlobal::className(), ['lang_id' => 'id']);
    }
}
