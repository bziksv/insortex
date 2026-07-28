function JCTitleSearch(arParams)
{
	var _this = this;

	this.arParams = {
		'AJAX_PAGE': arParams.AJAX_PAGE,
		'CONTAINER_ID': arParams.CONTAINER_ID,
		'INPUT_ID': arParams.INPUT_ID,
		'MIN_QUERY_LEN': parseInt(arParams.MIN_QUERY_LEN)
	};

	if(arParams.MIN_QUERY_LEN <= 0)
		arParams.MIN_QUERY_LEN = 1;

	this.cache = [];
	this.cache_key = null;

	this.startText = '';
	this.running = false;
	this.RESULT = null;
	this.CONTAINER = null;
	this.INPUT = null;

	this.ShowResult = function(result)
	{
		if(BX.type.isString(result))
		{
			_this.RESULT.innerHTML = result;
		}

		_this.RESULT.style.display = _this.RESULT.innerHTML !== '' ? 'block' : 'none';
		$('.rx-search-result .js-simplebar').each(function () {
			new SimpleBar(this);
		});
	};

	this.onChange = function(callback)
	{
		if (_this.running) {
			return;
		}

		_this.running = true;
		if(_this.INPUT.value !== _this.oldValue && _this.INPUT.value !== _this.startText)
		{
			_this.oldValue = _this.INPUT.value;
			if(_this.INPUT.value.length >= _this.arParams.MIN_QUERY_LEN)
			{
				_this.cache_key = _this.arParams.INPUT_ID + '|' + _this.INPUT.value;
				if(_this.cache[_this.cache_key] == null)
				{
					BX.ajax.post(
						_this.arParams.AJAX_PAGE,
						{
							'ajax_call':'y',
							'INPUT_ID':_this.arParams.INPUT_ID,
							'q':_this.INPUT.value,
							'l':_this.arParams.MIN_QUERY_LEN
						},
						function(result)
						{
							_this.cache[_this.cache_key] = result;
							_this.ShowResult(result);

							if (!!callback)
								callback();
							_this.running = false;
						}
					);

					return;
				}
				else
				{
					_this.ShowResult(_this.cache[_this.cache_key]);
				}
			}
			else
			{
				_this.RESULT.style.display = 'none';
			}
		}

		if (!!callback) {
			callback();
		}

		_this.running = false;
	};

	this.onFocusLost = function()
	{
		setTimeout(function(){_this.RESULT.style.display = 'none';}, 300);
	};

	this.onFocusGain = function()
	{
		if(_this.RESULT.innerHTML.length){
			_this.ShowResult();
		}
	};

	this.adjustResultNode = function() {};

	this._onContainerLayoutChange = function()
	{
		if(_this.RESULT.style.display !== "none" && _this.RESULT.innerHTML !== '')
		{
			_this.adjustResultNode();
		}
	};
	this.Init = function()
	{
		this.CONTAINER = document.getElementById(this.arParams.CONTAINER_ID);
		BX.addCustomEvent(this.CONTAINER, 'OnNodeLayoutChange', this._onContainerLayoutChange);

		this.RESULT = document.body.appendChild(document.createElement('DIV'));
		this.RESULT.className = 'rx-search-result ' + this.arParams.INPUT_ID;

		this.INPUT = document.getElementById(this.arParams.INPUT_ID);
		this.startText = this.oldValue = this.INPUT.value;

		BX.bind(this.INPUT, 'focus', function() {_this.onFocusGain()});
		BX.bind(this.INPUT, 'blur', function() {_this.onFocusLost()});

		let KeyDownTimer = false;
		$(this.INPUT).on('keydown', function(){
			if(KeyDownTimer){
				clearTimeout(KeyDownTimer);
			}

			KeyDownTimer = setTimeout(function(){
				_this.onChange();
			}, 300);
		});
	};
	BX.ready(function (){_this.Init(arParams)});
}
