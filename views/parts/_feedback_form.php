<div class="modal_title"><?=Yii::$app->langs->t("Заполните форму, и мы перезвоним к вам в ближайшее время");?></div>
<form id="feedback-form" class="form-ajax">
    <input type="hidden" name="Form" value="Contact us">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

    <div class="row">
        <div class="col-md-6">
            <div class="form-group position-relative">
                <input type="text"
                       class="form-control"
                       name="name"
                       placeholder="<?=Yii::$app->langs->t("Ваше имя");?>*"
                       data-mess="Enter your name">
            </div>

            <div class="form-group position-relative">
                <input type="tel"
                       class="form-control"
                       name="phone"
                       id="tel"
                       placeholder="<?=Yii::$app->langs->t("Номер телефона");?>*"
                       data-mess="Enter phone number">
            </div>

            <div class="form-group position-relative">
                <input type="email"
                       class="form-control"
                       name="email"
                       placeholder="<?=Yii::$app->langs->t("Адрес электронной почты");?>*">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <textarea class="form-control" name="message" placeholder="<?=Yii::$app->langs->t("Напишите здесь пожелания");?>"></textarea>
            </div>
        </div>
    </div>

    <div class="row align-items-end">
        <div class="col-lg-4">
            <div class="">
                <button type="submit" class="btn btn-dark"><?=Yii::$app->langs->t("Отправить заявку");?></button>
            </div>
        </div>
        <!--
        <div class="col-lg-4">
            <div class="required-area-mess">
                *Обязательные поля
            </div>
        </div>
        -->
    </div>

</form>
