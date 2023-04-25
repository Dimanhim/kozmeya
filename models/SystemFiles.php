<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "system_files".
 *
 * @property integer $id
 * @property string $path
 * @property string $name
 * @property string $content
 */
class SystemFiles extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content'], 'required'],
            [['content'], 'string'],
            [['path', 'name'], 'string', 'max' => 255],
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

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'content' => ['type' => 'textArea', 'data' => ['row' => 6]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Путь',
            'name' => 'Название файла',
            'content' => 'Содержимое файла',
        ];
    }
}
