steal(
	'mad/controller/component/menuController.js'
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
	 * @param {Object} [options] option values for the controller. These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.ResourceShortcutsController}
	 */
	mad.controller.component.MenuController.extend('passbolt.controller.component.ResourceShortcutsController', /** @static */ {

		'defaults': {}

	}, /** @prototype */ {

		'afterStart': function() {
			var menuItems = [
				new mad.model.Action({
					'id': 'js_pwd_wsp_filter_all',
					'label': __('All items'),
					'cssClasses': ['selected'],
					'action': function () {
						var filter = new passbolt.model.Filter({
							'label': __('All items'),
							'order': 'modified',
							'type': passbolt.model.Filter.SHORTCUT
						});
						mad.bus.trigger('filter_resources_browser', filter);
					}
				}), new mad.model.Action({
					'id': 'js_pwd_wsp_filter_favorite',
					'label': __('Favorite'),
					'action': function () {
						var filter = new passbolt.model.Filter({
							'label': __('Favorite'),
							'case': 'favorite',
							'type': passbolt.model.Filter.SHORTCUT
						});
						mad.bus.trigger('filter_resources_browser', filter);
					}
				}), new mad.model.Action({
					'id': 'js_pwd_wsp_filter_modified',
					'label': __('Recently modified'),
					'action': function () {
						var filter = new passbolt.model.Filter({
							'label': __('Recently modified'),
							'order': 'modified',
							'type': passbolt.model.Filter.SHORTCUT
						});
						mad.bus.trigger('filter_resources_browser', filter);
					}
				}), new mad.model.Action({
					'id': 'js_pwd_wsp_filter_share',
					'label': __('Shared with me'),
					'action': function () {
						var filter = new passbolt.model.Filter({
							'label': __('Shared with me'),
							'case': 'shared',
							'type': passbolt.model.Filter.SHORTCUT
						});
						mad.bus.trigger('filter_resources_browser', filter);
					}
				}), new mad.model.Action({
					'id': 'js_pwd_wsp_filter_own',
					'label': __('Items I own'),
					'action': function () {
						var filter = new passbolt.model.Filter({
							'label': __('Items I own'),
							'case': 'own',
							'type': passbolt.model.Filter.SHORTCUT
						});
						mad.bus.trigger('filter_resources_browser', filter);
					}
				})
			];
			this.load(menuItems);
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Listen to the browser filter
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @param {passbolt.model.Filter} filter The filter to apply
		 * @return {void}
		 */
		'{mad.bus} filter_resources_browser': function (element, evt, filter) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			if (filter.type != passbolt.model.Filter.SHORTCUT) {
				this.unselectAll();
			}
		}
	});

});