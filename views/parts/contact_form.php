<form action="#" class="form-ajax">
    <input type="hidden" name="Форма" value="Свяжитесь с нами">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

    <div class="tag-elem">Есть вопросы?</div>
    <h3 class="h-3">

        Свяжитесь с нами
    </h3>
    <p>Наш специалист свяжется с вами в ближайшее время</p>
    <div class="form-box">
        <input type="text" placeholder="Имя" name="Имя" class="required">
    </div>
    <div class="form-box">
        <input type="email" placeholder="E-mail" name="E-mail" class="required">
    </div>
    <div class="form-box"><span class="float-text">Телефон</span>
        <input type="text" placeholder="+7 (" class="phoneJs required" name="Телефон">
    </div>
    <div class="form-box ta-c">
        <button type="submit" class="send__dtn"><span class="red__btn red__btn-arrow">отправить </span>
        </button>
    </div>
</form>