<? if(isset($viewGrid) && is_array($viewGrid)):?>
    <div class="row">
        <div class="col-md-offset-10 col-md-2">
            <div class="pull-right">
                <span>Вид:</span>
                <? foreach($viewGrid as $viewGridId => $viewGridData):?>
                    <a class="btn btn-success <?=($view == $viewGridId ? "active" : "");?>" href="?view=<?=$viewGridId;?>"><i class="<?=$viewGridData["class"];?>"></i></a>
                <? endforeach;?>
            </div>
        </div>
    </div>
<? endif;?>