<?php

namespace app\controllers;

use app\models\Cart;
use app\models\CartVars;
use app\models\Categories;
use app\models\Deliveries;
use app\models\Items;
use app\models\ItemsReviews;
use app\models\Leads;
use app\models\Promocodes;
use app\models\Reviews;
use app\models\Subscribes;
use yii\db\Expression;
use Yii;
use yii\helpers\Json;

class AjaxController extends \app\components\Controller
{

	public $postData = [];
	public $getData = [];

	public function init(){
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		if (!Yii::$app->request->isAjax) {
			throw new \yii\web\NotFoundHttpException;
		}

		$this->postData = Yii::$app->request->post();
		$this->getData = Yii::$app->request->get();

		$this->enableCsrfValidation = false;

		parent::init();
	}

	public function actionCgood()
	{
		$_SESSION['cgood'] = 1;
	}

	public function actionForm()
	{

		$model = new Leads();

		$success = false;
		$msg = Yii::$app->langs->t("Сообщение отправлено!");
		$error = Yii::$app->langs->t("Произошла ошибка");
		$repeattoclient = false;
		$subscribe = false;
        $subscribemodel = "";

		unset($this->postData["_csrf"]);

		if(isset($this->postData["_toadmin"])) {
			$to = explode(",", Yii::$app->params["adminEmail"]);
		}
		else {
			$to = explode(",", Yii::$app->params["settings"][1]);
		}

		if(isset($this->postData["_toclient"]) && isset($this->postData["E-mail"]) && $this->postData["E-mail"] != ""){
			$repeattoclient = true;
		}

        if(isset($this->postData["_subscribe"]) && isset($this->postData["E-mail"]) && isset($this->postData["_subscribe_model"]) && $this->postData["E-mail"] != ""){
            $subscribe = true;
            $subscribemodel = $this->postData["_subscribe_model"];
        }

		unset($this->postData["_toadmin"]);
		unset($this->postData["_toclient"]);
        unset($this->postData["_subscribe"]);
        unset($this->postData["_subscribe_model"]);



        //file_put_contents('info-log.txt', date('d.m.Y H:i:s').'$this->postData - '.print_r($this->postData, true)."\n", FILE_APPEND);
        //file_put_contents('info-log.txt', date('d.m.Y H:i:s').' supportEmail - '.print_r(Yii::$app->params['supportEmail'], true)."\n", FILE_APPEND);
        //file_put_contents('info-log.txt', date('d.m.Y H:i:s').' host - '.print_r(Yii::$app->params['HOST'], true)."\n", FILE_APPEND);
        //file_put_contents('info-log.txt', date('d.m.Y H:i:s').' to - '.print_r($to, true)."\n", FILE_APPEND);
        //file_put_contents('info-log.txt', date('d.m.Y H:i:s').' Yii::$app->mailer - '.print_r(Yii::$app->mailer, true)."\n", FILE_APPEND);
        $sendResult = Yii::$app->mailer->compose(['html' => 'form'],['postData' => $this->postData])
            ->setTo($to)
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['HOST']])
            ->setSubject($this->postData["Form"] . " " . Yii::$app->params['HOST'])
            ->send();
        //info@maniamodeler.com
        //file_put_contents('info-log.txt', date('d.m.Y H:i:s').'$sendResult - '.print_r($sendResult, true)."\n", FILE_APPEND);
		if($sendResult)
		{
			$success = true;

			if($repeattoclient) {
				Yii::$app->mailer->compose(['html' => 'form'],['postData' => $this->postData])
					->setTo($this->postData["E-mail"])
					->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['HOST']])
					->setSubject($this->postData["Form"] . " " . Yii::$app->params['HOST'])
					->send();
			}

			if($subscribe) {
			    $subscribes = new Subscribes();
			    $subscribes->email = $this->postData["E-mail"];
			    $subscribes->date = date("Y-m-d H:i:s");
			    $subscribes->model = $subscribemodel;
			    $subscribes->active = 1;
			    $subscribes->save();

            }

			$model->date = date("Y-m-d H:i:s");
			$model->form_data = Json::encode($this->postData);
			$model->save();
		}

		$result = ['success' => $success, 'msg' => $msg, 'error' => $error];
		file_put_contents('info-log.txt', date('d.m.Y H:i:s').' result - '.print_r($result, true)."\n", FILE_APPEND);
		return $result;
	}

	public function actionUpload()
	{
		$success = false;
		$error = '';
		$msg = Yii::$app->langs->t('Файл загружен');

		$files = [];

		$targetPath = \Yii::$app->basePath.'/'.Yii::$app->params['uploadDir'].'/'.Yii::$app->params['uploadUserDir'];

		if (!is_dir($targetPath))
		{
			@mkdir($targetPath, 0777);
		}

		if (isset($_FILES['files']['name']) && is_array($_FILES['files']['name'])) {

			foreach($_FILES['files']['name'] as $index=>$name){
				$tmp = $_FILES['files']['tmp_name'][$index];

				$salt = rand(0, 9999);
				while (true) {
					$aboutFile = pathinfo($name);
					$name = sha1($salt.$aboutFile['filename']).'.'.$aboutFile['extension'];
					if (!file_exists($targetPath."/".$name)) break;
				}

				if(move_uploaded_file($tmp, $targetPath.'/'.$name )) {
					$files[] = Yii::$app->params['HOST'].Yii::$app->params['uploadDir'].'/'.Yii::$app->params['uploadUserDir'].'/'.$name;
					$success = true;
				}
			}
		}

		return ['files' => $files, 'success' => $success, 'error' => $error, 'msg' => $msg];

	}

	public function actionAddcomment()
	{
		$success = false;
		$msg = Yii::$app->langs->t("Комментарий отправлен!");
		$error = Yii::$app->langs->t("Произошла ошибка");

		if(isset($this->postData["pid"], $this->postData["post_id"], $this->postData["model"], $this->postData["text"])){
			if(\Yii::$app->db->createCommand('INSERT INTO `comments`(`post_id`, `model`, `pid`, `text`, `name`, `email`) VALUES (:post_id,:model,:pid,:text,:name,:email);')
				->bindValue(':post_id', $this->postData["post_id"])
				->bindValue(':model', $this->postData["model"])
				->bindValue(':pid', $this->postData["pid"])
				->bindValue(':text', $this->postData["text"])
				->bindValue(':name', $this->postData["name"])
				->bindValue(':email', $this->postData["email"])
				->execute()){

				$success = true;
			}
		}


		return ['success' => $success, 'msg' => $msg, 'error' => $error];
	}

	public function actionOpencard()
	{
		$html = "";

		if(isset($this->getData["id"]) && $this->getData["id"] != ""){
			$item = Items::findOne($this->getData["id"]);

			if($item) {
				$html = $this->renderPartial("/catalog/parts/fast_item", ["item" => $item]);
			}
		}


		return $html;
	}

	public function actionGetitemphotos()
	{
		$html = "";
		$success = false;
		$msg = "";
		$error = Yii::$app->langs->t("Произошла ошибка");

		if(isset($this->postData["id"]) && $this->postData["id"] != ""){
			$item = Items::findOne($this->postData["id"]);

			if($item) {
				$html = $this->renderPartial("/catalog/parts/photos", ["item" => $item]);
				$success = true;
			}
		}


		return ['success' => $success, 'msg' => $msg, 'error' => $error, 'html' => $html];
	}

    public function actionRepeatorder(){
        $html = '';
        $success = false;
        $msg = "";
        $error = Yii::$app->langs->t("Произошла ошибка");

        if(isset($this->postData["id"])) {
            if($model = Orders::findOne($this->postData["id"])) {
                foreach ($model->items as $item){
                    $vars = [];
                    if($item->vars) foreach ($item->vars as $var) {
                        $vars[$var->id] = $var->id;
                    }

                    $add = Yii::$app->catalog->addToCart($item->item_id, $item->qty, $vars);
                    if($add["success"]) {
                        $msg = "Added to cart";
                        $success = true;
                    }
                    else {
                        $error = $add["error"];
                    }
                }
            }
        }

        if($success) {
            $this->initCart();
            $html = $this->renderPartial("/parts/cart", []);
        }


        return ['success' => $success, 'msg' => $msg, 'error' => $error, 'html' => $html];
    }

	public function actionCart(){
		$html = '';
		$success = false;
		$msg = "";
		$error = Yii::$app->langs->t("Произошла ошибка");

		if(isset($this->postData["method"])) {
			$this->postData["qty"] = (isset($this->postData["qty"]) && $this->postData["qty"] > 0 ? (int) $this->postData["qty"] : 1);

			if($this->postData["method"] == "truncate") {
				if(Cart::findOne(['sid' => \Yii::$app->params['cartSID']])->delete()){
					$success = true;
					$msg = Yii::$app->langs->t("Товары удалены");
				}
			}
			else {
				if(isset($this->postData["id"]) && $this->postData["id"] != "") {
					if ($this->postData["method"] == "add") {
						$add = Yii::$app->catalog->addToCart($this->postData["id"], $this->postData["qty"], (isset($this->postData["vars"]) ? $this->postData["vars"] : []), (isset($this->postData["color"]) ? $this->postData["color"] : ""), (isset($this->postData["size"]) ? $this->postData["size"] : ""));
						if($add["success"]) {
                            $msg = "Added to cart";
                            $success = true;
                        }
                        else {
                            $error = $add["error"];
                        }
					}
					elseif ($this->postData["method"] == "delete") {
						if(Cart::findOne(['id' => $this->postData["id"], 'sid' => \Yii::$app->params['cartSID']])->delete()){
							$success = true;
							$msg = Yii::$app->langs->t("Товар удален");
						}
					}
				}

			}

		}

		if($success) {
			$this->initCart();
			$html = $this->renderPartial("/parts/cart", []);
		}


		return ['success' => $success, 'count' => count(\Yii::$app->params['cart']["items"]), 'msg' => $msg, 'error' => $error, 'html' => $html];
	}

	public function actionChangecart()
	{
		$html = '';
		$cart_prices = '';
		$cart_prices_main = '';

		$success = false;
		$msg = "";
		$error = Yii::$app->langs->t("Произошла ошибка");

		if(isset($this->postData["id"], $this->postData["qty"]) && $this->postData["id"] != "" && $this->postData["qty"] > 0) {
			$cart = Cart::find()->where(["id" => $this->postData["id"]])->one();
			if($cart) {
				$cart->qty = (int) $this->postData["qty"];
				if($cart->save()){
					$success = true;
				}
			}
		}

		if($success) {
			$this->initCart();
			$html = $this->renderPartial("/parts/cart", []);
			$cart_prices = $this->renderPartial("/catalog/parts/cart_prices", ['main' => false]);
			$cart_prices_main = $this->renderPartial("/catalog/parts/cart_prices", ['main' => true]);

			$item_price = \Yii::$app->params['cart']['cartPrice'][$cart->id]*$cart->qty;
			$item_old_price = $cart->item->old_price*$cart->qty;
		}

		return [
			'success' => $success,
			'msg' => $msg,
			'error' => $error,
			'html' => $html,
			'cart_prices' => $cart_prices,
			'cart_prices_main' => $cart_prices_main,
			'item_price' => $item_price,
			'item_old_price' => $item_old_price,
		];
	}

	public function actionChangedeliveries()
	{
		$price = 0;
		$cart_prices = '';
		$cart_prices_main = '';

		$success = false;
		$msg = "";
		$error = Yii::$app->langs->t("Произошла ошибка");

		if(isset($this->postData["id"]) && $this->postData["id"] != "") {
			$price = \Yii::$app->catalog->deliveryPrice($this->postData["id"]);
			if($price !== false) {
				$success = true;
			}
		}

		if($success) {
			$this->initCart();

			\Yii::$app->params['cart']["delivery_price"] = $price;

			$cart_prices = $this->renderPartial("/catalog/parts/cart_prices", ['main' => false]);
			$cart_prices_main = $this->renderPartial("/catalog/parts/cart_prices", ['main' => true]);
		}

		return [
			'success' => $success,
			'msg' => $msg,
			'error' => $error,
			'cart_prices' => $cart_prices,
			'cart_prices_main' => $cart_prices_main,
			'price' => $price,
		];
	}

	public function actionPromocode()
	{
		$cart_prices = '';
		$cart_prices_main = '';

		$success = false;
		$msg = "";
		$error = Yii::$app->langs->t("Произошла ошибка");

		if(isset($this->postData["code"]) && $this->postData["code"] != "") {
			$promocode = Promocodes::find()->where(["name" => $this->postData["code"], "vis" => 1])->one();
			if($promocode) {
				if($promocode->unlimited || (strtotime($promocode->date_from) < time() && strtotime($promocode->date_to) > time())) {
					$conditionSuccess = true;

                    if($promocode->users != "") {
                        $conditionSuccess = false;

                        $users = array_filter(explode(",", $promocode->users));
                        foreach ($users as $id) {
                            if(!Yii::$app->siteuser->isGuest && Yii::$app->siteuser->getId() == $id) {
                                $conditionSuccess = true;
                            }
                        }
                    }

                    if($conditionSuccess && $promocode->categories != "") {
                        $conditionSuccess = false;

                        $categories = array_filter(explode(",", $promocode->categories));

                        foreach ($categories as $id) {
                            foreach(\Yii::$app->params['cart']['items'] as $k=>$v) {
                                if($v->item->_categoryData->id == $id) {
                                    $conditionSuccess = true;
                                }
                            }
                        }
                    }

                    if($conditionSuccess && $promocode->brands != "") {
                        $conditionSuccess = false;

                        $brands = array_filter(explode(",", $promocode->brands));

                        foreach ($brands as $id) {
                            foreach(\Yii::$app->params['cart']['items'] as $k=>$v) {
                                if($v->item->brand_id == $id) {
                                    $conditionSuccess = true;
                                }
                            }
                        }
                    }

                    if($conditionSuccess && $promocode->items != "") {
                        $conditionSuccess = false;

                        $items = array_filter(explode(",", $promocode->items));

                        foreach ($items as $id) {
                            foreach(\Yii::$app->params['cart']['items'] as $k=>$v) {
                                if($v->item->id == $id) {
                                    $conditionSuccess = true;
                                }
                            }
                        }
                    }

					if($conditionSuccess && $promocode->conditions) {
						foreach($promocode->conditions as $condition) {
							$conditionvalue = \Yii::$app->params['cart']['price'];
							if($condition->type == "result_count"){
								$conditionvalue = \Yii::$app->params['cart']['qty'];
							}

							$ifcondition = "return ".$conditionvalue.$condition->condition.$condition->value.";";

							if(!eval($ifcondition)) {
								$conditionSuccess = false;
							}
						}
					}



					if($conditionSuccess) {
						\Yii::$app->session["promocode"] = $promocode->name;
						\Yii::$app->session["discount_value"] = $promocode->value;
						\Yii::$app->session["discount_type"] = $promocode->type;

						$success = true;

						$msg = Yii::$app->langs->t("Промо-код применен!");
					}
					else {
						$error = Yii::$app->langs->t("Промо-код не совпадает с условиями");
					}

				}
				else {
					$error = Yii::$app->langs->t("Промо-код больше не актуален");
				}

			}
			else {
				$error = Yii::$app->langs->t("Промо-код не найден");
			}
		}

		if($success) {
			$this->initCart();

			$cart_prices = $this->renderPartial("/catalog/parts/cart_prices", ['main' => false]);
			$cart_prices_main = $this->renderPartial("/catalog/parts/cart_prices", ['main' => true]);
		}

		return [
			'success' => $success,
			'msg' => $msg,
			'error' => $error,
			'cart_prices' => $cart_prices,
			'cart_prices_main' => $cart_prices_main,
		];
	}

	public function actionCompares()
	{
		$html = '';
		$success = false;
		$msg = "";
		$error = Yii::$app->langs->t("Произошла ошибка");

		if(isset($this->postData["id"], $this->postData["method"]))
		{
			if($this->postData["method"] == "add") {
				if(!isset(\Yii::$app->session["compares"][$this->postData["id"]])) {
					$compares = \Yii::$app->session["compares"];
					$compares[$this->postData["id"]] = $this->postData["id"];
					\Yii::$app->session["compares"] = $compares;

					$success = true;
					$msg = "Added to wishlist!";
				}
				else {
					$error = "Already exists in wishlist";
				}
			}
			elseif($this->postData["method"] == "delete"){
				$compares = \Yii::$app->session["compares"];
				unset($compares[$this->postData["id"]]);
				\Yii::$app->session["compares"] = $compares;

				$success = true;
				$msg = "Removed from wishlist!";
			}

		}

		if($success) {
			$this->initCompares();
			$html = $this->renderPartial("/parts/compare", []);
		}


		return ['success' => $success, 'msg' => $msg, 'error' => $error, 'html' => $html];
	}

	public function actionFavorites()
	{
		$html = '';
		$success = false;
		$msg = "";
		$error = Yii::$app->langs->t("Произошла ошибка");

		if(isset($this->postData["id"], $this->postData["method"]))
		{
			if($this->postData["method"] == "add") {
				if(!isset(\Yii::$app->session["favorites"][$this->postData["id"]])) {
					$favorites = \Yii::$app->session["favorites"];
					$favorites[$this->postData["id"]] = $this->postData["id"];
					\Yii::$app->session["favorites"] = $favorites;

					$success = true;
					$msg = "Added to wishlist!";
				}
				else {
                    $favorites = \Yii::$app->session["favorites"];
                    unset($favorites[$this->postData["id"]]);
                    \Yii::$app->session["favorites"] = $favorites;

                    $success = true;
                    $msg = "Removed from wishlist!";
				}
			}
			elseif($this->postData["method"] == "delete"){
				$favorites = \Yii::$app->session["favorites"];
				unset($favorites[$this->postData["id"]]);
				\Yii::$app->session["favorites"] = $favorites;

				$success = true;
				$msg = "Removed from wishlist!";
			}

		}

		if($success) {
			$this->initFavorites();
			$html = $this->renderPartial("/parts/favorites", []);
		}


		return ['success' => $success, 'count' => count(\Yii::$app->session["favorites"]), 'msg' => $msg, 'error' => $error, 'html' => $html];
	}

	public function actionSitemap()
	{
		$success = true;
		$msg = Yii::$app->langs->t("Карта сайта сгенерирована!");
		$error = Yii::$app->langs->t("Произошла ошибка");

		$sitemap = $this->siteMap();

		file_put_contents(\Yii::$app->params["PATH"].'/sitemap.xml', $sitemap['xml']);

		return ['success' => $success, 'msg' => $msg, 'error' => $error];
	}

	public function actionAdditemreview()
	{
		$success = false;
		$msg = Yii::$app->langs->t("Отзыв добавлен и скоро появится на сайте!");
		$error = Yii::$app->langs->t("Произошла ошибка");

		if(isset($this->postData["item_id"], $this->postData["name"], $this->postData["text"]) && $this->postData["item_id"] != "" && $this->postData["email"] != "" && $this->postData["text"] != "") {
			$model = new ItemsReviews();
			$model->item_id = (int) $this->postData["item_id"];
			$model->email = strip_tags($this->postData["email"]);
			$model->name = strip_tags($this->postData["name"]);
			$model->rating = (int) $this->postData["score"];
			$model->text = strip_tags($this->postData["text"]);
			$model->vis = 0;

			if($model->save()) {
				$to = explode(",", Yii::$app->params["settings"][1]);

				if(Yii::$app->mailer->compose(['html' => 'text'],['text' => Yii::$app->langs->t("На сайте новый отзыв о товаре ID").$model->item_id])
					->setTo($to)
					->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['HOST']])
					->setSubject(Yii::$app->langs->t("Новый отзыв о товаре") . " " . Yii::$app->params['HOST'])
					->send())
				{
					$success = true;
				}
			}
		}




		return ['success' => $success, 'msg' => $msg, 'error' => $error];
	}

	public function actionAddreview()
	{
		$success = false;
		$msg = Yii::$app->langs->t("Отзыв добавлен и скоро появится на сайте!");
		$error = Yii::$app->langs->t("Произошла ошибка");

		if(isset($this->postData["name"], $this->postData["text"]) && $this->postData["email"] != "" && $this->postData["text"] != "") {
			$model = new Reviews();
			$model->email = strip_tags($this->postData["email"]);
			$model->name = strip_tags($this->postData["name"]);
			$model->rating = (int) $this->postData["score"];
			$model->text = strip_tags($this->postData["text"]);
			$model->vis = 0;

			if($model->save()) {
				$to = explode(",", Yii::$app->params["settings"][1]);

				if(Yii::$app->mailer->compose(['html' => 'text'],['text' => Yii::$app->langs->t("На сайте новый отзыв")])
					->setTo($to)
					->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->params['HOST']])
					->setSubject(Yii::$app->langs->t("Новый отзыв") . " " . Yii::$app->params['HOST'])
					->send())
				{
					$success = true;
				}
			}
		}




		return ['success' => $success, 'msg' => $msg, 'error' => $error];
	}

	public function actionIsearch()
	{
		$result = [];

		$this->postData['query'] = preg_replace('/\s\s+/', ' ', trim($this->postData['query']));
		$queryFormat = Yii::$app->functions->latToCyr($this->postData['query']);

		$queryFormatSql = " OR categories.name LIKE ".Yii::$app->db->quoteValue('%'.$queryFormat.'%')." OR categories.text LIKE ".Yii::$app->db->quoteValue('%'.$queryFormat.'%')."";

		$querySql = " categories.vis = '1' AND (categories.name LIKE ".Yii::$app->db->quoteValue('%'.$this->postData['query'].'%')."
		OR categories.text LIKE ".Yii::$app->db->quoteValue('%'.$this->postData['query'].'%').$queryFormatSql.") ";

		$items = Categories::find()->where($querySql)->limit(20)->all();
		foreach($items as $k=>$v)
		{
			$result[] = ['name' => $v->name, 'link' => Yii::$app->catalog->categoryUrl($v->id), 'image' => ($v->images != "" ? \Yii::$app->functions->getUploadItem($v, "images", "fx", "50x50") : "")];
		}


		$queryFormatSql = " OR items.name LIKE ".Yii::$app->db->quoteValue('%'.$queryFormat.'%')." OR brands.name LIKE ".Yii::$app->db->quoteValue('%'.$queryFormat.'%')." OR categories.name LIKE ".Yii::$app->db->quoteValue('%'.$queryFormat.'%')." OR items.text LIKE ".Yii::$app->db->quoteValue('%'.$queryFormat.'%')."";
		$querySql = " items.vis = '1' AND (items.name LIKE ".Yii::$app->db->quoteValue('%'.$this->postData['query'].'%')." OR brands.name LIKE ".Yii::$app->db->quoteValue('%'.$this->postData['query'].'%')." OR categories.name LIKE ".Yii::$app->db->quoteValue('%'.$this->postData['query'].'%')." OR items.text LIKE ".Yii::$app->db->quoteValue('%'.$this->postData['query'].'%').$queryFormatSql.") ";


		$items = Items::find()->joinWith(["categories", "brand"])->where($querySql)->limit(20)->all();

		foreach ($items as $k=>$v)
		{
			$price = \Yii::$app->catalog->itemPrice($v);

			$result[] = ['name' => $v->name, 'link' => Yii::$app->catalog->itemUrl($v), 'price' => \Yii::$app->catalog->viewPrice($v, "price", "", "", $price["price"]), 'image' => ($v->images != "" ? \Yii::$app->functions->getUploadItem($v, "images", "fx", "50x50") : "")];
		}

		return $result;
	}

	public function actionChangelang()
	{
		$success = false;

		if (isset($this->postData['lang']) && $this->postData['lang'] != "") {
			\Yii::$app->session["lang"] = $this->postData['lang'];
			$success = true;
		}

		return ['success' => $success];
	}

    public function actionGetdeliveryprice()
    {
        if (isset($this->postData['price'])) {
            \Yii::$app->params['cart']["delivery_price"] = $this->postData['price'];
        }

        if ( $this->postData['dtype'] == 1 ) {
	        \Yii::$app->params['cart']["delivery_price"] = 0;
        }

        return ['cart_prices' => $this->renderPartial( '/catalog/parts/cart_prices', ['main' => true] ), 'cart_prices_main' => $this->renderPartial( '/catalog/parts/cart_prices', ['main' => true] ), 'price' => \Yii::$app->params['cart']["delivery_price"]];
    }
}
