<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $small
 * @property string $text
 * @property string $date
 * @property string $images
 * @property integer $count_view
 * @property integer $vis
 * @property integer $posled
 */
class Leads extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'leads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_data'], 'string'],
            [['date'], 'safe'],
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            return true;
        }

        return false;
    }

    public function updateRelations(){}

    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'rowChecker', 'value' => $model->id];
            }],

            'id',
            'date',
            [
                'attribute' => 'form_data',
                'format' => 'raw',
                'value'=> function($data) { return \Yii::$app->controller->renderPartial("@app/mail/form", ['postData' => \yii\helpers\Json::decode($data->form_data)]); },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{delete}'],
        ];
    }

    public function fieldsData()
    {
        return [

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
            'form_data' => 'Просмотр',
        ];
    }
}
