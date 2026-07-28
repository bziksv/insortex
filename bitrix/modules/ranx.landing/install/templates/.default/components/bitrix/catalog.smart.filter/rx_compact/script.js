// fix double add event handler (composite + google PSI module)
if(!window.JCSmartFilterBinds){
	$(document).ready(function(){
		$(document).on('click', '.bx_filter.rx_compact .delete_filter', function(){
			let $box = $(this).closest('.bx_filter_parameters_box');

			$box.find('input[type=text]').val('');
			$box.removeClass('set');
			$box.find('.bx_filter_pict_label').removeClass('active');
			$box.find('.bx_filter_select_label').removeClass('selected');
			$box.find('.bx_filter_select_text').text(BX.message('RX_SMART_FILTER_COMPACT_ALL'));
			$box.find('input').each(function(){
				$(this).prop('checked', false);
				smartFilter.reload(this);
			})
		});

		$(document).on('click', '.bx_filter.rx_compact .bx_filter_block', function(e){
			e.stopPropagation();
		});

		$(document).on('click', '.bx_filter_button_box .bx_filter_search_reset', function(e){
			$('.bx_filter_search_reset.btn-link-text').click();
		});

		$(document).on('click', '.bx_filter.rx_compact .bx_filter_parameters_box_title:not(.prices)', function(e){
			let target = e.target;
			if(!$(e.target).closest('.delete_filter').length){
				e.stopPropagation();

				let _this = $(this);
				let $box = _this.closest('.bx_filter_parameters_box');
				if($box.hasClass('active')) {
					$(this).next('.bx_filter_block').stop().fadeOut(100);
				}
				else{
					$('.bx_filter_block:not(.limited_block)').fadeOut(100);
					$('.bx_filter_parameters_box').removeClass('active');

					setTimeout(function(){
						let $filterBlock = _this.next('.bx_filter_block');
						let boxOffsetLeft =_this.offset().left;
						let boxWidth = $filterBlock.outerWidth();
						let windowWidth = $(window).width();

						if (window.matchMedia('(max-width: 767px)').matches) {
							if (boxOffsetLeft + boxWidth > windowWidth) {
								let leftOffset = boxOffsetLeft + boxWidth + 20 - windowWidth
								$filterBlock.css('left', `-${leftOffset}px`)
							}
						}
						else {
							$filterBlock.toggleClass('right', boxOffsetLeft + boxWidth > windowWidth);
						}
					}, 0);

					_this.next('.bx_filter_block:not(.limited_block)').stop().delay(250).fadeIn(300);
				}
				$box.toggleClass('active');
				_this.next('.bx_filter_block.limited_block').find('label:not(.disabled)').click();
			}
		});

		$(document).on('click', function(e){
			$('.bx_filter.rx_compact .bx_filter_block:not(.limited_block)').stop().fadeOut(100);
			$('.bx_filter.rx_compact .bx_filter_parameters_box').removeClass('active');
		});

		window.JCSmartFilterBinds = true;
	});
}

function JCSmartFilter(ajaxURL, blockId, params)
{
	this.ajaxURL = ajaxURL;
	this.blockId = blockId;
	this.form = null;
	this.timer = null;
	this.cacheKey = '';
	this.cache = [];
	this.normal_url = false;
	this.SEF_SET_FILTER_URL=params.SEF_SET_FILTER_URL;
	this.SEF_DEL_FILTER_URL=params.SEF_DEL_FILTER_URL;
	if (params && params.SEF_SET_FILTER_URL)
	{
		this.bindUrlToButton('set_filter', params.SEF_SET_FILTER_URL);
		this.sef = true;
	}
	if (params && params.SEF_DEL_FILTER_URL)
	{
		this.bindUrlToButton('del_filter', params.SEF_DEL_FILTER_URL);
	}
	if(!params.SEF_DEL_FILTER_URL){
		this.normal_url = true;
	}
}

JCSmartFilter.prototype.keyup = function(input)
{
	if(!!this.timer)
	{
		clearTimeout(this.timer);
	}
	if(!$(input).hasClass('disabled')){
		this.timer = setTimeout(BX.delegate(function(){
			this.reload(input);
		}, this), 500);
	}
};

