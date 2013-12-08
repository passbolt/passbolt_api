steal(
	'mad/controller/component/freeCompositeController.js',
    'app/controller/component/groupChooserController.js',
    'app/controller/component/userBrowserController.js',
	'app/controller/component/userWorkspaceMenuController.js',
	'app/controller/form/user/createFormController.js',
	'app/model/user.js'
).then(function () {

	/*
	 * @class passbolt.controller.PeopleWorkspaceController
	 * @inherits {mad.controller.component.WorkspaceController}
	 * @parent index 
	 * 
	 * @constructor
	 * Instanciates a new People Workspace Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.PeopleWorkspaceController}
	 */
	mad.controller.component.FreeCompositeController.extend('passbolt.controller.PeopleWorkspaceController', /** @static */ {

		'defaults': {
			'label': 'People',
			'templateUri': 'app/view/template/peopleWorkspace.ejs',
			'selectedUsers': new can.Model.List()
		}

	}, /** @prototype */ {

        /**
         * Called right after the start function
         * @return {void}
         * @see {mad.controller.ComponentController}
         */
        'afterStart': function() {
			// Kill the primary workspace menu controller, if exists
			$('#js_wsp_primary_menu').html(''); // TODO : modify this by a proper destroy function
			// Instantiate the primary workspace menu controller
			this.primMenu = new passbolt.controller.component.UserWorkspaceMenuController('#js_wsp_primary_menu', {
				'selectedUsers': this.options.selectedUsers
			});
			this.primMenu.start();

            // Instanciate the group chooser controller
            this.grpChooser = new passbolt.controller.component.GroupChooserController('#js_wsp_users_group_chooser', {});
            this.grpChooser.start();

            // Instanciate the passwords browser controller
            var userBrowserController = this.addComponent(passbolt.controller.component.UserBrowserController, {
                'id': 'js_passbolt_user_browser',
                'selectedUsers': this.options.selectedUsers
            }, 'js_workspace_users_main');
            userBrowserController.start();
        },

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when the user requests a category creation
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{mad.bus} request_user_creation': function (el, ev, data) {
			// get the category from the filter
			/*var categories = [];
			 this.options.filter.tags.each(function(val, i){
			 categories.push({
			 'id': val.id
			 });
			 });*/
			// create the resource which will be used by the form builder to populate the fields
			var user = new passbolt.model.User({active:1});

			// get the dialog
			var dialog = new mad.controller.component.DialogController(null, {label: __('Add User')})
				.start();

			// attach the component to the dialog
			var form = dialog.add(passbolt.controller.form.user.CreateFormController, {
				data: user,
				callbacks : {
					submit: function (data) {
						var user = new passbolt.model.User(data['passbolt.model.User']);
						user.save();
						dialog.remove();
						// TODO : remove only in case of success
					}
				}
			});
			form.load(user);
		},

		/**
		 * Observe when the user requests a user edition
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.User} resource The target user to edit
		 * @return {void}
		 */
		'{mad.bus} request_user_edition': function (el, ev, user) {
			var self = this;
			// Retrieve the selected user
			user = this.options.selectedUsers[0];
			// get the dialog
			var dialog = new mad.controller.component.DialogController(null, {label: __('Edit User')})
				.start();
			// attach the component to the dialog
			var form = dialog.add(passbolt.controller.form.user.CreateFormController, {
				data: user,
				callbacks : {
					submit: function (data) {
						//var user = new passbolt.model.User(data['passbolt.model.User']);
						user.attr(data['passbolt.model.User']).save();
						dialog.remove();
						// TODO : remove only in case of success
					}
				}
			});
			form.load(user);
		},

		/**
		 * Observe when the user requests a user deletion
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.User} user1 A target user to delete
		 * @param {passbolt.model.User} [user2 ...] Other users to delete
		 * @return {void}
		 */
		'{mad.bus} request_user_deletion': function (el, ev) {
			for (var i=2; i<arguments.length; i++) {
				var user = arguments[i];
				if (!(user instanceof passbolt.model.User)) {
					throw new mad.error.Exception('The parameter ' + i + ' should be an instance of passbolt.model.User');
				}
				user.destroy();
			}
		}
	});

});