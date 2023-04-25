var body;

$(function(){
	//$.material.init();
	body = $("body");
	
	if($("#refreshButton").length){
		setInterval(function(){ $("#refreshButton").click(); }, 60000);
	}

    body.on("click", ".updateNStay", function(){
        var form = $(this).closest("form");
        $(".updateNStayInput", form).val(1);

        form.submit();

        return false;
    });

	body.on("change", ".nameToAlias", function(){
		var alias = $($(this).data("selector"));
		if(alias.val() == "") alias.val(transliterate($(this).val()));
	});

	if($('.between-slider').length > 0) $('.between-slider').bootstrapSlider();

	$( "input[type='number']" ).spinner();

	initChosen();

	if($( ".sortable").length) {
		$( ".sortable" ).sortable({
			connectWith: ".connectedSortable"
		});

		$( ".sortable" ).disableSelection();
	}

	if($(".yandexMapPoints").length) {
        $(".yandexMapPoints").each(function () {
            yandexMapPoints($(this))
        });
    }

	if($('.date-mask').length) $('.date-mask').inputmask('99.99.9999', { 'placeholder': 'dd.mm.yyyy' });
    if($('.time-mask').length) $('.time-mask').inputmask('99:99:99', { });
    if($('.time-range-mask').length) $('.time-range-mask').inputmask('99:99:99-99:99:99', { });
	if($('.phone-mask').length) $('.phone-mask').inputmask('+7 (999) 999 99-99', {  });

	if($('.daterangepicker-input').length) {
        $('.daterangepicker-input').each(function () {
            var dates = $(this).val().split("/");

            $(this).daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: '/',
                    applyLabel: 'Применить',
                    cancelLabel: 'Отмена',
                },
                startDate: dates[0],
                endData: dates[1],
            });
        });

	}

	if($('.colorpicker').length) $('.colorpicker').colorpicker();

	if($('.daterangepicker-input-time').length) $('.daterangepicker-input-time').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' });

	if($("#editor").length > 0) {
		initEditor('editor');
	}

	if($("._editor").length > 0) {
		$("._editor").each(function () {
			initEditor($(this).attr("id"));
		});
	}

	function initEditor($id) {
		CKEDITOR.replace( $id,
			{
				toolbar : [
					{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
					{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
					{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
					'/',
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
					{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
					{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
					{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
					'/',
					{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
					{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
					{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
					{ name: 'others', items: [ '-' ] },
					{ name: 'about', items: [ 'About' ] }
				],
				toolbarGroups : [
					{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
					{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
					{ name: 'forms' },
					'/',
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
					{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
					{ name: 'links' },
					{ name: 'insert' },
					'/',
					{ name: 'styles' },
					{ name: 'colors' },
					{ name: 'tools' },
					{ name: 'others' },
					{ name: 'about' }
				],
				fullPage : false,
				filebrowserBrowseUrl : '/elfinder/elfinder-cke.html',
				enterMode : Number(1),
				allowedContent: true,
			});
	}

	body.on('submit', 'form.form-ajax', function(){
		var error = false,
			form = $(this),
			message = $('.form-message', form);

		if(message.length == 0){
			var message = $('.form-message', form.parent());
		}

		$('.required', form).removeClass('form-error');
		$('.required', form).removeClass('form-success');

		$('.required', form).each(function(){
			if($(this).val() == '') {
				error = true;
				$(this).addClass('form-error');
			}
			else {
				$(this).addClass('form-success');
			}
		});

		if(!error){
			$.ajax({
				url: '/ajax/form',
				data: form.serializeArray(),
				success: function(data){
					if(data.success){
						$('button:not(:submit)', form).val('');
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
		else {
			alertify.error('Заполните обязательные поля');
		}

		return false;
	});

	body.on("click", ".showMe", function(){
		var $this = $(this);
		$($this.data("selector")).toggle();
		$this.toggleClass("active");
		if($this.hasClass("active")) {
			$this.text($this.data("activetext"));
		}
		else {
			$this.text($this.data("text"));
		}

		return false;
	});

	body.on("click", ".generateSitemap", function(){
		$.post("/ajax/sitemap", function(data){
			if(data.success){
				alertify.success(data.msg);
			}
			else {
				alertify.error(data.error);
			}
		}, "json");

		return false;
	});

	if($('.fileupload').length > 0){
		body.on("click", ".fileuploadClick", function(){
			$(this).parent().find('.fileupload').click();
			return false;
		});

		$('.fileupload').each(function(){
			var $this = $(this);
			var itemsBlock = $this.closest(".uploader-custom-items-block");
			var resultBlock = $(".uploader-custom-items", itemsBlock);

			$this.fileupload({
				url: "/admin/ajax/modelloadfile",
				dataType: 'json',
				done: function (e, data) {
					resultBlock.append(data.result.html);
				}
			}).prop('disabled', !$.support.fileInput)
				.parent().addClass($.support.fileInput ? undefined : 'disabled');
		});
	}

	body.on("click", ".deleteParent", function(){
		$(this).closest($(this).data("parent")).remove();
		return false;
	});

	if($(".addLine").length > 0){
		$(".addLine").each(function(){
			reformatAddliner($(this));
		});
	}

	body.on("click", ".addLine", function(){
		$(".chosen").chosen('destroy');

		var selector = $(this).data('selector');
		var parent = $(selector).parent();

		var clone = $(selector+':last', parent).last().clone();
		clone.find('input:not(.unclear)').val('');
		clone.find('.removeLine').remove();
		clone.find('.line-remove').remove();

		if($(this).data('incnumbers') == true) {
			var current = parseInt($('.incnumbers-row').text());
			current++;
			clone.find('.incnumbers-row').text(current);
		}
		if($(this).data('increment') == true) {
			clone.find('input').each(function(){
				var name = $(this).attr("name");
				var found = name.match(/\[(\d+)\]/);
				if(found[1] != undefined){
					var newIndex = parseInt(found[1])+1000;
					$(this).attr("name", name.replace(/\[(\d+)\]/, "["+newIndex+"]"));
				}
			});
		}


		clone.append('<a class="btn btn-danger removeLine" href="#"><i class="fa fa-trash"></i></a>');
		$(this).before(clone);

		reformatAddliner($(this));

		if($(this).data("reinitchosen") != undefined && $(this).data("reinitchosen") == true) initChosen();

		return false;
	});

	function reformatAddliner($this){
		if($this.data('evoincrement') != undefined && $this.data('evoincrement') == true) {
			$(".lines-component-row-parent").each(function(index){
				var line = $(this);
				$("input, textarea, select", line).each(function(){
					var input = $(this);
					var attrname = input.attr("name");

					if(attrname != undefined && attrname != "") {
						var reformatName = attrname.replace(/\[(\d+)\]/, "["+(index+1)+"]");
						input.attr("name", reformatName);
					}
				});
			});
		}


	}

	body.on('click', '.removeLine', function(){
		$(this).parent().remove();
		return false;
	});

	if($(".filterTypeId").length > 0) {
		filterType($(".filterTypeId").val());

		body.on("change", ".filterTypeId", function(){
			filterType($(this).val());
		});
	}

	if($(".changeVarShowtype").length > 0) {
		$(".changeVarShowtype").each(function () {
			varShowType($(this));
		});



		body.on("change", ".changeVarShowtype", function(){
			varShowType($(this), true);
		});
	}

	function varShowType($this, $change){
		var parent = $this.closest(".lines-component-row");

		$(".varShowtypeValue", parent).hide();
		$(".varShowtypeValue.varShowtype_"+$this.val(), parent).show();

		if($change) {
			$(".varShowtypeValueDefault", parent).prop("selected", true);
			$(".varShowtypeValue", parent).closest("select").val(0);
		}
	}

    function filterType(val){
        if(val != 2) {
            $(".lines-component-sub").hide();
        }
        else {
            $(".lines-component-sub").show();
        }

        $(".varFilterType").hide();
        $(".propFilterType").hide();

        if(val == 4) {
            $(".varFilterType").show();
        }

        if(val == 2) {
            $(".propFilterType").show();
        }
    }

	body.on("click", ".loadItems:not(.loaded)", function () {
		var $this = $(this);
		var $data = $this.data();
		var parent = $this.closest(".loadItemsContainer");
		var itemsBlock = $(".loadItemsBlock", parent);

		$.post("/admin/items/getitems", $data, function (data) {
			$this.addClass("loaded");
			itemsBlock.html(data.html);
		}, "json");
	});

	body.on("click", ".checkAll", function(){
		$($(this).data("selector")).prop("checked", true);

		return false;
	});

	if($(".select2").length) $(".select2").select2();

	$(".mindselect").each(function(){
		var $this = $(this);
		$this.select2(
			{
				placeholder: "",
				minimumInputLength: 2,
				ajax: {
					url: '/admin/ajax/mindsearch',
					dataType: 'json',
					data: function (term, page) {
						return {
							q: term,
							page_limit: 50,
							model: $this.attr('model'),
							fields: $this.attr('fields'),
							searchfields: $this.attr('searchfields'),
						};
					},
					results: function (data, page)
					{
						return {results: data.results};
					},
					cache: true
				},
				formatResult: function (data) {
					return mindFormatResult(data);
				},
				formatSelection: function(data){
					return mindFormatSelection(data);
				},
				escapeMarkup: function (markup) { return markup; }
			});

		function mindFormatResult(row)
		{
			var fields = $.parseJSON($this.attr('fields'));
			var html = row[fields[1]];

			return html;
		}

		function mindFormatSelection(row)
		{
			var fields = $.parseJSON($this.attr('fields'));
			var parent = $this.closest('.mindSearchBlock');
			var block   = $('.mindSearchResult', parent);

			if(row[ fields[0]] != undefined && row[ fields[0]] != "") {
				var input   = $('.hiddenMindSearch', parent).val( row[ fields[0] ] );
				block.html( row[ fields[1] ] + '<a class="btn btn-danger removeMindSearchValue" href="#"><i class="fa fa-trash"></i></a>' );
			}

		}
	});

	body.on("click", ".removeMindSearchValue", function(){
		var $this = $(this);
		var parent = $this.closest('.mindSearchBlock');
		var block   = $('.mindSearchResult', parent);

		var input   = $('input[type="hidden"]', parent).val(0);
		block.html("");

		return false;
	});

	body.on("change", ".fastChange", function(){
		var $this = $(this);
		var $data = $this.data();
		$data['value'] = $this.val();
		$data['field'] = $this.attr("name");

		$.post("/admin/ajax/fastchange", $data, function(data){
			if(data.success){
				alertify.success(data.msg);
				$.pjax.reload({container:"#indexGrid"});
			}
			else {
				alertify.error(data.error);
			}
		}, "json");

		return false;
	});

	if($(".orderAddItems").length) {
		var $this = $(".orderAddItems");
		$this.select2(
			{
				placeholder: "",
				minimumInputLength: 2,
				ajax: {
					url: '/admin/ajax/mindsearch',
					dataType: 'json',
					data: function (term, page) {
						return {
							q: term,
							page_limit: 50,
							model: $this.attr('model'),
							fields: $this.attr('fields'),
							searchfields: $this.attr('searchfields'),
						};
					},
					results: function (data, page)
					{
						return {results: data.results};
					}
				},
				formatResult: orderItemsFormatResult,
				formatSelection: orderItemsFormatSelection,
				escapeMarkup: function (markup) { return markup; }
			});

		function orderItemsFormatResult(row)
		{
			var html = row.name + " (" + row.price + " руб.)";

			return html;
		}

		function orderItemsFormatSelection(row, container)
		{

			if(row.id != undefined && row.id !== "")
			{
				var container = $(".orderItems");
				var clone = $(".orderItemsRow", container).last().clone();
				$(".OrdersItemsRowId", clone).val("");

				$.each(row, function(field, value){
					$(".OrdersItems_"+field, clone).val(value);
				});

				$(".OrdersItems_item_id", clone).val(row.id);

				clone.show();

				container.append(clone);

				orderItemsReinitPrice();
			}

			return "Укажите название товара";

		}

		function orderItemsReinitPrice(){
			var form = $("#orderForm");
			$.post("/admin/orders/reinitprice", form.serializeArray(), function(data){
				$(".orderItems").html(data.items);
				$(".orderPrices").html(data.prices);
			}, "json");
		}

		body.on("change", ".orderItemsRowChanger", function(){
			orderItemsReinitPrice();
		});

		body.on("click", ".orderItemsRemove", function(){
			if($(".orderItemsRow").length > 1) {
				$(this).closest(".orderItemsRow").remove();

				orderItemsReinitPrice();
			}


			return false;
		});
	}

	body.on("click", ".addOrderComment", function () {
		var $this = $(this);
		var parent = $this.closest(".addOrderCommentContainer");
		var $data = $this.data();
		var text = $(".addOrderComment_text_" + $data.order_id, parent).val();

		if(text != "") {
			$data["text"] = text;

			$.post("/admin/orders/addcomment", $data, function(data){
				if(data.success) {
					$(".addOrderCommentsItems", parent).append(data.html);
					alertify.success(data.msg);
					$(".addOrderComment_text_" + $data.order_id, parent).val("");
				}
				else {
					alertify.error(data.error);
				}
			}, "json");
		}
		else {
			alertify.error("Комментарий не может быть пустым");
		}

		return false;
	});

	body.on("click", ".orderPaymentLink", function(){
		var $this = $(this);
		$.post("/admin/orders/orderpaymentlink", $this.data(), function(data){
			if(data.success) {
				alertify.success(data.msg);
			}
			else {
				alertify.error(data.error);
			}
		}, "json");

		return false;
	});

	body.on("change", ".rowChecker, .select-on-check-all", function(){
		var actions = $(".gridViewActions");

		if($(".rowChecker:checked").length > 0) {
			actions.show();
		}
		else {
			actions.hide();
		}
	});

	body.on("change", ".select-on-check-all", function () {
		var parent = $(this).closest(".GridViewForm");

		if($(this).prop("checked")) {
            $(".rowChecker", parent).prop("checked", true).change();
		}
		else {
            $(".rowChecker", parent).prop("checked", false).change();
		}
    });

	body.on("click", ".removeAllRows", function(){
		var actions = $(".gridViewActions");
		var form = $(".GridViewForm");

		$.post("/admin/ajax/removeall", form.serializeArray(), function(data){
			if(data.success){
				alertify.success(data.msg);
				$.pjax.reload({container:"#indexGrid"});
			}
			else {
				alertify.error(data.error);
			}
		}, "json");
	});

	body.on("click", ".goPrintRowChecker", function () {
        $(".goPrintRow").addClass("no-print");

		$(".rowChecker:checked").each(function () {
			$(this).closest(".goPrintRow").removeClass("no-print");
        });

        window.print();

        return false;
    });
});

function initChosen(){
	var config = {
		'.chosen'           : {inherit_select_classes: true, width: "200px", search_contains: true},
		'.chosen-select'           : {inherit_select_classes: true, width:"100%", search_contains: true},
		'.chosen-select-deselect'  : {allow_single_deselect:true, search_contains: true},
		'.chosen-select-no-single' : {disable_search_threshold:10, search_contains: true},
		'.chosen-select-no-results': {no_results_text:'Ооо, попробуй еще раз!', search_contains: true},
		'.chosen-select-width'     : {width:"95%", search_contains: true}

	}
	for (var selector in config) {
		if($(selector).length > 0) {
			$(selector).chosen(config[selector]);
		}
	}
}



function transliterate(word){
    var a = {"/": "-", "\\": "-", "&": "-", ".": "-", ",": "-", " ": "-", " ": "-", "Ё":"YO","Й":"I","Ц":"TS","У":"U","К":"K","Е":"E","Н":"N","Г":"G","Ш":"SH","Щ":"SCH","З":"Z","Х":"H","Ъ":"-","ё":"yo","й":"i","ц":"ts","у":"u","к":"k","е":"e","н":"n","г":"g","ш":"sh","щ":"sch","з":"z","х":"h","ъ":"-","Ф":"F","Ы":"I","В":"V","А":"a","П":"P","Р":"R","О":"O","Л":"L","Д":"D","Ж":"ZH","Э":"E","ф":"f","ы":"i","в":"v","а":"a","п":"p","р":"r","о":"o","л":"l","д":"d","ж":"zh","э":"e","Я":"Ya","Ч":"CH","С":"S","М":"M","И":"I","Т":"T","Ь":"-","Б":"B","Ю":"YU","я":"ya","ч":"ch","с":"s","м":"m","и":"i","т":"t","ь":"-","б":"b","ю":"yu"};

    return uniqueString(word.split('').map(function (char) {
        return a[char] || char;
    }).join("").toLowerCase());
}

function uniqueString(str) {
    var result = '';
    for(var i = 0; i < str.length; i++) {
        if(result.indexOf(str[i]) < 0) {
            result += str[i];
        }
    }
    return result;
}

function yandexMapPoints(element) {
    var points = $(element.data("points"));

    ymaps.ready(function () {
        var map = new ymaps.Map(element.attr("id"), {
                center: element.data("center"),
                zoom: 9
            }, {
                searchControlProvider: 'yandex#search'
            });



            points.each(function () {
				var data = points.data();

				ymaps.geocode(data["address"], {
					results: 1
				})
				.then(function (result) {
					var object = result.geoObjects.get(0);
					var coords = object.geometry.getCoordinates();

					var point = new ymaps.Placemark(coords, {
						balloonContentHeader: data["header"],
						balloonContentBody: data["body"],
						balloonContentFooter: data["footer"],
						hintContent: data["content"]
					});

					map.geoObjects.add(point);
				});
        	});
    });
}