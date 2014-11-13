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

		'defaults': {}

	}, /** @prototype */ {

		'afterStart': function() {
			var menuItems = [
				new mad.model.Action({
					'id': uuid(),
					'label': __('My profile'),
					'cssClasses': ['selected'],
					'action': function () {

					}
				}),
				new mad.model.Action({
					'id': uuid(),
					'label': __('Manage your keys'),
					'action': function () {

					}
				})
			];
			this.load(menuItems);
		}
	});

});