JCSmartFilter.prototype.click = function(checkbox)
{
	if(!!this.timer)
	{
		clearTimeout(this.timer);
	}

	this.timer = setTimeout(BX.delegate(function(){
		this.reload(checkbox);
	}, this), 500);
};

JCSmartFilter.prototype.reload = function(input)
{
	if (this.cacheKey !== '')
	{
		//Postprone backend query
		if(!!this.timer)
		{
			clearTimeout(this.timer);
		}
		this.timer = setTimeout(BX.delegate(function(){
			this.reload(input);
		}, this), 1000);
		return;
	}

	this.cacheKey = '|';
	this.form = BX.findParent(input, {'tag':'form'});
	if (this.form)
	{
		let values = [];

		$(input).closest('.bx_filter_parameters_box').removeClass('set');

		this.gatherInputsValues(values, BX.findChildren(this.form, {'tag': new RegExp('^(input|select)$', 'i')}, true));
		for (let i = 0; i < values.length; i++){
			this.cacheKey += values[i].name + ':' + values[i].value + '|';
			$('input[name='+values[i].name+']').closest('.bx_filter_parameters_box').addClass('set');
		}

		let setPropsCount = $(this.form).find('.bx_filter_parameters_box.set').length;
		$(this.form).find('#del_filter').toggleClass('hidden', !setPropsCount);

		if (this.cache[this.cacheKey]) {
			startBlockLoad(this.blockId);
			this.postHandler(this.cache[this.cacheKey], true);
		}
		else {
			this.ajax(this.ajaxURL, values);
		}
	}
};

JCSmartFilter.prototype.updateItem = function (PID, arItem, reset, count)
{
	if (arItem.PROPERTY_TYPE === 'N' || arItem.PRICE) {
		let trackBarId = PID;
		let trackBar = window['trackBar' + PID];
		if (!trackBar && arItem.ENCODED_ID) {
			trackBarId = arItem.ENCODED_ID;
			trackBar = window['trackBar' + arItem.ENCODED_ID];
		}

		if (trackBar && arItem.VALUES) {
			if (arItem.VALUES.MIN) {
				if (arItem.VALUES.MIN.FILTERED_VALUE)
					trackBar.setMinFilteredValue(arItem.VALUES.MIN.FILTERED_VALUE);
				else
					trackBar.setMinFilteredValue(arItem.VALUES.MIN.VALUE);
			}
			if (arItem.VALUES.MAX) {
				if (arItem.VALUES.MAX.FILTERED_VALUE)
					trackBar.setMaxFilteredValue(arItem.VALUES.MAX.FILTERED_VALUE);
				else
					trackBar.setMaxFilteredValue(arItem.VALUES.MAX.VALUE);
			}

			if (reset || arItem.PROPERTY_SET !== 'Y') {
				trackBar.leftPercent = trackBar.rightPercent = 0;
				$("#"+arItem.VALUES.MIN.CONTROL_ID).val('');
				$("#"+arItem.VALUES.MAX.CONTROL_ID).val('');
				$("#left_slider_"+trackBarId).css({'left':"0%"});
				$("#colorUnavailableActive_"+trackBarId).css({'left':"0%", 'right' : "0%"});
				$("#colorAvailableInactive_"+trackBarId).css({'left':"0%", 'right' : "0%"});
				$("#colorAvailableActive_"+trackBarId).css({'left':"0%", 'right' : "0%"});
				$("#right_slider_"+trackBarId).css({'right':"0%"});
			}
		}
	} else if (arItem.VALUES) {
		for (var i in arItem.VALUES) {
			if (!arItem.VALUES.hasOwnProperty(i)) {
				continue;
			}

			var value = arItem.VALUES[i];
			var control = BX(value.CONTROL_ID);
			if (!control) {
				continue;
			}

			var label = document.querySelector('[data-role="label_'+value.CONTROL_ID+'"]');
			var input = document.querySelector('[id="'+value.CONTROL_ID+'"]');

			if (value.DISABLED) {
				if (label){
					BX.addClass(label, 'disabled');
					if(input){
						input.setAttribute('disabled','disabled');
						BX.addClass(input, 'disabled');
					}
				} else {
					BX.addClass(control.parentNode, 'disabled');
				}
			} else {
				if (label){
					BX.removeClass(label, 'disabled');
					if(input){
						input.removeAttribute('disabled');
						BX.removeClass(input, 'disabled');
					}
					if($(control)) {
						$(control).prop('disabled',false);
						$(control).removeClass('disabled');
					}
					$(label).find('span').removeClass('disabled');
				} else {
					BX.removeClass(control.parentNode, 'disabled');
				}
			}

			let type = $(control).attr('type');
			if(reset && (type === 'checkbox' || type === 'radio')) {
				if($(control).attr('checked')){
					$(control).prop('checked',false);
				}
			}

			if (value.hasOwnProperty('ELEMENT_COUNT')) {
				label = document.querySelector('[data-role="count_'+value.CONTROL_ID+'"]');
				if (label) {
					label.innerHTML = value.ELEMENT_COUNT;
				}
			}
		}
	}

	let $countLabel = $('[data-role="count_'+PID+'"]');
	$countLabel.html(BX.message('RX_SMART_FILTER_COMPACT_SELECTED') + count);
	$countLabel.parent().show();
};

