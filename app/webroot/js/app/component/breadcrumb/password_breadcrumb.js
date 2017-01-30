import 'mad/component/component';
import 'app/view/template/component/breadcrumb/breadcrumb.ejs!';
import 'app/view/template/component/breadcrumb/breadcrumb_item.ejs!';

/**
 * @inherits {mad.Component}
 * @parent index
 *
 * The password Breadcrumb will allow the user to know where he is.
 *
 * @constructor
 * Instantiate the password breadcrumb controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.PasswordBreadcrumb}
 */
var PasswordBreadcrumb = passbolt.component.PasswordBreadcrumb= mad.Component.extend('passbolt.component.PasswordBreadcrumb', /** @static */ {

	defaults: {
		// Template
		templateUri: 'app/view/template/component/breadcrumb/breadcrumb.ejs',
		// Hidden by default
		status: 'hidden',
		// The filter to display
		filter: null
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		// Create and render menu in the created container.
		var menuSelector = '#' + this.getId() + ' ul';
		this.options.menu = new mad.component.Menu(menuSelector, {
			itemTemplateUri: 'app/view/template/component/breadcrumb/breadcrumb_item.ejs'
		});
		this.options.menu.start();
	},

	/**
	 * Parse the current filter
	 * @param {passbolt.model.Filter} filter The filter to load
	 * @return {array}
	 */
	parseFilter: function (filter) {
		var menuItems = [];

		// Add a link to filter on all items as first item.
		var menuItem = new mad.model.Action({
			id: uuid(),
			label: __('All items'),
			action: function () {
				var filter = new passbolt.model.Filter({
					label: __('All items'),
					order: 'modified',
					case: 'all_items',
					type: passbolt.model.Filter.SHORTCUT
				});
				mad.bus.trigger('filter_workspace', filter);
			}
		});
		menuItems.push(menuItem);

		// If we want to filter on keywords.
		if (typeof filter.keywords != 'undefined' && filter.keywords != '') {
			// Add the search keywords to the breadcrumb.
			var menuItem = new mad.model.Action({
				id: uuid(),
				label: __('Search : %s', filter.keywords)
			});
			menuItems.push(menuItem);
		}
		// Case filter
		else {
			if (typeof filter.label != 'undefined'
				&& filter.label != __('All items')) {
				var menuItem = new mad.model.Action({
					id: uuid(),
					label: filter.label
				});
				menuItems.push(menuItem);
			}
		}

		return menuItems;
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
	'{mad.bus.element} filter_workspace': function (element, evt, filter) {
		this.options.menu.reset();
		var menuItems = this.parseFilter(filter);
		this.options.menu.load(menuItems);
	}

});

export default PasswordBreadcrumb;
