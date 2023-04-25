<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subscribes".
 *
 * @property integer $id
 * @property string $email
 * @property string $date
 * @property string $model
 * @property integer $active
 */
class Subscribes extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscribes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'model'], 'required'],
            [['date'], 'safe'],
            [['email'], 'email'],
            [['active'], 'integer'],
            [['email', 'model'], 'string', 'max' => 255],
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
            'email',

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'email' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'model' => ['type' => 'dropDownList', 'data' => ["News" => "News"]],
            'active' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'E-mail',
            'date' => 'Дата',
            'model' => 'Тип',
            'active' => 'Активный',
        ];
    }
}
