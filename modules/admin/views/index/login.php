<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="login-box-body">
    <p class="login-box-msg">Необходима авторизация</p>

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(false) ?>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'password')->passwordInput()->label(false) ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <?= $form->field($model, 'rememberMe')->checkbox(["label" => "Запомнить меня"]) ?>
                    </label>
                </div>
            </div>

            <div class="col-xs-4">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>
    <?php ActiveForm::end(); ?>
</div>