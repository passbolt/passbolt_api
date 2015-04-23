steal(
	'mad/controller/componentController.js',
	'app/view/template/component/breadcrumb/breadcrumb.ejs',
	'app/view/template/component/breadcrumb/breadcrumbItem.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.component.PreferenceBreadcrumbController
	 * @inherits {mad.controller.ComponentController}
	 * @parent index
	 *
	 * The preference Breadcrumb will allow the user to know where he is.
	 *
	 * @constructor
	 * Instantiate the preference breadcrumb controller
	 *
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {passbolt.controller.component.PreferenceBreadcrumbController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.PreferenceBreadcrumbController', /** @static */ {

		'defaults': {
			// Template
			'templateUri': 'app/view/template/component/breadcrumb/breadcrumb.ejs',
			// Hidden by default
			'status': 'hidden',
			// The filter to display
			'filter': null
		}

	}, /** @prototype */ {

		/**
		 * Called right after the start function
		 * @return {void}
		 * @see {mad.controller.ComponentController}
		 */
		'afterStart': function () {
			// Create and render menu in the created container.
			var menuSelector = '#' + this.getId() + ' ul';
			this.options.menu = new mad.controller.component.MenuController(menuSelector, {
				'itemTemplateUri': 'app/view/template/component/breadcrumb/breadcrumbItem.ejs'
			});
			this.options.menu.start();
		},

		/**
		 * Parse the current filter
		 * @param {passbolt.model.Filter} filter The filter to load
		 * @return {array}
		 */
		'parseFilter': function (filter) {
			var menuItems = [];

			// Add a link to filter on all items as first item.
			var menuItem = new mad.model.Action({
				'id': uuid(),
				'label': __('All users'),
				'action': function () {
					var filter = new passbolt.model.Filter({
						'label': __('All users'),
						'type': passbolt.model.Filter.SHORTCUT
					});
					// Switch to people workspace.
					mad.bus.trigger('workspace_selected', 'people');
					// Set filter.
					mad.bus.trigger('filter_users_browser', filter);
				}
			});
			menuItems.push(menuItem);

			var menuItem = new mad.model.Action({
				'id': uuid(),
				'label': passbolt.model.User.getCurrent().Profile.first_name + ' ' + passbolt.model.User.getCurrent().Profile.last_name,
				'action': function () {
					return;
				}
			});
			menuItems.push(menuItem);

			var menuItem = new mad.model.Action({
				'id': uuid(),
				'label': __('Profile'),
				'action': function () {
					return;
				}
			});
			menuItems.push(menuItem);

			return menuItems;
		},

		/**
		 * Load the current filter
		 * @param {passbolt.model.Filter} filter The filter to load
		 */
		'load': function (filter) {
			var menuItems = this.parseFilter(filter);

			this.options.menu.reset();
			this.options.menu.load(menuItems);
		}

	});
});
