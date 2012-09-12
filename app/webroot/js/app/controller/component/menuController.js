steal(
	MAD_ROOT + '/controller/componentController.js'
).then(function ($) {

	/**
	 * @class passbolt.controller.component.MenuController
	 * @inherits {mad.controller.ComponentController}
	 * @parent index
	 * 
	 * @constructor
	 * Instanciate a menu controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.MenuController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.MenuController', /** @static */ {

		'defaults': {
			'label': 'MenuController'
		}

	}, /** @prototype */ {

		'li click': function (elt, event) {
			mad.eventBus.trigger('js-wk-controller-select');
		}

	});
});