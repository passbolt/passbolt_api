steal(
    'mad/controller/component/buttonController.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.CopyLoginButtonController
	 * @inherits {mad.controller.component.ButtonController}
	 * @parent index
	 * 
	 * 
	 * @constructor
	 * Instanciate a copy login button controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.CopyLoginButtonController}
	 */
	mad.controller.component.ButtonController.extend('passbolt.controller.component.CopyLoginButtonController', /** @static */ {

		'defaults': {
			'label': 'Copy Login To Clipboard',
			'cssClasses': ['with_icon', 'copy_login'],
			// The associated password browser
			'browser': null
		}

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the mouse leave the component
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'click': function (el, ev) {
			mad.bus.trigger('copy_login_clipboard', this.value);
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the mouse leave the component
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{browser} tbody mouseleave': function (el, ev) {
			if (this.state.is('ready')) {
				this.setState('hidden');
			}
		},

		/**
		 * Observe when an resource is hovered in the browser controller
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {mixed} item The hovered resource instance or its id
		 * @param {HTMLEvent} ev The source event which occured
		 * @return {void}
		 */
		'{browser} item_hovered': function (el, ev, item) {
			if (this.value.id == item.id) {
				this.setState('ready');
			} else {
				this.setState('hidden');
			}
		}

	});
});