<? if(isset($_GET['import_file'])):?>
    <? if(isset($_GET['msg'])) foreach($_GET['msg'] as $msg):?>
        <div style="color: <? if($msg['type'] == 'error') echo 'red'; else echo 'green';?>;"><?=$msg['value'];?></div>
    <? endforeach;?>

    <div>Обновлено: <?=$_GET['update'];?></div>
    <div>Добавлено: <?=$_GET['insert'];?></div>
<? endif;?>

<form action="/admin/tags/import" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

    <div class="form-group">
        <label for="" class="control-label">Импорт через файл (xls, xlsx)</label>

                <span class="container_files">
                    <div class="controls cdfiles">
                        <input type="file" name="Tags[file]">
                    </div>
                </span>
    </div>
    <button type="submit" class="btn btn-success">Загрузить</button>
    <small>
        <div>A - URL</div>
        <div>B - Алиас</div>
        <div>C - URL страницы размещения</div>
        <div>D - Название</div>
        <div>E - Категория</div>
        <div>F - Title</div>
        <div>G - Description</div>
        <div>H - Текст</div>
        <div>I - Сортировка</div>
        <div>J - Показывать</div>
    </small>
</form>