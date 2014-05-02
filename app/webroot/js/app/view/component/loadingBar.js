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
		},

		// constructor like
		'loading_start': function() {
			$('.progress-bar span', this.element).animate({width:'20%'},function(){
				$('.progress-bar span', this.element).css('width','20%');
			});
		},

		// constructor like
		'loading_complete': function() {
			$('.progress-bar span', this.element).animate({width:'100%'},function(){
				$('.progress-bar span', this.element).css('width','0%');
			});
		}
	});
});
