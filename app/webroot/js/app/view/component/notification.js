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
		'params': {
			'status': '',
			'title': '',
			'message': '',
			'data': '',
			'persistent': false,
			'timeout': 2500
		},

		'init': function (el, options) {
			this._super(el, options);

			// position the notificator functions of the search field
			this.element.position({
				my: "center top",
				at: "center bottom",
				of: $('#js_filter_keywords')
			});
		},

		'render': function () {
			var self = this;
			// A notification is already shown, destroy the current timeout listener
			if (this.timeoutBeforeReset) {
				clearTimeout(this.timeoutBeforeReset);
				self.controller.setState('hidden');
			}

			if(!this.params.persistent) {
				// hide the notificator after timeout value
				self.timeoutBeforeReset = setTimeout(function () {
					self.controller.setState('hidden');
				}, self.params.timeout);
			}

			return this._super();
		},

		'reset': function () {
			$(this.element).find('.js_notification_details_container').hide();
			$(this.element).find('.js_notification_more_button').text(__('see details'));
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
			var detailsVisible = $(this.element).find('#js_notification_details_container').is(':visible');
			if (detailsVisible) {
				$(this.element).find('#js_notification_details_container').hide();
				$(this.element).find('#js_notification_more_button').text(__('see details'));
			}
			else {
				$(this.element).find('#js_notification_details_container').show();
				$(this.element).find('#js_notification_more_button').text(__('hide details'));
			}
		},

		/**
		 * The user clicks on close
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'#js_notification_close_button click': function (element, ev) {
			this.controller.setState('hidden');
		}

	});
});