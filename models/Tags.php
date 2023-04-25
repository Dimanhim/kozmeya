<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property integer $id
 * @property string $name
 * @property string $post
 * @property string $vk
 * @property string $fb
 * @property string $phone
 * @property string $email
 * @property string $images
 * @property string $text
 * @property integer $vis
 * @property integer $posled
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias', 'url'], 'required'],
            [['name', 'alias', 'url', 'custom_url', 'anchor', 'hashalias', 'hashurl', 'title', 'description', 'text'], 'string'],
            [['vis', 'posled', 'category_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $data = Yii::$app->request->post();

        $this->hashalias = substr(md5($this->alias),0,255);
        $this->hashurl   = substr(md5($this->url),0,255);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $data = Yii::$app->request->post();

            return true;
        }
        return false;
    }

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'rowChecker', 'value' => $model->id];
            }],

            'id',
            'name',
            'alias',
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value'=> function($data) { return $data->category->name; },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
			'anchor' => ['type' => 'textInput', 'data' => ['maxlength' => true]],

            'category_id' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\Categories::find()->orderBy("name DESC")->all(), 'id', 'name')],

            'custom_url' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'url' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'alias' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
			'title' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'text' => ['type' => 'textArea', 'data' => ['rows' => 6, 'id' => 'editor']],
			'description' => ['type' => 'textArea', 'data' => ['rows' => 6]],

            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'anchor' => 'Кратко',
            'category_id' => 'Категория',
            'custom_url' => 'URL страницы размещения',
			'url' => 'URL фильтра',
            'alias' => 'Алиас',
            'title' => 'Meta title',
            'description' => 'Meta description',
            'text' => 'Описание',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'category_id']);
    }
}
