<div class="free__serv">
    <div class="container">
        <form action="#" class="form-ajax">
            <input type="hidden" name="Форма" value="Вызвать замерщика">
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

            <div class="row">
                <div class="col-md-4">
                    <div class="white__icon"><i class="icon-fr"></i>
                    </div>
                    <div class="fr__serv-info"> <em>Бесплатная услуга</em>
                        <h2>Вызвать замерщика</h2>
                        <p>Наш специалист приедет в удобное для вас время, произведет замер и поможет подобрать подходящий вам вариант</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-box">
                        <input type="text" placeholder="Имя" name="Имя" class="required">
                    </div>
                    <div class="form-box">
                        <input type="email" placeholder="E-mail" name="E-mail" class="required">
                    </div>
                    <div class="form-box"><span class="float-text">Телефон</span>
                        <input type="text" placeholder="+7 (" class="phoneJs required" name="Телефон">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-box">
                        <textarea placeholder="Сообщение" name="Сообщение" class="required"></textarea>
                    </div>
                    <div class="form-box">
                        <button type="submit" class="send__dtn fright"><span class="red__btn red__btn-arrow">отправить  </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>