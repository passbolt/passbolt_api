steal(
	'mad/controller/component/menuController.js'
).then(function () {

	/*
	 * @class passbolt.controller.UserShortcutsController
	 * @inherits mad.controller.component.TreeController
	 * @parent index 
	 * 
	 * Our users shortcuts component.
	 * It will allow the user to filter the users browser.
	 * 
	 * @constructor
	 * Creates a new User Shortcuts Controller.
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.UserShortcutsController}
	 */
	mad.controller.component.MenuController.extend('passbolt.controller.component.UserShortcutsController', /** @static */ {

		'defaults': {}

	}, /** @prototype */ {

		'afterStart': function() {
			var menuItems = [
				new mad.model.Action({
					'id': uuid(),
					'label': __('All users'),
					'action': function () {
						var filter = new passbolt.model.Filter({
							'order': 'modified'
						});
						mad.bus.trigger('filter_users_browser', filter);
					}
				})
			];
			this.load(menuItems);
		}

	});

});