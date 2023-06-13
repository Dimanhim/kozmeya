<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $images
 * @property string $text
 * @property integer $vis
 * @property integer $posled
 *
 * @property Categoriestoitems[] $categoriestoitems
 * @property Items[] $items
 */
class Categories extends \yii\db\ActiveRecord
{
    const ID_DESPOKE        = 20;
    const ID_SEMI_BESPOKE   = 22;
    const ID_CONVERTED      = 24;
    const ID_OCCASION       = 47;

    /**
     * @inheritdoc
     */
    public $url;
    public $section_id = 2;

    public $imagesUploader;
    public $menuimagesUploader;

    public static function tableName()
    {
        return 'categories';
    }

    public function afterFind()
    {
        $this->url = Yii::$app->catalog->categoryUrl($this->id);

        parent::afterFind();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        \Yii::$app->db->createCommand("DELETE FROM `aliases` WHERE `alias` = '".$this->alias."' AND `model` = '".Yii::$app->functions->getModelName($this)."'")->execute();
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        if(isset($this->alias) && $this->alias == "") {
            $this->alias = \Yii::$app->functions->setAlias($this->name);
        }

        if(isset($changedAttributes["alias"]) && $changedAttributes["alias"] != "" && $this->alias != $changedAttributes["alias"]) {
            \Yii::$app->db->createCommand("DELETE FROM `aliases` WHERE `alias` = '".$changedAttributes["alias"]."' AND `model` = '".Yii::$app->functions->getModelName($this)."'")->execute();
        }

        \Yii::$app->db->createCommand("INSERT INTO `aliases`(`alias`, `model`) VALUES ('".$this->alias."', '".Yii::$app->functions->getModelName($this)."') ON DUPLICATE KEY UPDATE id=id")->execute();

        \Yii::$app->functions->saveCustomFields($this);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($this->parent == ""){
                $this->parent = 0;
            }

            if($alias = Aliases::find()->where(["alias" => $this->alias])->andWhere(["!=", "model", Yii::$app->functions->getModelName($this)])->one()) {
                $this->addError("alias", 'Данный алиас уже используется в другой записи');
                return false;
            }

            return true;
        }
        return false;
    }

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'anchor' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'parent' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\Categories::find()->orderBy("name DESC")->all(), 'id', 'name'), 'prompt'=>'Родительская'],
            'alias' => ['type' => 'textInput', 'data' => ['maxlength' => true]],

            'text' => ['type' => 'textArea', 'data' => ['rows' => 6, 'id' => 'editor']],
            'small' => ['type' => 'textArea', 'data' => ['rows' => 6]],
            'images' => ['type' => 'uploader', 'data' => ["name" => "imagesUploader"]],
            'menuimages' => ['type' => 'uploader', 'data' => ["name" => "menuimagesUploader"]],

            'menu' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 0],
            'main' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 0],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
            '_customfields' => ['type' => 'customfields'],
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
            [
                'attribute' => 'parent',
                'format' => 'raw',
                'value'=> function($data) { if($data->parent0) return $data->parent0->name; },
            ],
            'alias',
            [
                'attribute' => 'images',
                'format' => 'image',
                'value'=> function($data) { if($data->images != "") return \Yii::$app->functions->getUploadItem($data, "images", "ra", "100x100"); },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['images', 'small', 'text', 'anchor'], 'string'],
            [['parent', 'menu', 'main', 'vis', 'posled'], 'integer'],
            [['name', 'alias'], 'string', 'max' => 255],
            [['images', 'menuimages'], 'safe'],
            [['images', 'menuimages'], 'file', 'maxFiles' => 1000],
            [['alias', 'parent'], 'unique', 'targetAttribute' => ['alias', 'parent']],
            [['alias'], 'match', 'pattern'=>'/^[-a-zA-Z0-9_\s]+$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'parent' => 'Внутри',
            'anchor' => 'Заголовок в меню SALE',
            'alias' => 'Алиас',
            'images' => 'Фотографии',
            'imagesUploader' => 'Фотографии',
            'menuimages' => 'Фото меню',
            'menuimagesUploader' => 'Фото меню',
            'small' => 'Кратко',
            'text' => 'Описание',
            'menu' => 'Показывать в меню',
            'main' => 'Показывать на главной',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
        ];
    }

    public function hierarchy()
    {
        $categories = Categories::find()->orderBy("posled")->all();
        $result = [];
        foreach($categories as $k=>$v){
            $result[$v->parent][] = $v;
        }

        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriestoitems()
    {
        return $this->hasMany(Categoriestoitems::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Items::className(), ['id' => 'item_id'])->viaTable('categoriestoitems', ['category_id' => 'id']);
    }

    public function getParent0()
    {
        return $this->hasOne(Categories::className(), ['id' => 'parent']);
    }

    public function getSubs()
    {
        return $this->hasMany(Categories::className(), ['parent' => 'id']);
    }

    public function getFilters()
    {
        return $this->hasMany(Filters::className(), ['id' => 'filter_id'])->viaTable('filters_categories', ['category_id' => 'id']);
    }
    public function innerCategory($alias)
    {
        if($page = Items::findOne(['alias' => $alias])) {
            if($page->categoryData && ($categoryData = json_decode($page->categoryData, true))) {
                if($categoryData['id'] == $this->id) return true;
            }
        }
        return false;
    }
}