JCSmartFilter.prototype.ajax = function (url, values = [])
{
	if (!!this.blockId) {
		startBlockLoad(this.blockId);
	}

	values[values.length] = { name: 'ajax', value: 'y' };
	BX.ajax.loadJSON(
		url,
		this.values2post(values),
		BX.delegate(this.postHandler, this)
	);
}

JCSmartFilter.prototype.filterItems = function (url, isReset)
{
	const post = {
		blockId: this.blockId,
		requestUrl: url,
	};

	rxRunComponentAction(
		'block',
		'smartFilter',
		{data: { post: post }}
	).then(function(result){
		$('#block_' + post.blockId).trigger('rxFilterItems', { html: result.data.html });
	}, function (result) {
		console.log(result);
	});
}

JCSmartFilter.prototype.postHandler = function (result, fromCache)
{
	let url;
	let reset = false;
	let $filter = $('.bx_filter.rx_compact');

	if (!result) {
		return;
	}

	if ('RESET_FORM' in result) {
		$filter.find('form.smartfilter').get(0).reset();
		reset = true;
	}

	for(let PID in result.ITEMS) {
		if (!result.ITEMS.hasOwnProperty(PID)) {
			continue;
		}

		this.updateItem(PID, result.ITEMS[PID], reset, result.ELEMENT_COUNT);
	}
	if(reset){
		$('.bx_filter.rx_compact .bx_filter_select_block').each(function () {
			$(this).find('.bx_filter_select_label').removeClass('selected');
			$(this).find('.bx_filter_select_text').text(BX.message('RX_SMART_FILTER_COMPACT_ALL'));
		});

		$filter.find('.bx_filter_parameters_box').removeClass('set');
		$filter.find('#del_filter').addClass('hidden');
	}

	let filterUrl = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
	if (window.history.enabled || window.history.pushState != null){
		window.history.pushState(null, document.title, decodeURIComponent(filterUrl));
	}

	if (!!this.blockId) {
		this.filterItems(filterUrl, reset);
	}

	this.bindUrlToButton('set_filter', result.SEF_SET_FILTER_URL || url);

	if (!fromCache && this.cacheKey !== '') {
		this.cache[this.cacheKey] = result;
	}
	this.cacheKey = '';
};

JCSmartFilter.prototype.bindUrlToButton = function (buttonId, url)
{
	let button = BX(buttonId);
	if (!button) {
		return;
	}

	if (button.type === 'submit')
		button.type = 'button';

	$(button).attr('data-href', url);
	BX.unbindAll(button);

	BX.bind(button, 'click', BX.proxy(function(){
		let url = $(button).attr('data-href');
		let id = $(button).attr('id');
		let values = [];

		if (id === 'del_filter') {
			values[values.length] = { name: 'reset_form', value: 'y' };
		}

		this.ajax(url, values);
	}, this));
};

