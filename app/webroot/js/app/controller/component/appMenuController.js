steal(
    MAD_ROOT + '/controller/component/menuController.js'
).then(function ($) {

	/*
	 * @class passbolt.controller.component.AppMenuController
	 * @inherits {mad.controller.component.MenuController}
	 * @parent index
	 * 
	 * 
	 * @constructor
	 * Instanciate the application menu controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.AppMenuController}
	 */
	mad.controller.component.MenuController.extend('passbolt.controller.component.AppMenuController', /** @static */ {

	}, /** @prototype */ {

		'init': function () {
			this._super();
			this.initMenuItems();
		},

		'initMenuItems': function () {
			var menuItems = [
				{ 'MenuItem': new mad.model.MenuItem({
					'id': uuid(),
					'label': 'passwords',
					'action': function () {
						passbolt.eventBus.trigger('workspace_selected', 'js_passbolt_passwordWorkspace_controller');
					}
				})
			}, { 'MenuItem': new mad.model.MenuItem({
					'id': uuid(),
					'label': 'people & groups',
					'action': function () {
						passbolt.eventBus.trigger('workspace_selected', 'js_passbolt_peopleWorkspace_controller');
					}
				})
			}];
			this.load(menuItems);
		}

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

	});
});