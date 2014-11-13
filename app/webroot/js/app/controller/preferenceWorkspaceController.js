steal(
	'mad/controller/componentController.js',
	'app/controller/component/preferenceMenuController.js',
	'app/controller/component/profileController.js',
	'app/controller/form/user/createFormController.js',
	'app/controller/form/user/passwordFormController.js',
	'app/controller/form/user/avatarFormController.js',
	'app/view/template/preferenceWorkspace.ejs'
).then(function () {

		/*
		 * @class passbolt.controller.PreferenceWorkspaceController
		 * @inherits {mad.controller.ComponentController}
		 * @parent index
		 *
		 * @constructor
		 * Instanciates a new Preference Workspace Controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.PreferenceWorkspaceController}
		 */
		mad.controller.ComponentController.extend('passbolt.controller.PreferenceWorkspaceController', /** @static */ {
			defaults: {
				'label': 'Preference',
				'templateUri': 'app/view/template/preferenceWorkspace.ejs'
			}
		}, /** @prototype */ {

			/**
			 * Called right after the start function
			 * @return {void}
			 * @see {mad.controller.ComponentController}
			 */
			'afterStart': function() {
				var self = this;

				// Instanciate the preference menu
				var preferenceWkMenu = new passbolt.controller.component.PreferenceMenuController('#js_wk_preference_menu', {});
				preferenceWkMenu.start();

				// Instanciate the main tabs controller
				this.preferenceTabsCtl = new mad.controller.component.TabController('#js_wk_preference_main', {
					'autoMenu': false // do not generate automatically the associated tab nav
				});
				this.preferenceTabsCtl.start();

				// Instantiate the profile component
				passbolt.model.User.findOne({
					'id': mad.Config.read('user.id'),
					'async': false
				}).then(function(user) {
					self.profileCtl = self.preferenceTabsCtl.addComponent(passbolt.controller.component.ProfileController, {
						'id': 'js_preference_wk_profile_controller',
						'label': 'profile',
						'user': user
					});
				});
			},

			/* ************************************************************** */
			/* LISTEN TO THE APP EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when the user requests a profile edition
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{mad.bus} request_profile_edition': function (el, ev, user) {
				var self = this;

				// get the dialog
				var dialog = new mad.controller.component.DialogController(null, {label: __('Edit User')})
					.start();
				// attach the component to the dialog
				var form = dialog.add(passbolt.controller.form.user.CreateFormController, {
					data: user,
					action: 'edit',
					callbacks : {
						submit: function (data) {
							user.attr(data['passbolt.model.User']).save();
							dialog.remove();
						}
					}
				});
				form.load(user);
			},

			/**
			 * Observe when the user requests a password edition
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{mad.bus} request_user_password_edition': function (el, ev, user) {
				var self = this;

				// get the dialog
				var dialog = new mad.controller.component.DialogController(null, {label: __('Edit User Password')})
					.start();
				// attach the component to the dialog
				var form = dialog.add(passbolt.controller.form.user.PasswordFormController, {
					data: user,
					callbacks : {
						submit: function (data) {
							user.attr(data['passbolt.model.User'])
								.savePassword();
							dialog.remove();
						}
					}
				});
				form.load(user);
			},

			/**
			 * Observe when the user requests an avatar edition
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{mad.bus} request_profile_avatar_edition': function (el, ev, user) {
				var self = this;

				// get the dialog
				var dialog = new mad.controller.component.DialogController(null, {label: __('Edit User Password')})
					.start();
				// attach the component to the dialog
				var form = dialog.add(passbolt.controller.form.user.AvatarFormController, {
					data: user,
					callbacks : {
						submit: function (data) {
							var $fileField = $('#js_field_avatar');
							user.saveAvatar($fileField[0].files[0]);
							dialog.remove();
						}
					}
				});
				form.load(user);
			},

			/* ************************************************************** */
			/* LISTEN TO THE STATE CHANGES */
			/* ************************************************************** */

			/**
			 * The application is ready.
			 * @param {boolean} go Enter or leave the state
			 * @return {void}
			 */
			'stateReady': function (go) {
				// Enable the target preference screen
				var prefScreenId = 'js_preference_wk_profile_controller';
				var prefContainer = mad.app.getComponent('js_wk_preference_main');
				prefContainer.enableTab(prefScreenId);
			}

		});
	});
