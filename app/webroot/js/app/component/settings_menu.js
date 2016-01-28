import 'mad/component/menu';

/**
 * @inherits mad.component.Menu
 * @parent index
 *
 * Our settings menu component.
 *
 * @constructor
 * Creates a new Settings Menu.
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller. These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.SettingsMenu}
 */
var SettingsMenu = passbolt.component.SettingsMenu = mad.component.Menu.extend('passbolt.component.SettingsMenu', /** @static */ {

	defaults: {
		menuItems: null
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function() {
		this.load(this.options.menuItems);
	}

});
export default SettingsMenu;