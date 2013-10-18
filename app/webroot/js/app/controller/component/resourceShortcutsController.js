steal(
	'mad/controller/component/menuController.js'
	// 'mad/controller/component/treeController.js'
).then(function () {

	/*
	 * @class passbolt.controller.ResourceShortcutsController
	 * @inherits mad.controller.component.TreeController
	 * @parent index 
	 * 
	 * Our resources shortcuts component.
	 * It will allow the user to filter the resources browser.
	 * 
	 * @constructor
	 * Creates a new Password Shortcuts Controller.
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.ResourceShortcutsController}
	 */
	mad.controller.component.MenuController.extend('passbolt.controller.component.ResourceShortcutsController', /** @static */ {

		'defaults': {}

	}, /** @prototype */ {

		'afterStart': function() {
			var menuItems = [
				new mad.model.Action({
					'id': uuid(),
					'label': __('All items'),
					'action': function () {
						var filter = new passbolt.model.Filter({
							'order': 'modified'
						});
						mad.bus.trigger('filter_resources_browser', filter);
					}
				}), new mad.model.Action({
					'id': uuid(),
					'label': __('Favorite'),
					'action': function () {
						var filter = new passbolt.model.Filter({
							'filter': 'favorite'
						});
						mad.bus.trigger('filter_resources_browser', filter);
					}
				// }), new mad.model.Action({
					// 'id': uuid(),
					// 'label': __('Most used'),
					// 'action': function () {
					// }
				}), new mad.model.Action({
					'id': uuid(),
					'label': __('Recently modified'),
					'action': function () {
						var filter = new passbolt.model.Filter({
							'order': 'modified'
						});
						mad.bus.trigger('filter_resources_browser', filter);
					}
				}), new mad.model.Action({
					'id': uuid(),
					'label': __('Expiring soon'),
					'action': function () {
						var filter = new passbolt.model.Filter({
							'order': 'expiry_date'
						});
						mad.bus.trigger('filter_resources_browser', filter);
					}
				}), new mad.model.Action({
					'id': uuid(),
					'label': __('Shared with me'),
					'action': function () {
						var filter = new passbolt.model.Filter({
							'filter': 'shared'
						});
						mad.bus.trigger('filter_resources_browser', filter);
					}
				}), new mad.model.Action({
					'id': uuid(),
					'label': __('Items I own'),
					'action': function () {
						var filter = new passbolt.model.Filter({
							'filter': 'own'
						});
						mad.bus.trigger('filter_resources_browser', filter);
					}
				})
			];
			this.load(menuItems);
		}

	});

});