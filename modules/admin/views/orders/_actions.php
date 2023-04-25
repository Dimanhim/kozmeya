<div>
    <hr>

    <a href="/admin/orders/bill?id=<?=$model->id;?>" data-pjax="0" class="btn btn-default"><i class="fa fa-barcode"></i> Чек</a>
    <a class="btn btn-default orderPaymentLink" data-id="<?=$model->id;?>"><i class="fa fa-credit-card"></i> Отправить ссылку на оплату <input id="orderPaymentLink-<?=$model->id;?>" type="hidden" value=""></a>
</div>