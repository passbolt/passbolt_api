import 'mad/component/menu';

/**
 * @inherits mad.component.Tree
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
 * @return {passbolt.component.ResourceShortcuts}
 */
var ResourceShortcuts = passbolt.component.ResourceShortcuts = mad.component.Menu.extend('passbolt.component.ResourceShortcuts', /** @static */ {

	'defaults': {}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function() {
		var menuItems = [
			new mad.model.Action({
				'id': 'js_pwd_wsp_filter_all',
				'label': __('All items'),
				'action': function () {
					var filter = new passbolt.model.Filter({
						'label': __('All items'),
						'order': 'modified',
                        'case' : 'all_items',
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
                        'case' : 'recently_modified',
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
        // Select first item.
        this.selectItem(menuItems[0]);
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Listen to the browser filter
	 * @param {jQuery} element The source element
	 * @param {Event} event The jQuery event
	 * @param {passbolt.model.Filter} filter The filter to apply
	 */
	'{mad.bus.element} filter_resources_browser': function (element, evt, filter) {
		if (filter.type != passbolt.model.Filter.SHORTCUT &&
			filter.type != passbolt.model.Filter.KEYWORD) {
			this.unselectAll();
		}
	}
});

export default ResourceShortcuts;
