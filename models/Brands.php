<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "brands".
 *
 * @property integer $id
 * @property string $name
 * @property string $images
 * @property integer $vis
 * @property integer $posled
 */
class Brands extends \yii\db\ActiveRecord
{

    public $imagesUploader;
    public $url;
    public $section_id = 14;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brands';
    }

    public function afterFind()
    {
        //$this->url = Yii::$app->functions->hierarchyUrl(\Yii::$app->params['allPages'][$this->section_id])."/".$this->alias;

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
            $this->alias = \Yii::$app->functions->setAlias($this->name)."-".$this->id;
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
            $data = Yii::$app->request->post();

            if($alias = Aliases::find()->where(["alias" => $this->alias])->andWhere(["!=", "model", Yii::$app->functions->getModelName($this)])->one()) {
                $this->addError("alias", 'Данный алиас уже используется в другой записи');
                return false;
            }

            return true;
        }
        return false;
    }

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['images', 'alias', 'small', 'text'], 'string'],
            [['main', 'vis', 'posled'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['images'], 'safe'],
            [['images'], 'file', 'maxFiles' => 1000],
            [['alias'], 'unique'],
            [['alias'], 'match', 'pattern'=>'/^[-a-zA-Z0-9_\s]+$/'],
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
                'attribute' => 'country_id',
                'format' => 'raw',
                'value'=> function($data) { if($data->country) return $data->country->name; },
            ],
            [
                'attribute' => 'images',
                'format' => 'image',
                'value'=> function($data) { if($data->images != "") return \Yii::$app->functions->getUploadItem($data, "images", "rn", "100x100"); },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{update} {delete}'],
        ];
    }

    public function fieldsData()
    {
        return [
            'name' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'country_id' => ['type' => 'dropDownList', 'data' => yii\helpers\ArrayHelper::map(\app\models\Countries::find()->orderBy("name DESC")->all(), 'id', 'name')],
            'alias' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            //'small' => ['type' => 'textarea', 'data' => ['rows' => 6]],
            'text' => ['type' => 'textarea', 'data' => ['rows' => 6, "id" => "editor"]],
            'images' => ['type' => 'uploader', 'data' => ["name" => "imagesUploader"]],
            'main' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 0],
            'vis' => ['type' => 'checkbox', 'data' => [], 'defaultIsNew' => 1],
            'posled' => ['type' => 'textInput', 'data' => ['maxlength' => true], 'defaultIsNew' => 999],
            '_customfields' => ['type' => 'customfields'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_id' => 'Страна',
            'alias' => 'Алиас',
            'small' => 'Кратко',
            'text' => 'Описание',
            'name' => 'Название',
            'images' => 'Фото',
            'imagesUploader' => 'Фото',
            'main' => 'Показывать на главной',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
        ];
    }

    public function getBrands($subs)
    {
        $return = [];

        if ( count($subs) > 0 )
        {
            $brands = Brands::find()
                        ->joinWith('items.categories')
                        ->where("brands.vis = '1' AND brands.id > 0 AND categories.id  IN (".implode(',', $subs).")")->orderBy("brands.name DESC")
                        ->groupBy("brands.id")
                        ->all();

            foreach ($brands as $v) $return[] = $v;
        }

        return $return;
    }

    public function getCountries($brands)
    {
        $return = [];
        $brandsIds = [];
        if ( count($brands) > 0 ) {
            foreach ($brands as $k => $v) {
                $brandsIds[$v->id] = $v->id;
            }
        }

        if ( count($brandsIds) > 0 )
        {
            $countries = Countries::find()
                    ->joinWith('brands')
                    ->where("brands.vis = '1' AND brands.id IN (".implode(',', $brandsIds).")")->orderBy("countries.name DESC")
                    ->groupBy("countries.id")
                    ->all();

            foreach ($countries as $v) $return[] = $v;
        }

        return $return;
    }

    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['id' => 'country_id']);
    }

    public function getItems()
    {
        return $this->hasMany(Items::className(), ['brand_id' => 'id']);
    }
}
