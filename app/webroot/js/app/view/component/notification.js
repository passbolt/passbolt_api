steal(
	'mad/view'
).then(function () {

	/*
	 * @class passbolt.view.component.Notification
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('passbolt.view.component.Notification', /** @static */ {

	}, /** @prototype */ {

		// 
		'timeoutBeforeReset': null,

		'render': function () {
			var self = this;
			// A notification is already shown, destroy the current timeout listener
			if (this.timeoutBeforeReset) {
				clearTimeout(this.timeoutBeforeReset);
				self.controller.setState('hidden');
			}
			// hide the notificator after 30 secondes
			self.timeoutBeforeReset = setTimeout(function(){
				self.controller.setState('hidden');
			}, 30000);

			this._super();

			// position the notificator functions of the search field
			var eltWidth = this.element.width(),
				refEltWidth = $('#js_search_field').width(),
				left = (refEltWidth - eltWidth) / 2
			this.element.css('left', left);
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * The user clicks on details
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'#js_notification_more_button click': function (element, ev) {
			$(this.element).find('#js_notification_details').show().one('mouseleave', function () {
				$(this).hide();
			});
		}

	});
});