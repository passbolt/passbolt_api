steal(
	'mad/controller/component/menuController.js'
).then(function () {

	/*
	 * @class passbolt.controller.PreferenceMenuController
	 * @inherits mad.controller.component.MenuController
	 * @parent index 
	 * 
	 * Our preference menu component.
	 * 
	 * @constructor
	 * Creates a new Preference Menu Controller.
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller. These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.PreferenceMenuController}
	 */
	mad.controller.component.MenuController.extend('passbolt.controller.component.PreferenceMenuController', /** @static */ {

		'defaults': {
			menuItems: null
		}

	}, /** @prototype */ {

		'afterStart': function() {
			this.load(this.options.menuItems);
		}
	});

});