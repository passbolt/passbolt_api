steal(
	'mad/controller/componentController.js',
	'app/view/component/loadingBar.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.LoadingBarController
	 * @inherits mad.controller.ComponentController
	 * @parent index
	 * @see {mad.view.View}
	 * 
	 * @constructor
	 * The Loading Bar class Controller will be used to display to users
	 * feedbacks about loading processus.
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.LoadingBarController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.LoadingBarController', /** @static */ {

		'defaults': {
			'label': 'Loading Bar Controller',
			'viewClass': passbolt.view.component.LoadingBar,
			'templateBased': false
		}

	}, /** @prototype */ {

		/**
		 * Start a loading.
		 */
		'loading_start': function () {
			this.view.loading_start();
		},

		/**
		 * Complete a loading
		 */
		'loading_complete': function () {
			this.view.loading_complete();
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Listen the event passbolt_loading and display a feedback to the user
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 */
		'{mad.bus} passbolt_loading': function (el, ev) {
			this.loading_start();
		},

		/**
		 * Listen the event passbolt_loading_completed and display a feedback to the user
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 */
		'{mad.bus} passbolt_loading_complete': function (el, ev) {
			this.loading_complete();
		}

	});
});
