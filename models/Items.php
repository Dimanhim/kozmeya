<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "items".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property double $price
 */
class Items extends \yii\db\ActiveRecord
{
    const UPLOAD_PATH = "ra/250x250/";
    /**
     * @inheritdoc
     */

    public $imagesUploader;
    public $_categoryData = [];
    public $url;
    public $section_id = 2;

    public static function tableName()
    {
        return 'items';
    }

    public function afterFind()
    {
        $this->url = Yii::$app->catalog->itemUrl($this);
        $this->_categoryData = ($this->categoryData != "" ? Json::decode($this->categoryData, false) : new Categories());

        parent::afterFind();
    }


    public function afterDelete()
    {
        parent::afterDelete();

        \Yii::$app->db->createCommand("DELETE FROM `aliases` WHERE `alias` = '".$this->alias."' AND `model` = '".Yii::$app->functions->getModelName($this)."'")->execute();
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        if($this->alias == "") {
            $this->alias = \Yii::$app->functions->setAlias($this->name)."-".$this->id;
        }

        if(isset($changedAttributes["alias"]) && $changedAttributes["alias"] != "" && $this->alias != $changedAttributes["alias"]) {
            \Yii::$app->db->createCommand("DELETE FROM `aliases` WHERE `alias` = '".$changedAttributes["alias"]."' AND `model` = '".Yii::$app->functions->getModelName($this)."'")->execute();
        }

        \Yii::$app->db->createCommand("INSERT INTO `aliases`(`alias`, `model`) VALUES ('".$this->alias."', '".Yii::$app->functions->getModelName($this)."') ON DUPLICATE KEY UPDATE id=id")->execute();

        $prices = \Yii::$app->catalog->itemPrice($this);
        \Yii::$app->db->createCommand()->update('items', ['_system_dynamic_price' => $prices["price"]], "id = '".$this->id."'")->execute();


        \Yii::$app->functions->saveCustomFields($this);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $data = Yii::$app->request->post();

            if($this->parent == ""){
                $this->parent = 0;
            }

            if($this->percent == ""){
                $this->percent = 0;
            }

            if($this->weight == ""){
                $this->weight = 0;
            }

            if($this->box_weight == ""){
                $this->box_weight = 0;
            }

            if($this->price == ""){
                $this->price = 0;
            }

            if($this->old_price == ""){
                $this->old_price = 0;
            }

            if($alias = Aliases::find()->where(["alias" => $this->alias])->andWhere(["!=", "model", Yii::$app->functions->getModelName($this)])->one()) {
                $this->addError("alias", 'Данный алиас уже используется в другой записи');
                return false;
            }

            return true;
        }
        return false;
    }

    public function updateRelations(){
        $data = Yii::$app->request->post();

        \Yii::$app->db->createCommand()->delete('categoriestoitems', ['item_id' => $this->id,])->execute();

        if(isset($data["categories"]) && count($data["categories"]) > 0) {
            $index = 0;foreach($data["categories"] as $category_id){ $index++;
                if($index == 1){
                    $category = Categories::findOne($category_id);
                    $this->categoryData = Json::encode($category);
                }

                $categoriestoitems = new CategoriesToItems();
                $categoriestoitems->category_id = $category_id;
                $categoriestoitems->item_id = $this->id;
                $categoriestoitems->save();
            }
        }

        \Yii::$app->db->createCommand()->delete('items_props', ['item_id' => $this->id,])->execute();

        if(isset($data["props"]) && count($data["props"]) > 0) {
            foreach($data["props"] as $prop_id=>$values){
                $valuesArray = explode(";", $values["value"]);

                foreach($valuesArray as $value){
                    $itemsprops = new ItemsProps();
                    $itemsprops->prop_id = $prop_id;
                    $itemsprops->item_id = $this->id;
                    $itemsprops->value = $value;
                    $itemsprops->show = $values["show"];
                    $itemsprops->save();
                }

            }
        }

        \Yii::$app->db->createCommand()->delete('items_sizes', ['item_id' => $this->id,])->execute();

        if(isset($data["sizes"]) && count($data["sizes"]) > 0) {
            foreach($data["sizes"] as $size_id){
                $itemssizes = new ItemsSizes();
                $itemssizes->size_id = $size_id;
                $itemssizes->item_id = $this->id;
                $itemssizes->save();
            }
        }

        \Yii::$app->db->createCommand()->delete('items_colors', ['item_id' => $this->id,])->execute();

        if(isset($data["colors"]) && count($data["colors"]) > 0) {
            foreach($data["colors"] as $color_id){
                $itemscolors = new ItemsColors();
                $itemscolors->color_id = $color_id;
                $itemscolors->item_id = $this->id;
                $itemscolors->save();
            }
        }

        \Yii::$app->langs->saveTranslates($this);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'status_id', 'price'], 'required'],
            [['parent', 'brand_id', 'status_id', 'currency_id', 'price', 'old_price', 'percent', 'new', 'best', 'special', 'day_item', 'rating', 'vis', 'posled', '_system_dynamic_price', 'weight', 'box_type', 'box_weight'], 'number'],
            [['name', 'alias'], 'string', 'max' => 255],
            [['text', 'meta_data'], 'string'],
            [['images'], 'safe'],
            [['images'], 'file', 'maxFiles' => 1000],
            [['alias'], 'unique'],
            [['alias'], 'match', 'pattern'=>'/^[-a-zA-Z0-9_\s]+$/'],
        ];
    }

    public function fieldsData()
    {
        return [
            'images' => ['type' => 'uploader_custom', 'data' => ["methodSize" => Items::UPLOAD_PATH]],
        ];
    }

    public function searchData(){
        $queryPrice = Yii::$app->db->createCommand("SELECT MAX(price) as max, MIN(price) as min FROM items t WHERE t.vis = '1' AND t.price <> '0'")->queryOne();

        $max = intval($queryPrice['max']);
        $min = intval($queryPrice['min']);

        return [
            's' => ['label' => 'Поиск', 'type' => 'fulltext'],
            'between_price' => ['label' => 'Стоимость', 'type' => 'between-slider', 'min' => $min, 'max' => $max, 'step' => 100],
            'categories' => ['label' => 'Категории', 'type' => 'select', 'values' => \app\models\Categories::find()->select(["id", "name"])->orderBy("name DESC")->all()],
            'brands' => ['label' => 'Бренды', 'type' => 'select',  'values' => \app\models\Brands::find()->select(["id", "name"])->orderBy("name DESC")->all()],
            'statuses' => ['label' => 'Статусы', 'type' => 'select',  'values' => \app\models\ItemsStatus::find()->select(["id", "name"])->orderBy("name DESC")->all()],
            'vis' => ['label' => 'Показывать', 'type' => 'checkbox'],
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
            'alias',
            [
                'attribute' => 'brand_id',
                'format' => 'raw',
                'value'=> function($data) { return $data->brand->name; },
            ],
            [
                'attribute' => 'images',
                'format' => 'image',
                'value'=> function($data) { if($data->images != "") return \Yii::$app->functions->getUploadItem($data, "images", "ra", "100x100"); },
            ],
            //'price:currency',
            [
                'attribute' => 'categories',
                'format' => 'raw',
                'value'=> function($data) {
                    $value = "";
                    if($data->categories) foreach($data->categories as $k=>$v){
                        $value .= "<div>".$v->name."</div>";
                    }

                    return $value;
                },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent' => 'Родитель',
            'brand_id' => 'Коллекция',
            'status_id' => 'Статус',
            'currency_id' => 'Валюта',
            'name' => 'Наименование',
            'alias' => 'Алиас',
            'price' => 'Цена',
            'old_price' => 'Старая цена',
            'text' => 'Описание',
            'images' => 'Фотографии',
            'imagesUploader' => 'Фотографии',
            'new' => 'Новинка',
            'best' => 'Хит',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
            'special' => 'Спец.предложение',
            'day_item' => 'Предложение дня',
            'rating' => 'Рейтинг',
            'date' => 'Дата',
            'vars' => 'Модификации',
            'categories' => 'Категории',
            'sizes' => 'Размеры',
            'colors' => 'Цвета',
            'weight' => 'Вес',
            'box_type' => 'Тип коробки',
            'box_weight' => 'Вес коробки',
            'subs' => 'Дочерние товары',
            'percent' => 'Скидка %',
        ];
    }

    public function getCategories()
    {
        return $this->hasMany(Categories::className(), ['id' => 'category_id'])->viaTable('categoriestoitems', ['item_id' => 'id']);
    }

    public function getSizes()
    {
        return $this->hasMany(Sizes::className(), ['id' => 'size_id'])->viaTable('items_sizes', ['item_id' => 'id']);
    }

    public function getColors()
    {
        return $this->hasMany(Colors::className(), ['id' => 'color_id'])->viaTable('items_colors', ['item_id' => 'id']);
    }

    public function getProps()
    {
        return $this->hasMany(ItemsProps::className(), ['item_id' => 'id']);
    }

    public function getBrand()
    {
        return $this->hasOne(Brands::className(), ['id' => 'brand_id']);
    }

    public function getStatus()
    {
        return $this->hasOne(ItemsStatus::className(), ['id' => 'status_id']);
    }

    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    public function getParent0()
    {
        return $this->hasOne(Items::className(), ['id' => 'parent']);
    }

    public function getSubs()
    {
        return $this->hasMany(Items::className(), ['parent' => 'id']);
    }

    public function getVars()
    {
        return $this->hasMany(ItemsVars::className(), ['item_id' => 'id'])->joinWith("showtype")->orderBy("vars_showtypes.posled");
    }

    public function getReviews()
    {
        return $this->hasMany(ItemsReviews::className(), ['item_id' => 'id'])->andOnCondition(['vis' => 1])->orderBy("date DESC");
    }
    public function innerCategoryId()
    {
        if($this->categoryData && ($categoryData = json_decode($this->categoryData, true))) {
            return $categoryData['id'];
        }
        return false;
    }
}
