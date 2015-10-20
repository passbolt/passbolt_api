import  'mad/component/menu';


/**
 * @inherits {mad.component.Menu}
 * @parent index
 *
 * Navigation left controller
 *
 * @constructor
 * Instantiate the application navigation left component
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.AppNavigationLeft}
 */
var AppNavigationLeft = passbolt.component.AppNavigationLeft = mad.component.Menu.extend('passbolt.component.AppNavigationLeft', /** @static */ {

	'defaults': {}

}, /** @prototype */ {

	/**
	 * After start. Init the menu items
	 * @return {void}
	 */
	afterStart: function () {
		var menuItems = [
			new mad.model.Action({
				'id': 'js_app_nav_left_home_link',
				'label': __('home'),
				'cssClasses': ['home'],
				'action': function () {
					mad.bus.trigger('workspace_selected', 'password');
				}
			}), new mad.model.Action({
				'id': 'js_app_nav_left_pwd_wsp_link',
				'label': __('passwords'),
				'cssClasses': ['passwords'],
				'action': function () {
					mad.bus.trigger('workspace_selected', 'password');
				}
			}), new mad.model.Action({
				'id':  'js_app_nav_left_user_wsp_link',
				'label': __('users'),
				'cssClasses': ['users'],
				'action': function () {
					mad.bus.trigger('workspace_selected', 'people');
				}
			})
		];
		this.load(menuItems);
	}

});

export default AppNavigationLeft;