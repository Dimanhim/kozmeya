<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vars_showtypes_values".
 *
 * @property integer $id
 * @property integer $showtype_id
 * @property string $name
 * @property string $text
 * @property string $files
 * @property integer $vis
 * @property integer $posled
 *
 * @property VarsShowtypes $showtype
 */
class VarsShowtypesValues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vars_showtypes_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['showtype_id', 'name'], 'required'],
            [['showtype_id', 'vis', 'posled'], 'integer'],
            [['text', 'files'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['showtype_id'], 'exist', 'skipOnError' => true, 'targetClass' => VarsShowtypes::className(), 'targetAttribute' => ['showtype_id' => 'id']],
        ];
    }

    public function searchData(){
        return [];
    }

    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['class' => 'rowChecker', 'value' => $model->id];
                }],

            'id',
            'name',
            [
                'attribute' => 'showtype_id',
                'format' => 'raw',
                'value'=> function($data) { if($data->showtype) return $data->showtype->name; },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'showtype_id' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\VarsShowtypes::find()->orderBy("name DESC")->all(), 'id', 'name')],
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'text' => ['type' => 'textArea', 'data' => ['rows' => 6]],
            'files' => ['type' => 'uploader_custom', 'data' => ["methodSize" => "ra/250x250/"]],
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
            'showtype_id' => 'Тип',
            'name' => 'Наименование',
            'text' => 'Дополнительно',
            'files' => 'Файлы',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShowtype()
    {
        return $this->hasOne(VarsShowtypes::className(), ['id' => 'showtype_id']);
    }
}
