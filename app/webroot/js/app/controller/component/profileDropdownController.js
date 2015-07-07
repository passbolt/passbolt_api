steal(
	'mad/controller/componentController.js',
	//'app/view/component/profileDropdown.js',
	'app/view/template/component/profileDropdown.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.ProfileDropdownController
	 * @inherits mad.controller.component.ComponentController
	 * @parent index
	 *
	 * @constructor
	 * Creates a new Profile Dropdown controller
	 *
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {passbolt.controller.ProfileDropdownController}
	 */
	mad.controller.component.ButtonDropdownController.extend('passbolt.controller.component.ProfileDropdownController', /** @static */ {

		'defaults': {
			'label': null,
			'cssClasses': [],
			'templateBased': true,
			'templateUri': 'app/view/template/component/profileDropdown.ejs',
			'contentElement': '#js_app_profile_dropdown .dropdown-content',
			'user': null
		}

	}, /** @prototype */ {

		// after start
		'afterStart': function() {
			this._super();
			var self = this;

			// Set current user.
			self.options.user = passbolt.model.User.getCurrent();

			// Add my profile action.
			var action = new mad.model.Action({
				'id': uuid(),
				'label': 'my profile',
				//'cssClasses': ['separator-after'],
				'action': function (menu) {
					mad.bus.trigger('workspace_selected', 'settings');
					mad.bus.trigger('request_settings_section', 'profile');
					self.view.close();
				}
			});
			this.options.menu.insertItem(action);

			// Add manage your keys action.
			var action = new mad.model.Action({
				'id': uuid(),
				'label': 'manage your keys',
				//'cssClasses': ['separator-after'],
				'action': function (menu) {
					mad.bus.trigger('workspace_selected', 'settings');
					mad.bus.trigger('request_settings_section', 'keys');
					self.view.close();
				}
			});
			this.options.menu.insertItem(action);

			// Add my profile action
			var action = new mad.model.Action({
				'id': uuid(),
				'label': 'logout',
				//'cssClasses': ['separator-after'],
				'action': function (menu) {
					document.location.href = APP_URL + '/logout';
				}
			});
			this.options.menu.insertItem(action);
		},

		/**
		 * Before render.
		 */
		'beforeRender': function() {
			this._super();
			this.setViewData('user', this.options.user);
		},

		/* ************************************************************** */
		/* LISTEN TO THE MODEL EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user is updated
		 * @param {passbolt.model.User} user The updated user
		 * @return {void}
		 */
		'{user} updated': function (user) {
			// The reference of the user does not change, refresh the component
			if(!this.state.is('disabled') && !this.state.is(null)) {
				this.refresh();
			}
		}

	});

});