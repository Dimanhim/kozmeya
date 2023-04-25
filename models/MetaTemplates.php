<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "meta_templates".
 *
 * @property integer $id
 * @property string $model
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property integer $active
 */
class MetaTemplates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'meta_templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model'], 'required'],
            [['title', 'description', 'keywords'], 'string'],
            [['active'], 'integer'],
            [['model'], 'string', 'max' => 255],
            [['model'], 'unique'],
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        if(!$insert) {
            if(isset($changedAttributes["title"]) || isset($changedAttributes["description"]) || isset($changedAttributes["keywords"])) {
                $resetModel = Yii::createObject([
                        'class' => "app\models\\".$this->model,
                ]);

                \Yii::$app->db->createCommand("UPDATE `".$resetModel->tableName()."` SET `meta_data`= ''")->execute();
            }
        }
    }


    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'rowChecker', 'value' => $model->id];
            }],

            'id',
            'model',
            'title',
            'description',
            'keywords',

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function updateRelations(){}

    public function getMetaModels(){
        return [
            "Categories" => "Категории",
            "Items" => "Товары",
            "News" => "Новости"
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Тип',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'active' => 'Активно',
        ];
    }
}
