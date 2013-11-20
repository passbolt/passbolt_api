steal(
	'mad/controller/component/freeCompositeController.js',
    'app/controller/component/groupChooserController.js',
    'app/controller/component/userBrowserController.js'
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
			'templateUri': 'app/view/template/peopleWorkspace.ejs'
		}

	}, /** @prototype */ {

        /**
         * Called right after the start function
         * @return {void}
         * @see {mad.controller.ComponentController}
         */
        'afterStart': function() {
            // Instanciate the group chooser controller
            this.grpChooser = new passbolt.controller.component.GroupChooserController('#js_wsp_users_group_chooser', {});
            this.grpChooser.start();

            // Instanciate the passwords browser controller
            var userBrowserController = this.addComponent(passbolt.controller.component.UserBrowserController, {
                'id': 'js_passbolt_user_browser',
                'selectedRs': this.options.selectedRs
            }, 'js_workspace_users_main');
            userBrowserController.start();
        }

	});

});