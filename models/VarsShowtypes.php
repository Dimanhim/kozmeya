<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vars_showtypes".
 *
 * @property integer $id
 * @property string $name
 *
 * @property ItemsVars[] $itemsVars
 */
class VarsShowtypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vars_showtypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function updateRelations(){\Yii::$app->langs->saveTranslates($this);}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemsVars()
    {
        return $this->hasMany(ItemsVars::className(), ['showtype_id' => 'id']);
    }

    public function getValues()
    {
        return $this->hasMany(VarsShowtypesValues::className(), ['showtype_id' => 'id']);
    }
}
