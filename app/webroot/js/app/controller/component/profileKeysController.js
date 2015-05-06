steal(
	'mad/controller/componentController.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.ProfileKeysController
	 * @inherits {mad.controller.ComponentController}
	 * @parent index 
	 * 
	 * Our profile keys controller
	 * 
	 * @constructor
	 * Creates a new Profile Keys Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.ProfileController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.ProfileKeysController', /** @static */ {

		'defaults': {

		}

	}, /** @prototype */ {

		/**
		 * After start.
		 */
		'afterStart': function() {
			var self = this;
			this._super();
		}

		/* ************************************************************** */
		/* LISTEN TO THE MODEL EVENTS */
		/* ************************************************************** */



		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */


	});

});