steal(
	'mad/controller/componentController.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.KeysController
	 * @inherits {mad.controller.ComponentController}
	 * @parent index 
	 * 
	 * Our Keys controller
	 * 
	 * @constructor
	 * Creates a new Keys Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.KeysController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.KeysController', /** @static */ {

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