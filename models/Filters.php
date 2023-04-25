<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "filters".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type_id
 * @property integer $showtype_id
 * @property string $ext
 * @property integer $vis
 * @property integer $posled
 *
 * @property FiltersShowtypes $showtype
 * @property FiltersTypes $type
 * @property FiltersCategories[] $filtersCategories
 * @property Categories[] $categories
 * @property FiltersValues[] $filtersValues
 */
class Filters extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'filters';
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

	public function updateRelations(){
		$data = Yii::$app->request->post();

		\Yii::$app->db->createCommand()->delete('filters_categories', ['filter_id' => $this->id,])->execute();

		if(isset($data["categories"]) && count($data["categories"]) > 0) {
			$index = 0;foreach($data["categories"] as $category_id){ $index++;
				$categoriestoitems = new FiltersCategories();
				$categoriestoitems->category_id = $category_id;
				$categoriestoitems->filter_id = $this->id;
				$categoriestoitems->save();
			}
		}

		\Yii::$app->db->createCommand()->delete('filters_values', ['filter_id' => $this->id,])->execute();

		if(isset($data["values"])) {
			foreach($data["values"] as $index => $values) {
				$valuesModel = new FiltersValues();
				$valuesModel->filter_id = $this->id;

				foreach($values as $field => $value) {
					$valuesModel->{$field} = $value;
				}

				if($this->type_id != 4) $valuesModel->var_showtype_id = 0;

				if($valuesModel->save()) {
					if($this->type_id == 2) {
						if(isset($data["subvalues"][$index]["from_value"])) {
							foreach($data["subvalues"][$index]["from_value"] as $subindex => $from_value) {
								if($from_value != "") {
									$subvaluesModel = new FiltersValuesProps();
									$subvaluesModel->filter_value_id = $valuesModel->id;
									$subvaluesModel->id = $data["subvalues"][$index]["id"][$subindex];
									$subvaluesModel->prop_id = $data["subvalues"][$index]["prop_id"][$subindex];
									$subvaluesModel->from_value = $from_value;
									$subvaluesModel->to_value = $data["subvalues"][$index]["to_value"][$subindex];

									$subvaluesModel->save();
								}

							}
						}
					}
				}
			}
		}
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
				'attribute' => 'type_id',
				'format' => 'raw',
				'value'=> function($data) { return $data->type->name; },
			],
			[
				'attribute' => 'showtype_id',
				'format' => 'raw',
				'value'=> function($data) { return $data->showtype->name; },
			],
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
	public function rules()
	{
		return [
			[['name', 'type_id', 'showtype_id'], 'required'],
			[['type_id', 'showtype_id', 'static', 'vis', 'posled'], 'integer'],
			[['name', 'ext'], 'string', 'max' => 255],
			[['showtype_id'], 'exist', 'skipOnError' => true, 'targetClass' => FiltersShowtypes::className(), 'targetAttribute' => ['showtype_id' => 'id']],
			[['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FiltersTypes::className(), 'targetAttribute' => ['type_id' => 'id']],
			[['alias'], 'unique'],
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
			'name' => 'Название',
			'alias' => 'Алиас',
			'type_id' => 'Тип',
			'showtype_id' => 'Тип отображения',
			'static' => 'Статичный фильтр',
			'ext' => 'Ед.изм (Для слайдеров)',
			'vis' => 'Показывать',
			'posled' => 'Сортировка',
			'categories' => 'Категории',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getShowtype()
	{
		return $this->hasOne(FiltersShowtypes::className(), ['id' => 'showtype_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getType()
	{
		return $this->hasOne(FiltersTypes::className(), ['id' => 'type_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFiltersCategories()
	{
		return $this->hasMany(FiltersCategories::className(), ['filter_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategories()
	{
		return $this->hasMany(Categories::className(), ['id' => 'category_id'])->viaTable('filters_categories', ['filter_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getValues()
	{
		return $this->hasMany(FiltersValues::className(), ['filter_id' => 'id']);
	}
}
