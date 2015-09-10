steal(
	'mad/controller/componentController.js',
	'mad/form/element/listController.js',
	'app/view/component/appFilter.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.AppFilterController
	 * @inherits {mad.controller.ComponentController}
	 * @parent index
	 * 
	 * The application Filter will allow the user to filter the different workspaces of the application through a
	 * simple textbox and some fancy widgets such as a list of tags.
	 * 
	 * @constructor
	 * Instantiate the application filter controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.AppFilterController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.AppFilterController', /** @static */ {

		'defaults': {
			'viewClass': passbolt.view.component.AppFilter
		}

	}, /** @prototype */ {

		/**
		 * After start hook.
		 * Init the embedded components
		 */
		'afterStart': function (options) {
			// Instantiate the filter form
			this.filterForm = new mad.form.FormController('#js_app_filter_form', {});
			this.filterForm.start();
			
			// Instantiate the textbox which will get the user search
			this.keywordsFormElement = this.filterForm.addElement(new mad.form.element.TextboxController('#js_app_filter_keywords', {
				modelReference: 'passbolt.model.Filter.keywords'
			}));
			this.keywordsFormElement.start();
			this.workspace = 'password';
		},

		/**
		 * Reset the filter
		 * @return {void}
		 */
		'reset': function () {
			this.keywordsFormElement.setValue('');
		},

		/* ************************************************************** */
		/* LISTEN TO APP EVENTS */
		/* ************************************************************** */

		/**
		 * Observe when category is selected
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {passbolt.model.Category} category The selected category
		 * @return {void}
		 */
		'{mad.bus} category_selected': function (el, ev, category) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			this.reset();
		},

		/**
		 * Observe when a workspace is selected.
		 * @param {HTMLElement} el
		 * @param {HTMLEvent} event
		 * @param workspace
		 */
		'{mad.bus} workspace_selected': function (el, event, workspace) {
			// @todo fixed in future canJs.
			if (!this.element) return;

			this.workspace = workspace;
			if (this.workspace == 'password') {
				this.keywordsFormElement.element.attr("placeholder", "search passwords");
			}
			else {
				this.keywordsFormElement.element.attr("placeholder", "search people");
			}
		},

		/* ************************************************************** */
		/* LISTEN TO VIEW EVENTS */
		/* ************************************************************** */

		/**
		 * Listen when the user is updating the filter
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {object} data The form data
		 */
		' update': function(el, ev) {
			var data = this.filterForm.getData(),
				filter = new passbolt.model.Filter(data['passbolt.model.Filter']);

			if (this.workspace == 'password') {
				mad.bus.trigger('filter_resources_browser', filter);
			}
			else if (this.workspace == 'people') {
				mad.bus.trigger('filter_users_browser', filter);
			}
			else if (this.workspace == 'settings') {
				// Switch to people workspace.
				mad.bus.trigger('workspace_selected', ['people', {filter: filter}]);
			}
		},

		/**
		 * Observe when the user wants to reset the filter
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 * js_filter_reset
		 */
		' reset': function (el, ev) {
			this.reset();
			var filter = new passbolt.model.Filter({
				'keywords': '',
				'tags': []
			});
			mad.bus.trigger('filter_resources_browser', filter);
		}
	});
});