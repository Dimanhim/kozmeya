<?php
use yii\helpers\Html;
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>

  <table width="800" cellpadding="0" cellspacing="0" align="center" style="border:1px solid #000; font: 14px #000;">

    <thead>
    	<tr>
    		<td width="40" style="border-bottom:10px solid #3d3d3d;"></td>
	    	<td style="border-bottom:10px solid #3d3d3d;">
	    		<table>
	    			<tr>
	    				<td height="97" style="padding-right:49px;">
	    					<a href="<?=Yii::$app->params["HOST"]?>/" target="_blank"><img src="<?=Yii::$app->params["HOST"]?>/img/logo.png" alt="" width="150"  border="0" alt="" title="room"></a>
	    				</td>
	    				<td height="97" style="padding-right:60px;">
							<b style="font-size:16px;"><?=Yii::$app->params["settings"][2]?></b><br>
							<div style="font-size: 12px; margin-top: 5px;"><?=Yii::$app->params["settings"][8]?></div>
	    					<div style="font-size: 12px;margin-top: 5px"><?=Yii::$app->params["settings"][5]?></div>
	    				</td>
	    				<td height="97">
	    					<div style="background:#3d3d3d;padding:3px 6px;margin-bottom:3px;"><a target="_blank" href="<?=Yii::$app->params["HOST"]?>/dostavka" style="color:#fff;text-decoration:none;">Условия доставки</a></div>
	    					<div style="background:#3d3d3d;padding:3px 6px;"><a target="_blank" href="<?=Yii::$app->params["HOST"]?>/politika-konfedacialnosti" style="color:#fff;text-decoration:none;">Политика конфедациальности</a></div>
	    				</td>
	    			</tr>
	    		</table>
	    	</td>
	    	<td width="40" style="border-bottom:10px solid #3d3d3d;"></td>
    	</tr>
    </thead>
    <tbody>
    	<tr>
    				<td width="40" style="border-top:10px solid #3d3d3d;"></td>
	    			<td style="border-top:10px solid #3d3d3d; font-size: 12px; padding-top: 10px; padding-bottom: 10px;">

							<?= $content ?>

		    		</td>
			    	<td width="40" style="border-top:10px solid #3d3d3d;"></td>
		    	</tr>
		    </tbody>
		    <tfoot>
		    	<tr>
		    		<td width="40" style="border-top:19px solid #3d3d3d;"></td>
			    	<td style="border-top:19px solid #3d3d3d;">

			    		<table width="100%">
			    			<tbody>
			    				<tr>
			    					<td style="padding:15px 0;" width="300">
			    						С уважением, <?=Yii::$app->params["HOST"]?>
			    					</td>
			    					<td style="padding:15px 0;" width="300">
			    						<a target="_blank" href="<?=Yii::$app->params["HOST"]?>/"><img src="<?=Yii::$app->params["HOST"]?>/img/logo.png" width="150" alt=""></a>
			    					</td>
			    					<td style="padding:15px 0;" width="200">


			    					</td>
			    				</tr>
			    			</tbody>
			    		</table>

			    	</td>
			    	<td width="40" style="border-top:19px solid #3d3d3d;"></td>
		    	</tr>
		    </tfoot>
		  </table>


    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
