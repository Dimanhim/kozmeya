<div class="box box-success direct-chat direct-chat-success <? if($collapsed):?>collapsed-box<? endif;?> addOrderCommentContainer">
    <div class="box-header with-border">
        <h3 class="box-title">Комментарии</h3>

        <div class="box-tools pull-right">
            <span data-toggle="tooltip" title="" class="badge bg-green" data-original-title="<?=count($model->comments);?> комм."><?=count($model->comments);?></span>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-<?=($collapsed ? "plus" : "minus");?>"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="direct-chat-messages addOrderCommentsItems">
            <? if($model->comments) foreach($model->comments as $comment):?>
                <?=$this->render("/orders/_comment_item", ['comment' => $comment]);?>
            <? endforeach;?>
        </div>
    </div>
    <? if($add):?>
    <div class="box-footer">
        <div class="input-group">
            <input type="text" name="text" placeholder="Комментарий" class="form-control addOrderComment_text_<?=$model->id;?>">
            <span class="input-group-btn"><button type="button" data-order_id="<?=$model->id;?>" class="btn btn-success btn-flat addOrderComment">Добавить</button></span>
        </div>
    </div>
    <? endif;?>
</div>