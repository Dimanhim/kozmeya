<div class="error-page">
    <h2 class="headline text-yellow"> <?=$exception->statusCode;?></h2>

    <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i>
            <? if($exception->statusCode == 403):?>
                Ошибка доступа к странице, проверьте права доступа для данного пользователя
            <? else:?>
                Страница не найдена
            <? endif;?>
        </h3>

        <p>
            Вернуться на <a href="/admin">главную</a>
        </p>
    </div>
    <!-- /.error-content -->
</div>
<!-- /.error-page -->