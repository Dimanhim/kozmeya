<? if(isset($_GET['import_file'])):?>
    <? if(isset($_GET['msg'])) foreach($_GET['msg'] as $msg):?>
        <div style="color: <? if($msg['type'] == 'error') echo 'red'; else echo 'green';?>;"><?=$msg['value'];?></div>
    <? endforeach;?>

    <div>Обновлено: <?=$_GET['update'];?></div>
    <div>Добавлено: <?=$_GET['insert'];?></div>
<? endif;?>

<form action="/admin/redirects/import" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

    <div class="form-group">
        <label for="" class="control-label">Импорт через файл (xls, xlsx)</label>

                <span class="container_files">
                    <div class="controls cdfiles">
                        <input type="file" name="Redirects[file]">
                    </div>
                </span>
    </div>
    <button type="submit" class="btn btn-success">Загрузить</button>
    <small>
        <div>A - url старый (вида /section)</div>
        <div>B - url новый (вида /section)</div>
    </small>
</form>