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
                'selectedRs': this.options.selectedRs
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
			var user = new passbolt.model.User();

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
		}


	});

});