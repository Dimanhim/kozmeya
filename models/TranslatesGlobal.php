<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "translates_global".
 *
 * @property integer $id
 * @property integer $lang_id
 * @property string $value
 * @property string $translate
 *
 * @property Langs $lang
 */
class TranslatesGlobal extends \yii\db\ActiveRecord
{

    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'translates_global';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_id', 'value', 'translate'], 'required'],
            [['lang_id'], 'integer'],
            [['value', 'translate'], 'string'],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Langs::className(), 'targetAttribute' => ['lang_id' => 'id']],
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
            [
                'attribute' => 'lang_id',
                'format' => 'raw',
                'value'=> function($data) { if($data->lang) return $data->lang->name; },
            ],
            'value',
            'translate',

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'lang_id' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\Langs::find()->where("`default` = '0'")->orderBy("name DESC")->all(), 'id', 'name')],
            'value' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'translate' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lang_id' => 'Язык',
            'value' => 'Значение',
            'translate' => 'Перевод',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Langs::className(), ['id' => 'lang_id']);
    }
}
