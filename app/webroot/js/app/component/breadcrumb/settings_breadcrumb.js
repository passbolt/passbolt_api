import 'mad/component/component';
import 'app/view/template/component/breadcrumb/breadcrumb.ejs!';
import 'app/view/template/component/breadcrumb/breadcrumb_item.ejs!';

/**
 * @inherits {mad.Component}
 * @parent index
 *
 * The settings Breadcrumb will allow the user to know where he is.
 *
 * @constructor
 * Instantiate the settings breadcrumb controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.SettingsBreadcrumb}
 */
var SettingsBreadcrumb = passbolt.component.SettingsBreadcrumb= mad.Component.extend('passbolt.component.SettingsBreadcrumb', /** @static */ {

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
		this.options.menu = new mad.component.Menu(
			menuSelector, {
				itemTemplateUri: 'app/view/template/component/breadcrumb/breadcrumb_item.ejs'
			}
		);
		this.options.menu.start();

		// Store menu items in an array.
		// This contains the static part of the menu.
		this.menuItems = [];
		// Contains the specific section menu items.
		this.sectionMenuItems = [];

		// First 2 items of the menu are constant.
		var menuItem = new mad.model.Action({
			id: uuid(),
			label: __('All users'),
			action: function () {
				// Add a link to filter on all items as first item.
				var filter = new passbolt.model.Filter({
					label: __('All users'),
					type: passbolt.model.Filter.SHORTCUT
				});
				// Switch to people workspace.
				mad.bus.trigger('request_workspace', 'people');
				// Set filter.
				mad.bus.trigger('filter_users_browser', filter);
			}
		});
		this.menuItems.push(menuItem);

		var menuItem = new mad.model.Action({
			id: uuid(),
			label: passbolt.model.User.getCurrent().Profile.first_name + ' ' + passbolt.model.User.getCurrent().Profile.last_name,
			action: function () {
				// Switch to people workspace.
				mad.bus.trigger('request_settings_section', 'profile');
			}
		});
		this.menuItems.push(menuItem);

		// Specific menu items, per section.
		// profile section.
		this.sectionMenuItems['profile'] = [
			new mad.model.Action({
				id: uuid(),
				label: __('Profile'),
				action: function () {
					return;
				}
			})
		];
		// keys section.
		this.sectionMenuItems['keys'] = [
			new mad.model.Action({
				id: uuid(),
				label: __('Keys management'),
				action: function () {
					return;
				}
			})
		];
	},

	/**
	 * Load the current filter
	 */
	load: function () {
		// To use if we need to load something.
		// Do not remove, it breaks the code.
	},

	/**
	 * Destroy the workspace.
	 */
	destroy: function() {
		// Be sure that the primary workspace menu controller will be destroyed also.
		$('#' + this.getId() + ' ul').empty();
		this._super();
	},

	/**
	 * Refresh the menu items as per the section.
	 * @param section
	 */
	refreshMenuItems: function(section) {
		// The items of the menu are a combination of static items and section dynamic items.
		// If the section is recognised, we just assemble the 2 arrays. Otherwise, we just keep the static part.
		var menuItems = (this.sectionMenuItems[section] !== undefined) ?
			$.merge($.merge([], this.menuItems), this.sectionMenuItems[section]) : this.menuItems;
		// Reset the menu.
		this.options.menu.reset();
		// Load the items.
		this.options.menu.load(menuItems);
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Listen to request_settings_section event.
	 * @param el
	 * @param ev
	 * @param section
	 */
	'{mad.bus.element} request_settings_section': function (el, ev, section) {
		// When the section changes, we refresh the menu items in the breadcrumb.
		this.refreshMenuItems(section);
	}

});

export default SettingsBreadcrumb;