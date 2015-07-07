steal(
    'mad/controller/component/menuController.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.AppNavigationLeftController
	 * @inherits {mad.controller.component.MenuController}
	 * @parent index
	 * 
	 * Navigation left controller
	 * 
	 * @constructor
	 * Instantiate the application navigation left controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.AppNavigationLeftController}
	 */
	mad.controller.component.MenuController.extend('passbolt.controller.component.AppNavigationLeftController', /** @static */ {

		'defaults': {}

	}, /** @prototype */ {

		/**
		 * After start. Init the menu items
		 * @return {void}
		 */
		'afterStart': function () {
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
});