JCSmartFilter.prototype.gatherInputsValues = function (values, elements)
{
	if(elements)
	{
		for(var i = 0; i < elements.length; i++)
		{
			var el = elements[i];
			if (!el.type)
				continue;

			switch(el.type.toLowerCase())
			{
				case 'text':
				case 'textarea':
				case 'password':
				case 'hidden':
				case 'select-one':
					if(el.value.length)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'radio':
				case 'checkbox':
					if(el.checked)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'select-multiple':
					for (var j = 0; j < el.options.length; j++)
					{
						if (el.options[j].selected)
							values[values.length] = {name : el.name, value : el.options[j].value};
					}
					break;
				default:
					break;
			}
		}
	}
};

JCSmartFilter.prototype.values2post = function (values)
{
	var post = [];
	var current = post;
	var i = 0;

	while(i < values.length)
	{
		var p = values[i].name.indexOf('[');
		if(p == -1)
		{
			current[values[i].name] = values[i].value;
			current = post;
			i++;
		}
		else
		{
			var name = values[i].name.substring(0, p);
			var rest = values[i].name.substring(p+1);
			if(!current[name])
				current[name] = [];

			var pp = rest.indexOf(']');
			if(pp == -1)
			{
				//Error - not balanced brackets
				current = post;
				i++;
			}
			else if(pp == 0)
			{
				//No index specified - so take the next integer
				current = current[name];
				values[i].name = '' + current.length;
			}
			else
			{
				//Now index name becomes and name and we go deeper into the array
				current = current[name];
				values[i].name = rest.substring(0, pp) + rest.substring(pp+1);
			}
		}
	}
	return post;
};

JCSmartFilter.prototype.hideFilterProps = function(element)
{
	var obj = element.parentNode,
		filterBlock = obj.querySelector("[data-role='bx_filter_block']"),
		propAngle = obj.querySelector("[data-role='prop_angle']");

	if(BX.hasClass(obj, "bx-active"))
	{
		new BX.easing({
			duration : 300,
			start : { opacity: 1,  height: filterBlock.offsetHeight },
			finish : { opacity: 0, height:0 },
			transition : BX.easing.transitions.quart,
			step : function(state){
				filterBlock.style.opacity = state.opacity;
				filterBlock.style.height = state.height + "px";
			},
			complete : function() {
				filterBlock.setAttribute("style", "");
				BX.removeClass(obj, "bx-active");
			}
		}).animate();

		BX.addClass(propAngle, "fa-angle-down");
		BX.removeClass(propAngle, "fa-angle-up");
	}
	else
	{
		filterBlock.style.display = "block";
		filterBlock.style.opacity = 0;
		filterBlock.style.height = "auto";

		var obj_children_height = filterBlock.offsetHeight;
		filterBlock.style.height = 0;

		new BX.easing({
			duration : 300,
			start : { opacity: 0,  height: 0 },
			finish : { opacity: 1, height: obj_children_height },
			transition : BX.easing.transitions.quart,
			step : function(state){
				filterBlock.style.opacity = state.opacity;
				filterBlock.style.height = state.height + "px";
			},
			complete : function() {
			}
		}).animate();

		BX.addClass(obj, "bx-active");
		BX.removeClass(propAngle, "fa-angle-down");
		BX.addClass(propAngle, "fa-angle-up");
	}
};

JCSmartFilter.prototype.toggleCheckbox = function(input, selector)
{
	if ($(input).hasClass('disabled')) {
		return;
	}

	BX.toggleClass(input, selector);
}

JCSmartFilter.prototype.showDropDownPopup = function(element, popupId)
{
	let contentNode = element.querySelector('[data-role="dropdownContent"]');
	let offset = $(element).offset();
	let popup = BX.PopupWindowManager.create("smartFilterDropDown"+popupId, element, {
		autoHide: true,
		offsetLeft: 0,
		offsetTop: 0,
		overlay : false,
		draggable: { restrict:true },
		closeByEsc: true,
		content: contentNode
	});
	popup.show();

	// return the code from the popup
	let $boxContainer = $(element).closest('.bx_filter_parameters_box_container');
	let id = '#smartFilterDropDown' + popupId;
	if(!$boxContainer.find(id).length) {
		$(id).insertAfter($boxContainer).css({'top':'auto', 'left':'auto', 'width':$(element).css('width')});
	}

	$(id).each(function(){ new SimpleBar(this) });
};

