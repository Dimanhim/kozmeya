<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "static".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $text
 * @property integer $parent
 */
class StaticPage extends \yii\db\ActiveRecord
{
    public $url;
    public $imagesUploader;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'static';
    }
	
	public function getInner()
    {
        return $this->hasOne(StaticPage::className(), ['id' => 'parent']);
    }
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['text'], 'string'],
            [['parent', 'vis', 'top', 'left', 'bottom', 'main', 'posled'], 'integer'],
            [['name', 'alias'], 'string', 'max' => 255],
            [['alias', 'parent'], 'unique', 'targetAttribute' => ['alias', 'parent']],
            //[['alias'], 'match', 'pattern'=>'/^[-a-zA-Z0-9_\s]+$/'],
        ];
    }

    public function afterFind()
    {
        $this->url = Yii::$app->functions->hierarchyUrl($this);

        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        if (isset($this->alias) && $this->alias == "") {
            $this->alias = \Yii::$app->functions->setAlias($this->name);
            $this->save();
        }

        \Yii::$app->functions->saveCustomFields($this);
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

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

    public function columnsData(){
        return [
            ['class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                return ['class' => 'rowChecker', 'value' => $model->id];
            }],

            'id',
            'name',
            [
                'attribute' => 'parent',
                'format' => 'raw',
                'value'=> function($data) { if($data->parent0) return $data->parent0->name; },
            ],
            [
                'attribute' => 'alias',
                'format' => 'raw',
                'value'=> function($data) { return "<a href='/".$data->alias."'>".$data->alias."</a>"; },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true, 'class' => 'form-control nameToAlias', 'data-selector' => '.imAlias22']],
            'alias' => ['type' => 'textInput', 'data' => ['maxlength' => true, 'class' => 'form-control imAlias']],
            'parent' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\StaticPage::find()->orderBy("name DESC")->all(), 'id', 'name'), 'prompt'=>'Родительская'],
            'text' => ['type' => 'textarea', 'data' => ['rows' => 6, "id" => "editor"]],
                        'images' => ['type' => 'uploader', 'data' => ["name" => "imagesUploader"]],
            'top' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'left' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'bottom' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
            '_customfields' => ['type' => 'customfields'],
        ];
    }

    public function hierarchy()
    {
        $pages = StaticPage::find()->orderBy("posled")->all();
        $result = [];
        foreach($pages as $k=>$v){
            $result[$v->parent][] = $v;
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Заголовок',
            'alias' => 'Алиас',
            'text' => 'Текст',
            'parent' => 'Родитель',
            'vis' => 'Показывать',
            'top' => 'Отображать в верхнем меню',
            'left' => 'Отображать в левом меню',
            'bottom' => 'Отображать в нижнем меню',
            'main' => 'Отображать в основном меню',
                        'images' => 'Фото',
            'imagesUploader' => 'Фото',
            'posled' => 'Сортировка',
        ];
    }

    public function getParent0()
    {
        return $this->hasOne(StaticPage::className(), ['id' => 'parent']);
    }
}
