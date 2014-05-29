steal(
	'mad/view'
).then(function () {

	/*
	 * @class passbolt.view.component.LoadingBar
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('passbolt.view.component.LoadingBar', /** @static */ {

		'defaults': {
		}

	}, /** @prototype */ {

		/**
		 * Update the loading bar.
		 * @param size
		 * @param animate
		 * @param callback
		 */
		'update': function(size, animate, callback) {
			animate = typeof(animate) != 'undefined' ? animate : true;
			callback = callback || null;
			var percent = size + '%';

			if (animate) {
				$('.progress-bar span', this.element).animate({width:percent}, callback);
			} else {
				$('.progress-bar span', this.element).css('width', percent);
				if (callback) {
					callback();
				}
			}
		}
	});
});
