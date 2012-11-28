steal(
    'mad/controller/component/menuController.js'
).then(function () {

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
	 * @todo pas propre mais separe les boutons du workspace, think to something cleaner
	 */
	mad.controller.component.MenuController.extend('passbolt.controller.component.AppMenuController', /** @static */ {

	}, /** @prototype */ {

		'init': function (el, options) {
			this._super(el, options);
		},

		'initMenuItems': function () {
			var menuItems = [
				new mad.model.Action({
					'id': uuid(),
					'label': 'home',
					'cssClasses': ['home'],
					'action': function () {
						passbolt.eventBus.trigger('workspace_selected', 'js_passbolt_passwordWorkspace_controller');
					}
				}), new mad.model.Action({
					'id': uuid(),
					'label': 'passwords',
					'action': function () {
						passbolt.eventBus.trigger('workspace_selected', 'js_passbolt_passwordWorkspace_controller');
					}
				}), new mad.model.Action({
					'id': uuid(),
					'label': 'people',
					'action': function () {
						passbolt.eventBus.trigger('workspace_selected', 'js_passbolt_peopleWorkspace_controller');
					}
				}), new mad.model.Action({
					'id': uuid(),
					'label': 'help',
					'action': function () {
						passbolt.eventBus.trigger('workspace_selected', 'js_passbolt_passwordWorkspace_controller');
					}
				})
			];
			this.load(menuItems);
		}

	});
});