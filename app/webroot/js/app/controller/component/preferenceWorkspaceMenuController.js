steal(
	'mad/controller/componentController.js',
	'app/view/template/component/preferenceWorkspaceMenu.ejs'
).then(function () {

		/*
		 * @class passbolt.controller.component.PreferenceWorkspaceMenuController
		 * @inherits mad.controller.component.ComponentController
		 * @parent index
		 *
		 * Our passbolt preference workspace menu controller
		 *
		 * @constructor
		 * Creates a new preference workspace menu controller
		 *
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the controller.  These get added to
		 * this.options and merged with defaults static variable
		 * @return {passbolt.controller.component.PreferenceWorkspaceMenuController}
		 */
		mad.controller.ComponentController.extend('passbolt.controller.component.PreferenceWorkspaceMenuController', /** @static */ {
			'defaults': {
				'label': 'Preference Workspace Menu Controller'
			}

		}, /** @prototype */ {

			/**
			 * after start hook.
			 * @return {void}
			 */
			'afterStart': function () {
				var self = this;

				// Manage edition action
				this.options.editionButton = new mad.controller.component.ButtonController($('#js_preference_wk_menu_edition_button'))
					.start();

				this.on();
			},

			/* ************************************************************** */
			/* LISTEN TO THE APP EVENTS */
			/* ************************************************************** */

			/**
			 * Observe when the user wants to edit an instance (Resource, User depending of the active workspace)
			 * @param {HTMLElement} el The element the event occured on
			 * @param {HTMLEvent} ev The event which occured
			 * @return {void}
			 */
			'{editionButton} click': function (el, ev) {
				mad.bus.trigger('request_profile_edition');
			}

			/* ************************************************************** */
			/* LISTEN TO THE STATE CHANGES */
			/* ************************************************************** */
		});

	});