steal(MAD_ROOT + '/controller/component/workspaceController.js')

.then(function ($) {

	mad.controller.ComponentController.extend('passbolt.sample.controller.AjaxSampleWorkspaceController',
	/** @static */
	{
		'defaults': {
			'label': 'Ajax Sample Workspace'
//			'templateUri': '//app/plugin/sample/view/template/ajaxSampleWorkspace.ejs'
		}
	},
	/** @prototype */
	{
		// constructor like
		'init': function (el, options) {
			this._super();
			this.render(); // render the component to be used by the others
			
			// Demonstrate a simple request
			// Demonstrate a request with object mapping
			// Demonstrate error request
			
		},

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Ready.
		 * The ready state is fired automatically after the Component is rendered
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateReady': function (go) {
			
		}

	});

});