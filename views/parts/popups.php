
<? if (empty($_SESSION['cgood'])): ?>
<div id="kuki-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="text-right">
                <span data-dismiss="modal" class="modal-close"></span>
            </div>

            <div class="modal-body">
                
                <div class="thanks-modal-content">                  
                  <div class="thanks-modal-text">Мы используем на своем сайте файлы cookie. Если вы продолжаете использовать сайт, то мы будем считать, что вас это устраивает</div>
                </div>

                <div class="text-center">
                  <a href="#" class="btn btn-dark btn-w-m cgood" data-dismiss="modal">Хорошо</a>
                </div>

            </div>
        </div>
    </div>
</div>
<? endif; ?>

<div id="feedback-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="margin: 10px auto 0 auto;">
            <div class="text-right">
                <span data-dismiss="modal" class="modal-close"></span>
            </div>

            <div class="modal-body">
                <div class="modal_title"><?=Yii::$app->langs->t("Заполните форму, и мы перезвоним к вам в ближайшее время");?></div>
                <form id="feedback-form" class="form-ajax">
                    <input type="hidden" name="Форма" value="Свяжитесь с нами">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group position-relative">                                
                                <input type="text" 
                                       class="form-control" 
                                       name="name"                                       
                                       placeholder="<?=Yii::$app->langs->t("Ваше имя*");?>"
                                       data-mess="введите свое имя">
                            </div>

                            <div class="form-group position-relative">                                
                                <input type="tel" 
                                       class="form-control" 
                                       name="phone" 
                                       id="tel"                                        
                                       placeholder="<?=Yii::$app->langs->t("Номер телефона*");?>" 
                                       data-mess="введите номер телефона">
                            </div>

                            <div class="form-group position-relative">                                
                                <input type="email" 
                                       class="form-control" 
                                       name="email"                                       
                                       placeholder="<?=Yii::$app->langs->t("Адрес электронной почты");?>">
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">                                
                                <textarea class="form-control" name="message" placeholder="<?=Yii::$app->langs->t("Напишите здесь пожелания");?>"></textarea>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row align-items-end">
                        <div class="col-lg-4 offset-lg-4">
                            <div class="text-center">
                                <button type="submit" class="btn btn-dark"><?=Yii::$app->langs->t("Отправить заявку");?></button>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="required-area-mess">
                                *Обязательные поля
                            </div>
                        </div>
                    </div>                    
                    
                </form>

                <div class="form-error-mess">
                    В одном или нескольких похях есть ошибки. Пожалуйста, проверьте и отправьте сообщение снова
                </div>
            </div>
        </div>
    </div>
</div>

<div id="thanks-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="text-right">
                <span data-dismiss="modal" class="modal-close"></span>
            </div>

            <div class="modal-body">
                
                <div class="thanks-modal-content">
                  <div class="thanks-modal-title">БОЛЬШОЕ СПАСИБО!</div>
                  <div class="thanks-modal-text">НАШ МЕНЕДЖЕР ПЕРЕЗВОНИТ ВАМ В БЛИЖАЙШЕЕ ВРЕМЯ</div>
                </div>

                <div class="text-center">
                  <a href="#" class="btn btn-dark btn-w-m" data-dismiss="modal">ЗАКРЫТЬ</a>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="black" id="black" style="display: none;"></div>