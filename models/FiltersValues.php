<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "filters_values".
 *
 * @property integer $id
 * @property integer $filter_id
 * @property string $name
 * @property string $alias
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property integer $vis
 * @property integer $posled
 *
 * @property Filters $filter
 * @property FiltersValuesProps[] $filtersValuesProps
 * @property Props[] $props
 */
class FiltersValues extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'filters_values';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['filter_id', 'name'], 'required'],
			[['filter_id', 'var_showtype_id', 'var_showtype_value_id', 'prop_id', 'vis', 'posled'], 'integer'],
			[['name', 'alias', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
			[['filter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Filters::className(), 'targetAttribute' => ['filter_id' => 'id']],
			[['alias'], 'unique'],
			[['alias'], 'match', 'pattern'=>'/^[-a-zA-Z0-9_\s]+$/'],
		];
	}

	public function afterSave($insert, $changedAttributes){
		parent::afterSave($insert, $changedAttributes);

        if($this->alias == "") {
            $this->alias = \Yii::$app->functions->setAlias($this->name)."-".$this->id;
        }
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			$data = Yii::$app->request->post();

			if($this->vis == "") {
				$this->vis = 0;
			}

            if($this->prop_id == "") {
                $this->prop_id = 0;
            }

			if($this->posled == "") {
				$this->posled = 999;
			}

			return true;
		}
		return false;
	}

	public function updateRelations(){}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'filter_id' => 'Фильтр',
			'var_showtype_id' => 'Модификация',
            'prop_id' => 'Характеристика для автозначений',
			'name' => 'Отображаемое значение',
			'alias' => 'Алиас',
			'h1' => 'H1',
			'meta_title' => 'Meta Title',
			'meta_description' => 'Meta Description',
			'meta_keywords' => 'Meta Keywords',
			'vis' => 'Показывать',
			'posled' => 'Сортировка',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFilter()
	{
		return $this->hasOne(Filters::className(), ['id' => 'filter_id']);
	}

	public function getVarshowtype()
	{
		return $this->hasOne(VarsShowtypes::className(), ['id' => 'var_showtype_id']);
	}

    public function getProp()
    {
        return $this->hasOne(Props::className(), ['id' => 'prop_id']);
    }

	public function getVarshowtypevalue()
	{
		return $this->hasOne(VarsShowtypesValues::className(), ['id' => 'var_showtype_value_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getValues()
	{
		return $this->hasMany(FiltersValuesProps::className(), ['filter_value_id' => 'id']);
	}
}
