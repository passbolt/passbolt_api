import  'mad/component/menu';

/**
 * @inherits {mad.component.Menu}
 * @parent index
 *
 * Navigation right component
 *
 * @constructor
 * Instantiate the application navigation right controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.AppNavigationRight}
 */
var AppNavigationRight = passbolt.component.AppNavigationRight = mad.component.Menu.extend('passbolt.component.AppNavigationRight', /** @static */ {

	defaults: {}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * Initialize the menu items.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		var menuItems = [
			new mad.model.Action({
				id: uuid(),
				label: __('logout'),
				cssClasses: ['logout'],
				action: function () {
					document.location.href = APP_URL + '/auth/logout';
				}
			})
		];
		this.load(menuItems);
	}

});