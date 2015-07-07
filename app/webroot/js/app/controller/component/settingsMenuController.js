steal(
	'mad/controller/component/menuController.js'
).then(function () {

	/*
	 * @class passbolt.controller.SettingsMenuController
	 * @inherits mad.controller.component.MenuController
	 * @parent index 
	 * 
	 * Our settings menu component.
	 * 
	 * @constructor
	 * Creates a new Settings Menu Controller.
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller. These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.SettingsMenuController}
	 */
	mad.controller.component.MenuController.extend('passbolt.controller.component.SettingsMenuController', /** @static */ {

		'defaults': {
			menuItems: null
		}

	}, /** @prototype */ {

		'afterStart': function() {
			this.load(this.options.menuItems);
		}
	});

});