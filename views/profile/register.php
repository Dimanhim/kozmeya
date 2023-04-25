

<div class="header_top">
    <div class="container">
        <div class="page-title d-flex align-items-center">
            <div><?=Yii::$app->langs->t("Вход / регистрация");?></div>
        </div>
    </div>
</div><!--.header_top-->

<div class="container reg">
    <div class="row auth_row">
        
        <div class="col-lg-5">
            
            <div class="h4">
                <?=Yii::$app->langs->t("Регистрация");?>:                
            </div>
            
            <div class="privancy-policy">
                <?=Yii::$app->langs->t("Пожалуйста, зарегистрируйтесь, чтобы создать учетную запись.<br>Все поля являются обязательными для заполнения.");?>
            </div>

            <form method="post" action="" id="registerForm">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

                <div class="form-group">
                    <label><?=Yii::$app->langs->t("Страна");?></label>
                    <select name="country" onmousedown="if(this.options.length>5){this.classList.add('active');this.size=5;}"  onchange='this.size=5;' onblur="this.size=5;">
                        <? foreach (\app\models\Deliverycountries::find()->where("vis = 1")->orderBy("posled")->all() as $k=>$v):?>
                        <option value="<?=Yii::$app->langs->modelt($v, "name");?>"><?=Yii::$app->langs->modelt($v, "name");?></option>
                        <? endforeach;?>
                    </select>
                </div>
                <div class="form-group">
                    <label><?=Yii::$app->langs->t("Имя");?></label>
                    <input class="form-control" required type="text" onkeyup="preventDigits(this);" name="name" placeholder="">
                </div>
                <div class="form-group">
                    <label><?=Yii::$app->langs->t("Фамилия");?></label>
                    <input class="form-control" type="text" onkeyup="preventDigits(this);" name="last_name" placeholder="">
                </div>
                <div class="form-group">
                    <label><?=Yii::$app->langs->t("Телефон");?></label>
                    <input class="form-control" type="text" id="phone" name="phone" placeholder="">
                </div>
                <div class="form-group">
                    <label><?=Yii::$app->langs->t("Электронная почта");?> <small id="valid"></small></label>
                    <input class="form-control" required type="text" id="email" name="email" placeholder="">
                </div>
                <div class="form-group">
                    <label><?=Yii::$app->langs->t("Пароль");?></label>
                    <div class="position-relative">
                        <input class="form-control" required type="password" id="passwd_input" name="password" placeholder=""> 
                        <span class="show-pass" id="show_passwd"><?=Yii::$app->langs->t("Показать");?></span>                   
                    </div>
                </div>
                
                <div class="privancy-policy">
                    <?=Yii::$app->langs->t("Создавая учетную запись, Вы соглашаетесь с условиями нашей");?>
                    <a href="#"><?=Yii::$app->langs->t("Политики конфиденциальности");?>.</a>
                </div>                

                <div class="registr-btn-wrap">
                    <button type="submit" class="btn btn-dark btn-wm"><?=Yii::$app->langs->t("Регистрация");?></button>
                </div>
            </form>
        </div>

        <div class="col-lg-2 d-none d-xl-block d-lg-block">
            <div class="form-separator"></div>
        </div>

        <div class="col-lg-5">
            <div class="h4"><?=Yii::$app->langs->t("Вход");?>:</div>

            <form id="loginForm">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                
                <div class="form-group">
                    <input class="form-control" type="login" name="SiteLoginForm[username]" placeholder="<?=Yii::$app->langs->t("Адрес электронной почты");?>">
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="SiteLoginForm[password]" placeholder="<?=Yii::$app->langs->t("Пароль");?>">
                </div>

                <div class="d-sm-flex  flex-sm-row-reverse justify-content-center justify-content-md-end text-center">                    
                    <button type="submit" class="btn btn-dark btn-wm login_btn"><?=Yii::$app->langs->t("Войти");?></button>                

                    <div class="confirm-pass">
                        <a href="#" class="login_pre-text"><?=Yii::$app->langs->t("Забыли пароль?");?></a>
                    </div>
                </div>

            </form>
        </div>

    </div>
</div>
