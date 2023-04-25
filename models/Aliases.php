<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "custom_fields".
 *
 * @property integer $id
 * @property string $code
 * @property integer $post_id
 * @property string $class
 * @property string $value
 */
class Aliases extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'aliases';
    }

    public function rules()
    {
        return [
            [['alias'], 'required'],
            [['model'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [];
    }
}
