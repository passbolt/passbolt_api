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
