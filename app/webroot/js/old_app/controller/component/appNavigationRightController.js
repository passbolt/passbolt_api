steal(
    'mad/controller/component/menuController.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.AppNavigationRightController
	 * @inherits {mad.controller.component.MenuController}
	 * @parent index
	 * 
	 * Navigation right controller
	 * 
	 * @constructor
	 * Instantiate the application navigation right controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.AppNavigationRightController}
	 */
	mad.controller.component.MenuController.extend('passbolt.controller.component.AppNavigationRightController', /** @static */ {

		'defaults': {}

	}, /** @prototype */ {

		/**
		 * After start. Init the menu items
		 * @return {void}
		 */
		'afterStart': function () {
			var menuItems = [
				new mad.model.Action({
					'id': uuid(),
					'label': __('logout'),
					'cssClasses': ['logout'],
					'action': function () {
						document.location.href = APP_URL + '/logout';
					}
				})
			];
			this.load(menuItems);
		}

	});
});
