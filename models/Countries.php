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
class Countries extends \yii\db\ActiveRecord
{

    public $imagesUploader;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countries';
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
            [['name'], 'required'],
            [['images', 'alias'], 'string'],
            [['vis', 'posled'], 'integer'],
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
            'alias' => ['type' => 'textInput', 'data' => ['maxlength' => true]],
            'images' => ['type' => 'uploader', 'data' => ["name" => "imagesUploader"]],
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
            'name' => 'Название',
            'alias' => 'Алиас',
            'images' => 'Фото',
            'imagesUploader' => 'Фото',
            'vis' => 'Показывать',
            'posled' => 'Сортировка',
        ];
    }

    public function getBrands()
    {
        return $this->hasMany(Brands::className(), ['country_id' => 'id']);
    }
}
