steal(
	'mad/view'
).then(function () {

	/*
	 * @class passbolt.view.component.AppFilter
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('passbolt.view.component.AppFilter', /** @static */ {

	}, /** @prototype */ {

		'render': function () {
			return this._super();
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
//			$(this.element).find('#js_notification_details').show().one('mouseleave', function () {
//				$(this).hide();
//			});
		}

	});
});