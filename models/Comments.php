<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property string $date
 * @property string $model
 * @property integer $post_id
 * @property string $name
 * @property string $email
 * @property string $text
 * @property integer $pid
 * @property integer $vis
 *
 * @property Comments $p
 * @property Comments[] $comments
 */
class Comments extends \yii\db\ActiveRecord
{
    public $disableTranslates = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['model', 'post_id', 'text'], 'required'],
            [['post_id', 'pid', 'vis'], 'integer'],
            [['text'], 'string'],
            [['model', 'name', 'email'], 'string', 'max' => 255],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => Comments::className(), 'targetAttribute' => ['pid' => 'id']],
        ];
    }

    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'rowChecker', 'value' => $model->id];
            }],

            'id',
            'model',
            'post_id',
            'pid',
            'name',
            'email',
            'text',

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'date' => ['type' => 'widget', 'widget' => \yii\jui\DatePicker::classname(), 'data' => ['language' => 'ru','dateFormat' => 'yyyy-MM-dd']],
            'pid' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\Comments::find()->orderBy("name DESC")->all(), 'id', 'name'), 'prompt' => "-"],
            'model' => ['type' => 'dropDownList', 'data' => ["News" => 'Новости']],
            'post_id' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'email' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'text' => ['type' => 'textArea', 'data' => ['rows' => 6]],
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
            'date' => 'Дата',
            'model' => 'Раздел',
            'post_id' => 'Пост ID',
            'name' => 'Имя',
            'email' => 'E-mail',
            'text' => 'Текст',
            'pid' => 'Родитель',
            'vis' => 'Показывать',
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);


    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($this->parent == ""){
                $this->parent = 0;
            }

            return true;
        }

        return false;
    }

    public function updateRelations(){}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getP()
    {
        return $this->hasOne(Comments::className(), ['id' => 'pid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['pid' => 'id']);
    }
}
