steal(
	'mad/controller/component/workspaceController.js'
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
	mad.controller.component.WorkspaceController.extend('passbolt.controller.PeopleWorkspaceController', /** @static */ {

		'defaults': {
			'label': 'People',
			'templateUri': 'app/view/template/peopleWorkspace.ejs'
		}

	}, /** @prototype */ {

	});

});