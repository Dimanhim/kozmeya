var body;

$(function() {
	body = $('body');



	function mobileBody() {
        if($(window).width() < 767) {
            body.addClass('mobile-body').removeClass('desktop-body')
        }
        else {
            body.removeClass('mobile-body').addClass('desktop-body')
        }
    }

    mobileBody();

	$(window).resize(function() {
	    mobileBody()
    })

    $('body').on('click', '.main_menu > ul > li > a', function(e) {
        e.preventDefault();
        let next = $(this).next();
        if(next.hasClass('submenu')) {
            next.addClass('submenu-opened')
        }
    });

	$('.slick-product').slick({
        //infinite: true,
        //slidesToShow: 1,
        dots: true,
        arrows: false,
        mobileFirst: true,
        //prevArrow: false,
        //nextArrow: false,
    })
	$('body').on('click', '.cgood-btn', function(e) {
	    e.preventDefault();
	    $('.cookies-banner').fadeOut()
	});
	//ddd

	body.on('change', 'select.ddd', function(){
		if ($(this).val() != 1) {

			$('.dadr').show();
			$('.pickup_d').hide();
		} else {
			$('.pickup_d').show();
			$('.dadr').hide();
		}
	});

	body.on('click', '.cgood', function(){
        $.post("/ajax/cgood", {v:1}, function (data) {

        }, "json");
	});
	//cgood

	body.on('click', '.sub', function(){
		$(this).closest( "form" ).submit();
	});

    body.on('submit', '.pjaxFilters', function(){
        var form = $(this);
		form.submit();
		/*
        $.pjax({
            url:form.attr('action') +'?'+ form.serialize(),
            container: form.data("grid"),
            scrollTo: false,
        });*/

        return false;
    });

    body.on('click', '.goTo', function(){
        window.location.replace($(this).attr("href"));
        return false;
    });

    body.on('click', '.goSubmit', function(){
        $(this).closest('form').submit();
    });

    body.on('change', '.goSubmitOnChange', function(){
        $(this).closest('form').submit();
    });

    body.on('change', '.reloadByValue', function(){
        window.location.replace($(this).val());
        return false;
    });

    var filesCount = 1;
    var url = '/ajax/upload';
    if($('.fileupload').length > 0){
        $('.fileupload').each(function(){
            var $this = $(this);
            $this.fileupload({
                url: url,
                dataType: 'json',
                done: function (e, data) {

                    $.each(data.result.files, function (index, file) {
                        $($this.data("files")).append("<input type='hidden' name='Файл "+filesCount+"' value='"+file+"'><div>"+file+"</div>");
                        filesCount = filesCount+1;
                    });
                }
            }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');
        });
    }

    body.on('submit', 'form.form-ajax', function(){
        var form = $(this);
        var error = validateForm(form);
        console.log('error', error)

        if(!error){
            $.ajax({
                url: '/ajax/form',
                data: form.serializeArray(),
                success: function(data){
                    if(data.success){
                        $('input:not(:submit)', form).val('');
                        $('textarea', form).val('');

                        $("#feedback-modal").modal("hide");
                        $("#thanks-modal").modal("show");
                        alertify.success(data.msg);

                    }
                    else {
                        alertify.error(data.error);

                    }
                },
                type: "POST", dataType: "json"
            });
        }

        return false;
    });


    body.on('submit', 'form.addComment', function(){
        var form = $(this);
        var error = validateForm(form);

        if(!error){
            $.ajax({
                url: '/ajax/addcomment',
                data: form.serializeArray(),
                success: function(data){
                    if(data.success){
                        $('input:not(:submit)', form).val('');
                        $('textarea', form).val('');

                        alertify.success(data.msg);
                    }
                    else {
                        alertify.error(data.error);

                    }
                },
                type: "POST", dataType: "json"
            });
        }

        return false;
    });

    body.on("submit", ".addItemReview", function(){
        var form = $(this);
        var error = validateForm(form);

        if(!error) {
            $.post("/ajax/additemreview", form.serializeArray(), function (data) {
                if (data.success) {
                    alertify.success(data.msg);
                    $(".clearVal", form).val("");
                }
                else {
                    alertify.error(data.error);
                }
            }, "json");
        }

        return false;
    });

    body.on("submit", ".addReview", function(){
        var form = $(this);
        var error = validateForm(form);

        if(!error) {
            $.post("/ajax/addreview", form.serializeArray(), function (data) {
                if (data.success) {
                    alertify.success(data.msg);
                    $(".clearVal", form).val("");
                }
                else {
                    alertify.error(data.error);
                }
            }, "json");
        }

        return false;
    });


    body.on("click", ".getItemPhotos", function(){
        var $this = $(this);
        $.post("/ajax/getitemphotos", $this.data(), function(data){
            if(data.success){
                $(".ajaxItemPhotos").html(data.html);
            }
            else {
                alertify.error(data.error);
            }
        }, "json");

        return false;
    });

    body.on("click", ".setItemSize", function () {
        $(".setItemSizeInput").prop("checked", false);
        $(".setItemSizeInput.setItemSizeInput_"+$(this).data("value")).prop("checked", true);

        $('#size_modal').modal('toggle');

        return false;
    });

    /* Langs */
    body.on("click", ".changeLang", function(){
        var $this = $(this);
        $.post("/ajax/changelang", $this.data(), function(data){
            if(data.success){
                location.reload();
            }
        }, "json");

        return false;
    });
    /* End Langs */


    /* Profile */
    body.on("submit", "#loginForm", function(){
        var form = $(this);
        $.post("/profile/login", form.serializeArray(), function(data){
            if(data.success){
                alertify.success(data.msg);
                if($("#registerForm").length){

                    window.location.replace("/cart?step=3");
                }
                else {
                    window.location.replace("/profile");
                }
            }
            else {
                alertify.error(data.error);
            }
        }, "json");

        return false;
    });

    body.on("submit", "#registerForm", function(){
        var form = $(this);
        $.post("/profile/register", form.serializeArray(), function(data){
            if(data.success){
                alertify.success(data.msg);
                if($("#orderForm").length) {
                    window.location.replace("/cart?step=3");
                }
                else {
                    //window.location.replace("/profile");
                    window.location = "/profile";
                }
            }
            else {
                alertify.error(data.error);
            }
        }, "json");

        return false;
    });

    body.on("submit", "#repairForm", function(){
        var form = $(this);
        $.post("/profile/repair", form.serializeArray(), function(data){
            if(data.success){
                alertify.success(data.msg);
            }
            else {
                alertify.error(data.error);
            }
        }, "json");

        return false;
    });

    /* End profile */

    body.on("keyup", ".iSearch", function(){
        var $this = $(this);
        var val = $this.val();

        $(".iSearchResults").remove();

        if(val.length > 2) {
            $.post("/ajax/isearch", {query: val}, function (data) {
                var tmpl = "";
                $.each(data, function(index, item){
                    tmpl += '<div class="table"><div class="row">';
                    if(!!item.image) {
                        tmpl += '<div class="td"><div class="image"><img src="' + item.image + '" /></div></div>';
                    }
                    tmpl += '<div class="td text"><div class="tmpl"><a href="'+item.link+'">' + item.name + '</a></div></div>';

                    if(!!item.price) {
                        tmpl += '<div class="td"><div class="price-search">' + item.price + '</div></div>';
                    }

                    tmpl += '</div></div>';
                    return tmpl;
                });

                if(tmpl == "") tmpl = "Nothing founded";

                $this.closest('.iSearchBlock').append('<div class="iSearchResults" style="position: absolute; left: 0; top: 45px; width: 100%; display: block;"><div class="results"><div class="group"><div class="group-items"><div class="result">'+tmpl+'</div></div><div class="clearfix"></div></div></div></div>');

            }, "json");
        }
    });


    body.on("change", ".changeVar", function(){
        var $this = $(this);
        itemVars($this);

        return false;
    });

    if($(".changeVar").length > 0) {
        $(".changeVar:checked").each(function(){
            var $this = $(this);

            itemVars($this);
        });
    }

    /* Favorites */
    body.on("click", ".addToFav", function(){

        var $this = $(this);
        var icon = $("i", $this);

        $.post("/ajax/favorites", $this.data(), function(data){


            if(data.success){
                $(".imFavorites").html(data.html);
                $('.favCount').html(data.count);



                alertify.success(data.msg);

                if($this.hasClass("inFav") && icon.hasClass("active")) {
                    $this.closest(".itemRow").remove();
                }

                icon.toggleClass("active");

                console.log(123);

            }
            else {
                alertify.error(data.error);
            }
        }, "json");

        return false;
    });

    /* End favorites */

    /* Compare */
    body.on("click", ".addToCompare", function(){
        var $this = $(this);
        $.post("/ajax/compares", $this.data(), function(data){
            if(data.success){
                $(".imCompares").html(data.html);
                alertify.success(data.msg);
            }
            else {
                alertify.error(data.error);
            }
        }, "json");

        return false;
    });

    /* End Compare */

    /* Cart */
    body.on('click', '.repeatOrder', function(){
        var $this = $(this);

        $.post('/ajax/repeatorder/', $this.data(), function(data){
            if(data.success) {
                $('.imCart').html(data.html);
                $('.cartCount').html(data.count);

                alertify.success(data.msg);
                $this.remove();
            }
            else {
                alertify.error(data.error);
            }
        }, "json");

        return false;
    });

    body.on('submit', '.addToCartForm', function(){
        var form = $(this);
        submitLoading($('[type="submit"]', form), true);
        $.post('/ajax/cart', form.serializeArray(), function(data){
            if(data.success) {
                $('.imCart').html(data.html);
                $('.cartCount').html(data.count);

                submitLoading($('[type="submit"]', form), false);
                alertify.success(data.msg);
            }
            else {
                alertify.error(data.error);
            }
        }, "json");

        return false;
    });

    body.on('change', '.changeCartQty', function(){
        var $this = $(this);
        var parent = $this.closest('.itemBlock');

        $.post('/ajax/changecart', {id: $this.data("id"), qty: $this.val()}, function(data){
            if(data.success) {
                $('.imCart').html(data.html);
                $('.cartCount').html(data.count);

                $('.cartPrices').html(data.cart_prices);
                $('.cartPricesMain').html(data.cart_prices_main);

                $('.itemPrice', parent).html(data.item_price);
                $('.itemOldPrice', parent).html(data.item_old_price);

                $(".changeDeliveries:checked").change();
            }
            else {
                alertify.error(data.error);
            }
        }, "json");


        return false;
    });

    body.on("click", ".changeCartBtn", function(){
        var parent = $(this).closest(".changeCartQtyBlock");
        $(".changeCartQty", parent).change();
    });

    body.on("change", ".changePayments", function(){
        $(".paymentsBlock").hide();
        $(".paymentsBlock_"+$(this).val()).show();
    });

    if($(".changePayments").length > 0) {
        $(".changePayments").first().prop("checked", true).change();
    }

    body.on("change", "select.deliveryCountry, select.ddd, select.deliveryCities, .deliveryCity", function(){
	    var dtype = $("select.ddd option:selected").val();
	    var another_city = $(".deliveryCity").val();
        var another_city = $(".deliveryCity").val();
        var price = parseFloat($("select.deliveryCities option:selected").data("price"));
        if(price == 0 || another_city != "") {
            price = parseFloat($("select.deliveryCountry option:selected").data("price"));
        }

        $.post('/ajax/getdeliveryprice', {price: price, dtype: dtype}, function(data){
            $('.cartPrices').html(data.cart_prices);
            $('.cartPricesMain').html(data.cart_prices_main);

            $('.deliveryPrice').val(data.price);
            $('.deliveryPriceText').html(data.price);
        }, "json");
    });

    body.on("change", "select.deliveryCountry", function(){
        var country_id = parseInt($("select.deliveryCountry option:selected").data("id"));

        $(".cityOption").hide();
        $(".cityOption.cityCountry_"+country_id).show();
    });

    if($(".deliveryCountry").length > 0) {
        $(".deliveryCountry").change();
    }


    body.on("change", ".changeDeliveries", function(){
        $(".deliveriesBlock").hide();
        $(".paymentdelivery").hide();

        $(".deliveriesBlock_"+$(this).val()).show();
        $(".paymentdelivery_"+$(this).val()).show();

        $.post('/ajax/changedeliveries', {id: $(this).val()}, function(data){
            if(data.success) {
                $('.cartPrices').html(data.cart_prices);
                $('.cartPricesMain').html(data.cart_prices_main);

                $('.deliveryPrice').val(data.price);
                $('.deliveryPriceText').html(data.price);
            }
            else {
                alertify.error(data.error);
            }
        }, "json");
    });

    if($(".changeDeliveries").length > 0) {
        $(".changeDeliveries").first().prop("checked", true).change();
    }

    body.on("submit", ".setPromocode", function(){
        var form = $(this);

        $.post('/ajax/promocode', form.serializeArray(), function(data){
            if(data.success) {
                $('.cartPrices').html(data.cart_prices);
                $('.cartPricesMain').html(data.cart_prices_main);

                alertify.success(data.msg);
            }
            else {
                alertify.error(data.error);
            }
        }, "json");

        return false;
    });

    body.on("submit", "#orderForm", function(){
        var form = $(this);
        var error = validateForm(form);

        if(!error) {
            return true;
        }

        return false;
    });
    /* End cart */
});

/* Functions */
function itemVars($this){
    var parent = $this.closest(".itemRow");

    if($this.data("price") != undefined) {
        if(parseInt($this.data("price")) > 0) {
            $(".priceBlock", parent).show();
        }
        else {
            $(".priceBlock", parent).hide();
        }

        $(".itemPrice", parent).text(numberFormat($this.data("price"), "", " ", " "));
    }

    if($this.data("image") != undefined && $this.data("image") != "") {
        $(".itemImage").attr("src", $this.data("image"));
    }
}

function arrFind(array, search){
    var find = false;

    $.each(array, function(index, value){
        if(value == search) {
            find = index;
        }
    });

    return find;
}

function validateForm(form){
    var error = false;
    message = $('.form-message', form);

    if(message.length == 0){
        var message = $('.form-message', form.parent());
    }

    $('.required', form).removeClass('form-error');
    $('.required', form).removeClass('form-success');



    $('.required', form).each(function(){
        var type = $(this).attr('type');

        if((type == 'text' || type == 'email' || type == 'tel' || type == 'password') && $(this).val() == '') {
            error = true;
            $(this).addClass('form-error');
        }
        else {
            $(this).addClass('form-success');
        }

        if((type == 'radio' || type == 'checkbox') && !$(this).prop('checked')) {
            error = true;
            $(this).addClass('form-error');
        }
        else {
            $(this).addClass('form-success');
        }


        if($(this).hasClass("required-if-not")) {
            var $ifselector = $($(this).data("selector"));
            var $ifvalue = $(this).data("value");
            $ifvalue = $ifvalue.split(',');

            if(arrFind($ifvalue, $ifselector.val()) !== false){

            }
            else {

            }
        }
    });

    if(error) {
        $(".form-error", form).first().focus();
        alertify.error('Fill in all required fields');
    }

    return error;
}

function submitLoading(el, init){
	el.prop('disabled', init);
	var text = el.html();

	if(init){
		el.addClass('loading');
		el.text('Загрузка...');
		el.data('text', text);
	}
	else {
		el.removeClass('loading');
		el.html(el.data('text'));
	}
}

function numberFormat( number, decimals, dec_point, thousands_sep )
{

    var i, j, kw, kd, km;


    if( isNaN(decimals = Math.abs(decimals)) ){
        decimals = 2;
    }
    if( dec_point == undefined ){
        dec_point = ",";
    }
    if( thousands_sep == undefined ){
        thousands_sep = ".";
    }

    i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

    if( (j = i.length) > 3 ){
        j = j % 3;
    } else{
        j = 0;
    }

    km = (j ? i.substr(0, j) + thousands_sep : "");
    kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);

    kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


    return km + kw + kd;
}

function t($value){
    if(_t[$value] != undefined) {
        return _t[$value];
    }

    return $value;
}
