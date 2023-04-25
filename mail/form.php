<table width="50%">
    <? foreach($postData as $name=>$value): if($value != ''): $name = str_replace("_", " ", $name);?>
        <tr>
            <td><?=$name;?></td>
            <td><?=$value;?></td>
        </tr>
    <? endif; endforeach;?>
</table>