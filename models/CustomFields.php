<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "custom_fields".
 *
 * @property integer $id
 * @property string $code
 * @property integer $post_id
 * @property string $class
 * @property string $value
 */
class CustomFields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'custom_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'class', 'name'], 'required'],
            [['code'], 'match', 'pattern'=>'/^[-a-zA-Z0-9_\s]+$/'],
            [['code', 'name', 'class'], 'string', 'max' => 255],
        ];
    }

    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['class' => 'rowChecker', 'value' => $model->id];
                }],

            'id',
            'name',
            'code',
            'class',

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        $classes = \Yii::$app->controller->allmodels();
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'code' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'class' => ['type' => 'dropDownList', 'data' => $classes],
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
            'code' => 'Код',
            'name' => 'Название',
            'class' => 'Тип',
        ];
    }
}
