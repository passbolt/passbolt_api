steal(
	'mad/controller/componentController.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.ProfileController
	 * @inherits {mad.controller.ComponentController}
	 * @parent index 
	 * 
	 * Our profile controller
	 * 
	 * @constructor
	 * Creates a new Profile Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.ProfileController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.ProfileController', /** @static */ {

		'defaults': {
			// The target user
			'user': null
		}

	}, /** @prototype */ {

		/**
		 * Before render.
		 */
		'beforeRender': function() {
			this._super();
			this.setViewData('user', this.options.user);
		},

		/**
		 * After start.
		 */
		'afterStart': function() {
			var self = this;
			this._super();

			// List defined permissions
			//var groupList = new mad.controller.component.TreeController($('#js_wk_preference_profile_groups'), {
			//	'cssClasses': ['groups'],
			//	'viewClass': mad.view.component.Tree,
			//	'itemClass': passbolt.model.Group,
			//	//'templateUri': 'mad/view/template/component/tree.ejs',
			//	//'itemTemplateUri': 'app/view/template/component/permission/permissionListItem.ejs',
			//	// The map to use to make jstree working with our category model
			//	'map': new mad.object.Map({
			//		'id': 'id',
			//		'label': 'name'
			//	})
			//});
			//groupList.start();
			//groupList.load(this.options.user.Group);
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
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * The user want to edit his personal information
		 */
		'.edit-action click': function(el, ev) {
			mad.bus.trigger('request_profile_edition', this.options.user);
		},

		/**
		 * The user want to edit his password
		 */
		'.edit-password-action click': function(el, ev) {
			mad.bus.trigger('request_user_password_edition', this.options.user);
		},

		/**
		 * The user want to edit his avatar
		 */
		'.edit-avatar-action click': function(el, ev) {
			mad.bus.trigger('request_profile_avatar_edition', this.options.user);
		}
	});

});