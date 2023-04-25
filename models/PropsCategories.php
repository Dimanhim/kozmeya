<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "props_categories".
 *
 * @property integer $prop_id
 * @property integer $category_id
 */
class PropsCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'props_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prop_id', 'category_id'], 'required'],
            [['prop_id', 'category_id'], 'integer'],
        ];
    }

    public function updateRelations(){}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prop_id' => 'Prop ID',
            'category_id' => 'Category ID',
        ];
    }
}
