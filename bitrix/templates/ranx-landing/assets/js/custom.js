// this file won't be updated, so you can write your own code here

(function () {
	function initBeforeAfter(root) {
		if (!root || root.dataset.rxBaReady === '1') {
			return;
		}

		var range = root.querySelector('.rx-ba__range');
		if (!range) {
			return;
		}

		var setPos = function () {
			root.style.setProperty('--pos', range.value + '%');
		};

		range.addEventListener('input', setPos);
		range.addEventListener('change', setPos);
		setPos();
		root.dataset.rxBaReady = '1';
	}

	function initAll(ctx) {
		var scope = ctx && ctx.querySelectorAll ? ctx : document;
		scope.querySelectorAll('.rx-ba').forEach(initBeforeAfter);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			initAll(document);
		});
	} else {
		initAll(document);
	}

	if (typeof BX !== 'undefined' && BX.addCustomEvent) {
		BX.addCustomEvent('onFrameDataReceived', function () {
			initAll(document);
		});
	}
})();
