<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Items */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="model-form">

	<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs">
			<li <? if(!isset($_GET["tab"]) || $_GET["tab"] == "mains"):?>class="active"<? endif;?>><a href="#mains" data-toggle="tab" aria-expanded="true">Базовая информация</a></li>
			<li <? if(isset($_GET["tab"]) && $_GET["tab"] == "categories"):?>class="active"<? endif;?>><a href="#categories" data-toggle="tab" aria-expanded="false">Категории</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane <? if(!isset($_GET["tab"]) || $_GET["tab"] == "mains"):?>active<? endif;?>" id="mains">
				<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'class' => 'form-control nameToAlias', 'data-selector' => '.imNameAlias']) ?>

				<?= $form->field($model, 'alias')->textInput(['maxlength' => true, 'class' => 'form-control imNameAlias']) ?>

				<? if($model->isNewRecord) $model->type_id = 2;?>
				<?=$form->field($model, 'type_id')->dropDownList(yii\helpers\ArrayHelper::map(\app\models\FiltersTypes::find()->orderBy("name DESC")->all(), 'id', 'name'), ['class' => 'form-control filterTypeId']);?>

				<?=$form->field($model, 'showtype_id')->dropDownList(yii\helpers\ArrayHelper::map(\app\models\FiltersShowtypes::find()->orderBy("name DESC")->all(), 'id', 'name'), []);?>

				<?= $form->field($model, 'ext')->textInput(['maxlength' => true]) ?>

				<? if($model->isNewRecord) $model->static = 0;?>
				<?= $form->field($model, 'static')->checkbox() ?>

                <?php echo $this->render('/components/alert', ['title' => 'Подсказка', 'text' => 'Имеет ли фильтр статичный урл, в панеле фильтров будет отображаться ссылкой']); ?>

				<? if($model->isNewRecord) $model->vis = 1;?>
				<?= $form->field($model, 'vis')->checkbox() ?>

				<? if($model->isNewRecord) $model->posled = 999;?>
				<?= $form->field($model, 'posled')->textInput() ?>

				<? $labels = \app\models\FiltersValues::attributeLabels();?>
				<? $labelsSub = \app\models\FiltersValuesProps::attributeLabels();?>
				<? $props = yii\helpers\ArrayHelper::map(\app\models\Props::find()->orderBy("name DESC")->all(), 'id', 'name');?>
				<? $varsshowtype = yii\helpers\ArrayHelper::map(\app\models\VarsShowtypes::find()->orderBy("name DESC")->all(), 'id', 'name');?>
				<? $varsshowtypevalues = \app\models\VarsShowtypesValues::find()->orderBy("name DESC")->all();?>

				<hr>
				<legend>Фильтры</legend>
				<div class="values-edit-container form-inline lines-component">
					<? if($model->values): foreach($model->values as $index => $data):?>
						<div class="values-edit-row-size lines-component-row lines-component-row-parent">
							<div class="form-group">
								<input type="hidden" name="values[<?=$index;?>][id]" value="<?=$data->id;?>"/>
								<select name="values[<?=$index;?>][var_showtype_id]" class="form-control varFilterType changeVarShowtype" style="display: none;">
									<? foreach($varsshowtype as $var_showtype_id=>$var_showtype_name):?>
										<option <?=($data->var_showtype_id == $var_showtype_id ? "selected" : "");?> value="<?=$var_showtype_id;?>"><?=$var_showtype_name;?></option>
									<? endforeach;?>
								</select>
								<select name="values[<?=$index;?>][var_showtype_value_id]" class="form-control varFilterType" style="display: none;">
									<option class="varShowtypeValueDefault" value="0">Не выбрано</option>
									<? foreach($varsshowtypevalues as $k => $v):?>
										<option class="varShowtypeValue varShowtype_<?=$v->showtype_id;?>" <?=($data->var_showtype_value_id == $v->id ? "selected" : "");?> value="<?=$v->id;?>"><?=$v->name;?></option>
									<? endforeach;?>
								</select>
                                <select name="values[<?=$index;?>][prop_id]" class="form-control propFilterType" style="display: none;">
                                    <option value="0">Выбрать хар-ку для автозначений</option>
                                    <? foreach($props as $k => $v):?>
                                        <option <?=($data->prop_id == $k ? "selected" : "");?> value="<?=$k?>"><?=$v;?></option>
                                    <? endforeach;?>
                                </select>
								<input class="form-control" type="text" name="values[<?=$index;?>][name]" placeholder="<?=$labels["name"]?>" value="<?=$data->name;?>"/>
								<input class="form-control" type="text" name="values[<?=$index;?>][alias]" placeholder="<?=$labels["alias"]?>" value="<?=$data->alias;?>"/>
								<input class="form-control" type="text" name="values[<?=$index;?>][h1]" placeholder="<?=$labels["h1"]?>" value="<?=$data->h1;?>"/>
								<input class="form-control" type="text" name="values[<?=$index;?>][meta_title]" placeholder="<?=$labels["meta_title"]?>" value="<?=$data->meta_title;?>"/>
								<input class="form-control" type="text" name="values[<?=$index;?>][meta_description]" placeholder="<?=$labels["meta_description"]?>" value="<?=$data->meta_description;?>"/>
								<input class="form-control" type="text" name="values[<?=$index;?>][meta_keywords]" placeholder="<?=$labels["meta_keywords"]?>" value="<?=$data->meta_keywords;?>"/>
								<input style="width: 50px;" class="form-control" type="text" name="values[<?=$index;?>][posled]" placeholder="<?=$labels["posled"]?>" value="<?=$data->posled;?>"/>
								<label><input class="unclear" type="checkbox" name="values[<?=$index;?>][vis]" value="1" <?=($data->vis ? "checked" : "");?>/> Показывать</label>

								<div class="lines-component-sub">
									<legend>Значения</legend>
									<div class="subvalues-edit-container form-inline lines-component">
										<? if($data->values): foreach($data->values as $k => $v):?>
											<div class="subvalues-edit-row-size lines-component-row">
												<input type="hidden" name="subvalues[<?=$index;?>][id][]" value="<?=$v->id;?>"/>
												<select class="chosen" name="subvalues[<?=$index;?>][prop_id][]">
													<? foreach($props as $propid=>$propname):?>
														<option <?=($v->prop_id == $propid ? "selected" : "");?> value="<?=$propid;?>"><?=$propname;?></option>
													<? endforeach;?>
												</select>
												<input class="form-control" type="text" name="subvalues[<?=$index;?>][from_value][]" placeholder="<?=$labelsSub["from_value"]?>" value="<?=$v->from_value;?>"/>
												<input class="form-control" type="text" name="subvalues[<?=$index;?>][to_value][]" placeholder="<?=$labelsSub["to_value"]?>" value="<?=$v->to_value;?>"/>

												<? if($k != 0):?><a class="btn btn-danger removeLine"><i class="fa fa-trash"></i></a><? endif;?>
											</div>
										<? endforeach;?>
										<? else:?>
											<div class="subvalues-edit-row-size lines-component-row">
												<input type="hidden" name="subvalues[0][id][]" value=""/>
												<select class="chosen" name="subvalues[0][prop_id][]">
													<? foreach($props as $propid=>$propname):?>
														<option value="<?=$propid;?>"><?=$propname;?></option>
													<? endforeach;?>
												</select>
												<input class="form-control" type="text" name="subvalues[0][from_value][]" placeholder="<?=$labelsSub["from_value"]?>" value=""/>
												<input class="form-control" type="text" name="subvalues[0][to_value][]" placeholder="<?=$labelsSub["to_value"]?>" value=""/>
											</div>
										<? endif;?>

										<a data-selector=".subvalues-edit-row-size" data-increment="false" data-reinitchosen="true" data-evoincrement="true" class="btn btn-default addLine">Добавить значения</a>

                                        <div><small> * Поле "значение от" обязательно для заполнения</small></div>
                                        <div><small> ** Для одиночных значений поле "значение до" оставьте пустым</small></div>
									</div>
								</div>
							</div>

							<? if($index != 0):?><a class="btn btn-danger removeLine"><i class="fa fa-trash"></i></a><? endif;?>
						</div>
					<? endforeach;?>
					<? else:?>
						<div class="values-edit-row-size lines-component-row lines-component-row-parent">
							<input type="hidden" name="values[0][id]" value=""/>
							<select name="values[0][var_showtype_id]" class="form-control varFilterType changeVarShowtype" style="display: none;">
								<? foreach($varsshowtype as $var_showtype_id=>$var_showtype_name):?>
									<option value="<?=$var_showtype_id;?>"><?=$var_showtype_name;?></option>
								<? endforeach;?>
							</select>
							<select name="values[0][var_showtype_value_id]" class="form-control varFilterType" style="display: none;">
								<option class="varShowtypeValueDefault" value="0">Не выбрано</option>
								<? foreach($varsshowtypevalues as $k => $v):?>
									<option class="varShowtypeValue varShowtype_<?=$v->showtype_id;?>" value="<?=$v->id;?>"><?=$v->name;?></option>
								<? endforeach;?>
							</select>
                            <select name="values[0][prop_id]" class="form-control propFilterType" style="display: none;">
                                <option value="0">Выбрать хар-ку для автозначений</option>
                                <? foreach($props as $k => $v):?>
                                    <option value="<?=$k?>"><?=$v;?></option>
                                <? endforeach;?>
                            </select>
							<input class="form-control" type="text" name="values[0][name]" placeholder="<?=$labels["name"]?>" value=""/>
							<input class="form-control" type="text" name="values[0][alias]" placeholder="<?=$labels["alias"]?>" value=""/>
							<input class="form-control" type="text" name="values[0][h1]" placeholder="<?=$labels["h1"]?>" value=""/>
							<input class="form-control" type="text" name="values[0][meta_title]" placeholder="<?=$labels["meta_title"]?>" value=""/>
							<input class="form-control" type="text" name="values[0][meta_description]" placeholder="<?=$labels["meta_description"]?>" value=""/>
							<input class="form-control" type="text" name="values[0][meta_keywords]" placeholder="<?=$labels["meta_keywords"]?>" value=""/>
							<input style="width: 50px;" class="form-control" type="text" name="values[0][posled]" placeholder="<?=$labels["posled"]?>" value=""/>
							<label><input class="unclear" type="checkbox" name="values[0][vis]" value="1"/> Показывать</label>

							<div class="lines-component-sub">
								<legend>Значения</legend>
								<div class="subvalues-edit-container form-inline lines-component">
									<div class="subvalues-edit-row-size lines-component-row">
										<input type="hidden" name="subvalues[0][id][]" value=""/>
										<select class="chosen" name="subvalues[0][prop_id][]">
											<? foreach($props as $propid=>$propname):?>
												<option value="<?=$propid;?>"><?=$propname;?></option>
											<? endforeach;?>
										</select>
										<input class="form-control" type="text" name="subvalues[0][from_value][]" placeholder="<?=$labelsSub["from_value"]?>" value=""/>
										<input class="form-control" type="text" name="subvalues[0][to_value][]" placeholder="<?=$labelsSub["to_value"]?>" value=""/>
									</div>

									<a data-selector=".subvalues-edit-row-size" data-increment="false" data-reinitchosen="true" data-evoincrement="true" class="btn btn-default addLine">Добавить значения</a>

                                    <div><small> * Поле "значение от" обязательно для заполнения</small></div>
                                    <div><small> * Для одиночных значений поле "значение до" оставьте пустым</small></div>
								</div>
							</div>
						</div>
					<? endif;?>

					<a data-selector=".values-edit-row-size" data-increment="false" data-reinitchosen="true" data-evoincrement="true" class="btn btn-default addLine">Добавить фильтр</a>
				</div>
				<hr>
			</div>

			<div class="tab-pane <? if(isset($_GET["tab"]) && $_GET["tab"] == "categories"):?>active<? endif;?>" id="categories">
				<legend>Категории</legend>
				<?
				$categories = \app\models\Categories::hierarchy();
				$checked = [];
				if(!$model->isNewRecord && $model->categories) {
					foreach($model->categories as $k=>$v){
						$checked[$v->id] = $v->id;
					}
				}
				?>

				<?= $this->render( '/components/recursive_checkbox', ['data' => $categories,  'field' => 'name', 'inputname' => 'categories', 'checked' => $checked]); ?>
			</div>
			<!-- /.tab-pane -->
		</div>
		<!-- /.tab-content -->
	</div>



	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
