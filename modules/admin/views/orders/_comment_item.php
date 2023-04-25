<div class="direct-chat-msg">
    <div class="direct-chat-info clearfix">
        <span class="direct-chat-name pull-left"><?=$comment->user->username;?></span>
        <span class="direct-chat-timestamp pull-right"><?=date("d.m.Y в H:i:s", strtotime($comment->date));?></span>
    </div>
    <img class="direct-chat-img" src="<?=($comment->user->avatar != "" ? \Yii::$app->functions->getUploadItem($comment->user, "avatar", "ra", "128x128") : "http://placehold.it/128x128");?>">
    <div class="direct-chat-text">
        <?=$comment->text;?>
    </div>
</div>