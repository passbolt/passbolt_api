steal(
    MAD_ROOT + '/controller/component/buttonController.js'
).then(function ($) {

	/*
	 * @class passbolt.controller.component.UserMenuButtonController
	 * @inherits {mad.controller.component.ButtonController}
	 * @parent index
	 * 
	 * 
	 * @constructor
	 * Instanciate a user menu button controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.UserMenuButtonController}
	 */
	mad.controller.component.ButtonController.extend('passbolt.controller.component.UserMenuButtonController', /** @static */ {

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Disabled
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateDisabled': function (go) {
			if (go) {
				this.element.parent('li').addClass('js_state_disabled');
			} else {
				this.element.parent('li').removeClass('js_state_disabled');
			}
		}

	});
});