JCSmartFilter.prototype.selectDropDownItem = function(element, controlId)
{
	if(BX.hasClass(element,'disabled')) {
		return;
	}

	this.keyup(BX(controlId));
	let wrapContainer = BX.findParent(BX(controlId), { className : 'bx_filter_select_container' }, false);
	let currentOption = wrapContainer.querySelector('[data-role="currentOption"]');

	currentOption.innerHTML = element.innerHTML;
	$(element).closest('.bx_filter_select_popup').find('label').removeClass('selected');
	BX.addClass(element, 'selected');

	if(BX.PopupWindowManager.getCurrentPopup())
		BX.PopupWindowManager.getCurrentPopup().close();
};

BX.namespace("BX.Iblock.SmartFilter");
BX.Iblock.SmartFilter = (function()
{
	var SmartFilter = function(arParams)
	{
		if (typeof arParams === 'object')
		{
			this.leftSlider = BX(arParams.leftSlider);
			this.rightSlider = BX(arParams.rightSlider);
			this.tracker = BX(arParams.tracker);
			this.trackerWrap = BX(arParams.trackerWrap);

			this.minInput = BX(arParams.minInputId);
			this.maxInput = BX(arParams.maxInputId);

			this.minPrice = parseFloat(arParams.minPrice);
			this.maxPrice = parseFloat(arParams.maxPrice);

			this.curMinPrice = parseFloat(arParams.curMinPrice);
			this.curMaxPrice = parseFloat(arParams.curMaxPrice);

			this.fltMinPrice = arParams.fltMinPrice ? parseFloat(arParams.fltMinPrice) : parseFloat(arParams.curMinPrice);
			this.fltMaxPrice = arParams.fltMaxPrice ? parseFloat(arParams.fltMaxPrice) : parseFloat(arParams.curMaxPrice);

			this.precision = arParams.precision || 0;

			this.priceDiff = this.maxPrice - this.minPrice;

			this.leftPercent = arParams.leftPercent ? parseFloat(arParams.leftPercent) : 0;
			this.rightPercent = arParams.rightPercent ? parseFloat(arParams.rightPercent) : 0;

			this.fltMinPercent = 0;
			this.fltMaxPercent = 0;

			this.colorUnavailableActive = BX(arParams.colorUnavailableActive);//gray
			this.colorAvailableActive = BX(arParams.colorAvailableActive);//blue
			this.colorAvailableInactive = BX(arParams.colorAvailableInactive);//light blue

			this.isTouch = false;

			this.init();

			if ('ontouchstart' in document.documentElement || 'ontouchstart' in window)
			{
				this.isTouch = true;

				BX.bind(this.leftSlider, "touchstart", BX.proxy(function(event){
					this.onMoveLeftSlider(event)
				}, this));

				BX.bind(this.rightSlider, "touchstart", BX.proxy(function(event){
					this.onMoveRightSlider(event)
				}, this));

				BX.bind(this.colorAvailableActive, "touchstart", BX.proxy(function(event){
					this.onChangeLeftSlider(event);
				}, this));

				BX.bind(this.colorUnavailableActive, "touchstart", BX.proxy(function(event){
					this.onChangeLeftSlider(event);
				}, this));

				BX.bind(this.colorAvailableInactive, "touchstart", BX.proxy(function(event){
					this.onChangeLeftSlider(event);
				}, this));
			}
			else
			{
				BX.bind(this.leftSlider, "mousedown", BX.proxy(function(event){
					this.onMoveLeftSlider(event)
				}, this));

				BX.bind(this.rightSlider, "mousedown", BX.proxy(function(event){
					this.onMoveRightSlider(event)
				}, this));

				BX.bind(this.colorAvailableActive, "mousedown", BX.proxy(function(event){
					this.onChangeLeftSlider(event);
				}, this));

				BX.bind(this.colorUnavailableActive, "mousedown", BX.proxy(function(event){
					this.onChangeLeftSlider(event);
				}, this));

				BX.bind(this.colorAvailableInactive, "mousedown", BX.proxy(function(event){
					this.onChangeLeftSlider(event);
				}, this));
			}

			BX.bind(this.minInput, "keyup", BX.proxy(function(event){
				this.onInputChange();
			}, this));

			BX.bind(this.maxInput, "keyup", BX.proxy(function(event){
				this.onInputChange();
			}, this));
		}
	};

	SmartFilter.prototype.init = function()
	{
		var priceDiff;

		if (this.curMinPrice > this.minPrice)
		{
			priceDiff = this.curMinPrice - this.minPrice;
			this.leftPercent = (priceDiff*100)/this.priceDiff;

			this.leftSlider.style.left = this.leftPercent + "%";
			this.colorUnavailableActive.style.left = this.leftPercent + "%";
		}

		this.setMinFilteredValue(this.fltMinPrice);

		if (this.curMaxPrice < this.maxPrice)
		{
			priceDiff = this.maxPrice - this.curMaxPrice;
			this.rightPercent = (priceDiff*100)/this.priceDiff;

			this.rightSlider.style.right = this.rightPercent + "%";
			this.colorUnavailableActive.style.right = this.rightPercent + "%";
		}

		this.setMaxFilteredValue(this.fltMaxPrice);
	};

	SmartFilter.prototype.setMinFilteredValue = function (fltMinPrice)
	{
		this.fltMinPrice = parseFloat(fltMinPrice);
		if (this.fltMinPrice >= this.minPrice)
		{
			var priceDiff = this.fltMinPrice - this.minPrice;
			this.fltMinPercent = (priceDiff*100)/this.priceDiff;

			if (this.leftPercent > this.fltMinPercent)
				this.colorAvailableActive.style.left = this.leftPercent + "%";
			else
				this.colorAvailableActive.style.left = this.fltMinPercent + "%";

			this.colorAvailableInactive.style.left = this.fltMinPercent + "%";
		}
		else
		{
			this.colorAvailableActive.style.left = "0%";
			this.colorAvailableInactive.style.left = "0%";
		}
	};

	SmartFilter.prototype.setMaxFilteredValue = function (fltMaxPrice)
	{
		this.fltMaxPrice = parseFloat(fltMaxPrice);
		if (this.fltMaxPrice <= this.maxPrice)
		{
			var priceDiff = this.maxPrice - this.fltMaxPrice;
			this.fltMaxPercent = (priceDiff*100)/this.priceDiff;

			if (this.rightPercent > this.fltMaxPercent)
				this.colorAvailableActive.style.right = this.rightPercent + "%";
			else
				this.colorAvailableActive.style.right = this.fltMaxPercent + "%";

			this.colorAvailableInactive.style.right = this.fltMaxPercent + "%";
		}
		else
		{
			this.colorAvailableActive.style.right = "0%";
			this.colorAvailableInactive.style.right = "0%";
		}
	};

	SmartFilter.prototype.getXCoord = function(elem)
	{
		var box = elem.getBoundingClientRect();
		var body = document.body;
		var docElem = document.documentElement;

		var scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft;
		var clientLeft = docElem.clientLeft || body.clientLeft || 0;
		var left = box.left + scrollLeft - clientLeft;

		return Math.round(left);
	};

	SmartFilter.prototype.getPageX = function(e)
	{
		e = e || window.event;
		var pageX = null;

		if (this.isTouch && e.targetTouches[0] != null)
		{
			pageX = e.targetTouches[0].pageX;
		}
		else if (e.pageX != null)
		{
			pageX = e.pageX;
		}
		else if (e.clientX != null)
		{
			var html = document.documentElement;
			var body = document.body;

			pageX = e.clientX + (html.scrollLeft || body && body.scrollLeft || 0);
			pageX -= html.clientLeft || 0;
		}

		return pageX;
	};

	SmartFilter.prototype.recountMinPrice = function()
	{
		var newMinPrice = (this.priceDiff*this.leftPercent)/100;
		newMinPrice = (this.minPrice + newMinPrice).toFixed(this.precision);

		if (newMinPrice != this.minPrice)
			this.minInput.value = newMinPrice;
		else
			this.minInput.value = "";
		smartFilter.keyup(this.minInput);
	};

	SmartFilter.prototype.recountMaxPrice = function()
	{
		var newMaxPrice = (this.priceDiff*this.rightPercent)/100;
		newMaxPrice = (this.maxPrice - newMaxPrice).toFixed(this.precision);

		if (newMaxPrice != this.maxPrice)
			this.maxInput.value = newMaxPrice;
		else
			this.maxInput.value = "";
		smartFilter.keyup(this.maxInput);
	};

	SmartFilter.prototype.onInputChange = function ()
	{
		var priceDiff;
		if (this.minInput.value)
		{
			var leftInputValue = this.minInput.value;
			if (leftInputValue < this.minPrice)
				leftInputValue = this.minPrice;

			if (leftInputValue > this.maxPrice)
				leftInputValue = this.maxPrice;

			priceDiff = leftInputValue - this.minPrice;
			this.leftPercent = (priceDiff*100)/this.priceDiff;

			this.makeLeftSliderMove(false);
		}

		if (this.maxInput.value)
		{
			var rightInputValue = this.maxInput.value;
			if (rightInputValue < this.minPrice)
				rightInputValue = this.minPrice;

			if (rightInputValue > this.maxPrice)
				rightInputValue = this.maxPrice;

			priceDiff = this.maxPrice - rightInputValue;
			this.rightPercent = (priceDiff*100)/this.priceDiff;

			this.makeRightSliderMove(false);
		}
	};

	SmartFilter.prototype.makeLeftSliderMove = function(recountPrice)
	{
		recountPrice = (recountPrice !== false);

		this.leftSlider.style.left = this.leftPercent + "%";
		this.colorUnavailableActive.style.left = this.leftPercent + "%";

		var areBothSlidersMoving = false;
		if (this.leftPercent + this.rightPercent >= 100)
		{
			areBothSlidersMoving = true;
			this.rightPercent = 100 - this.leftPercent;
			this.rightSlider.style.right = this.rightPercent + "%";
			this.colorUnavailableActive.style.right = this.rightPercent + "%";
		}

		if (this.leftPercent >= this.fltMinPercent && this.leftPercent <= (100-this.fltMaxPercent))
		{
			this.colorAvailableActive.style.left = this.leftPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.right = 100 - this.leftPercent + "%";
			}
		}
		else if(this.leftPercent <= this.fltMinPercent)
		{
			this.colorAvailableActive.style.left = this.fltMinPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.right = 100 - this.fltMinPercent + "%";
			}
		}
		else if(this.leftPercent >= this.fltMaxPercent)
		{
			this.colorAvailableActive.style.left = 100-this.fltMaxPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.right = this.fltMaxPercent + "%";
			}
		}

		if (recountPrice)
		{
			this.recountMinPrice();
			if (areBothSlidersMoving)
				this.recountMaxPrice();
		}
	};

	SmartFilter.prototype.countNewLeft = function(event)
	{
		var pageX = this.getPageX(event);

		var trackerXCoord = this.getXCoord(this.trackerWrap);
		var rightEdge = this.trackerWrap.offsetWidth;

		var newLeft = pageX - trackerXCoord;

		if (newLeft < 0)
			newLeft = 0;
		else if (newLeft > rightEdge)
			newLeft = rightEdge;

		return newLeft;
	};

	SmartFilter.prototype.onMoveLeftSlider = function(e)
	{
		if (!this.isTouch)
		{
			this.leftSlider.ondragstart = function() {
				return false;
			};
		}

		$('.bx_filter .bx_filter_parameters_box_container input').prop('disabled', false);

		if (!this.isTouch)
		{
			document.onmousemove = BX.proxy(function(event) {
				this.leftPercent = ((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
				this.makeLeftSliderMove();
			}, this);

			document.onmouseup = function() {
				document.onmousemove = document.onmouseup = null;
			};
		}
		else
		{
			var onMoveFunction = BX.proxy(function(event) {
				this.leftPercent = ((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
				this.makeLeftSliderMove();
			}, this);

			var onEndFunction = BX.proxy(function(event) {
				window.removeEventListener('touchmove', onMoveFunction, false);
				window.removeEventListener('touchend', onEndFunction, false);
				onMoveFunction = onEndFunction = null;
			}, this);

			window.addEventListener('touchmove', onMoveFunction, false);
			window.addEventListener('touchend', onEndFunction, false);
		}

		return false;
	};

	SmartFilter.prototype.makeRightSliderMove = function(recountPrice)
	{
		recountPrice = (recountPrice !== false);

		this.rightSlider.style.right = this.rightPercent + "%";
		this.colorUnavailableActive.style.right = this.rightPercent + "%";

		var areBothSlidersMoving = false;
		if (this.leftPercent + this.rightPercent >= 100)
		{
			areBothSlidersMoving = true;
			this.leftPercent = 100 - this.rightPercent;
			this.leftSlider.style.left = this.leftPercent + "%";
			this.colorUnavailableActive.style.left = this.leftPercent + "%";
		}

		if ((100-this.rightPercent) >= this.fltMinPercent && this.rightPercent >= this.fltMaxPercent)
		{
			this.colorAvailableActive.style.right = this.rightPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.left = 100 - this.rightPercent + "%";
			}
		}
		else if(this.rightPercent <= this.fltMaxPercent)
		{
			this.colorAvailableActive.style.right = this.fltMaxPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.left = 100 - this.fltMaxPercent + "%";
			}
		}
		else if((100-this.rightPercent) <= this.fltMinPercent)
		{
			this.colorAvailableActive.style.right = 100-this.fltMinPercent + "%";
			if (areBothSlidersMoving)
			{
				this.colorAvailableActive.style.left = this.fltMinPercent + "%";
			}
		}

		if (recountPrice)
		{
			this.recountMaxPrice();
			if (areBothSlidersMoving)
				this.recountMinPrice();
		}
	};

	SmartFilter.prototype.onMoveRightSlider = function(e)
	{
		if (!this.isTouch)
		{
			this.rightSlider.ondragstart = function() {
				return false;
			};
		}

		$('.bx_filter .bx_filter_parameters_box_container input').prop('disabled', false);

		if (!this.isTouch)
		{
			document.onmousemove = BX.proxy(function(event) {
				this.rightPercent = 100-(((this.countNewLeft(event))*100)/(this.trackerWrap.offsetWidth));
				this.makeRightSliderMove();
			}, this);

			document.onmouseup = function() {
				document.onmousemove = document.onmouseup = null;
			};
		}
		else
		{
			document.ontouchmove = BX.proxy(function(event) {
				this.rightPercent = 100-(((this.countNewLeft(event))*100)/(this.trackerWrap.offsetWidth));
				this.makeRightSliderMove();
			}, this);

			document.ontouchend = function() {
				document.ontouchmove = document.ontouchend = null;
			};
		}

		return false;
	};

	SmartFilter.prototype.onChangeLeftSlider = function(e)
	{
		if (!this.isTouch)
		{
			this.leftSlider.ondragstart = function() {};
		}

		if (!this.isTouch)
		{
			var percent=((this.countNewLeft(event)*100)/this.trackerWrap.offsetWidth);
			if($(event.target).is(".bx_ui_slider_handle") || !$(event.target).is("[class^=bx_ui_slider]"))
				return;
			if(percent<50){
				this.leftPercent = percent+1;
				this.makeLeftSliderMove();
			}else{
				this.rightPercent = 100-percent;
				this.makeRightSliderMove();
			}

			document.onmouseup = function() {
				document.onmousemove = document.onmouseup = null;
			};
		}
		else
		{
			var percent=((this.countNewLeft(e)*100)/this.trackerWrap.offsetWidth);
			if($(e.target).is(".bx_ui_slider_handle"))
				return;
			if(percent<50){
				this.leftPercent = percent;
				this.makeLeftSliderMove();
			}else{
				this.rightPercent = 100-percent;
				this.makeRightSliderMove();
			}
		}

		return false;
	};

	return SmartFilter;
})();
