steal(
    'mad/controller/componentController.js',
	'app/view/template/component/workspaceSecondaryMenu.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.component.WorkspaceSecondaryMenuController
	 * @inherits mad.controller.component.ComponentController
	 * @parent index 
	 * 
	 * Our passbolt workspace secondary menu controller
	 * 
	 * @constructor
	 * Creates a new workspace secondary menu controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller. These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.WorkspaceSecondaryMenuController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.WorkspaceSecondaryMenuController', /** @static */ {

		'defaults': {
			'label': 'Workspace Secondary Menu Controller'
		}

	}, /** @prototype */ {

		/**
		 * after start hook.
		 * @return {void}
		 */
		'afterStart': function () {
			// Manage the display of the sidebar
			this.options.viewSidebarButton = new mad.controller.component.ButtonController($('#js_wk_secondary_menu_view_sidebar_button'))
				.start();
			
			// Manage the layout as grid action
			this.options.gridLayoutButton = new mad.controller.component.ButtonController($('#js_wk_secondary_menu_grid_layout_button'))
				.start();
			
			// Manage the layout as box action
			this.options.boxLayoutButton = new mad.controller.component.ButtonController($('#js_wk_secondary_menu_box_layout_button'), {
				'state': 'disabled'
			}).start();
			
			// Manage the config action
			this.options.configButton = new mad.controller.component.ButtonController($('#js_wk_secondary_menu_config_button'), {
				'state': 'disabled'
			}).start();
			
			// Rebind controller events
			this.on();
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */
		
		/**
		 * Observe when the user wants to view the side bar
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{viewSidebarButton} click': function (el, ev) {
			console.log('viewSidebarButton');
		},

		/**
		 * Observe when the user wants to see the browser as grid
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{gridLayoutButton} click': function (el, ev) {
			console.log('gridLayoutButton');
		},

		/**
		 * Observe when the user wants to see the browser as boxes
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{boxLayoutButton} click': function (el, ev) {
			console.log('boxLayoutButton');
		},

		/**
		 * Observe when the user wants to see the extra config
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'{configButton} click': function (el, ev) {
			console.log('configButton');
		},

	});

});