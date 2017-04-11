import 'mad/component/component';
import 'mad/form/form';
import 'mad/form/element/textbox';
import 'app/view/component/app_filter';
import 'app/model/filter';

import 'app/view/template/component/app_filter.ejs!';

/**
 * @inherits {mad.Component}
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
 * @return {passbolt.component.AppFilter}
 */
var AppFilter = passbolt.component.AppFilter = mad.Component.extend('passbolt.component.AppFilter', /** @static */ {

	defaults: {
        templateUri: 'app/view/template/component/app_filter.ejs',
		viewClass: passbolt.view.component.AppFilter
	}

}, /** @prototype */ {

	/**
	 * The currently enabled workspace
	 * @type {mad.Component}
	 */
	workspace: null,

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		// Instantiate the filter form
		this.filterForm = new mad.Form('#js_app_filter_form', {});
		this.filterForm.start();

		// Instantiate the textbox which will get the user search
		var keywordsFormElement = this.filterForm.addElement(new mad.form.Textbox('#js_app_filter_keywords', {
			onChangeTimeout: 200,
			modelReference: 'passbolt.model.Filter.keywords'
		}));
		keywordsFormElement.start();
		this.options.keywordsFormElement = keywordsFormElement;

		this.on();
	},

	/**
	 * Reset the filter
	 */
	reset: function () {
		this.options.keywordsFormElement.setValue('');
	},

	/* ************************************************************** */
	/* LISTEN TO VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * Listen when the user is updating the filter
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {object} data The form data
	 */
	'{keywordsFormElement.element} changed': function(el, ev, data) {
		var self = this,
			// Retrieve the form data.
			formData =  this.filterForm.getData(),
			// Keywords.
			keywords = formData['passbolt.model.Filter']['keywords'],
			// The filter to build used to filter the workspace.
			filter = null;

		// If the settings workspace is currently enabled.
		// Enable the people workspace first, and filter it.
		if (this.workspace instanceof passbolt.component.SettingsWorkspace) {
			filter = passbolt.component.PeopleWorkspace.getDefaultFilterSettings();
			filter.setRule('keywords', keywords);
			mad.bus.trigger('request_workspace', ['people', {filterSettings: filter}]);
		}
		// Otherwise filter the current workspace.
		else {
			filter = self.workspace.constructor.getDefaultFilterSettings();
			filter.setRule('keywords', keywords);
			mad.bus.trigger('filter_workspace', filter);
		}
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user switched to another workspace
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {mad.Component} workspace The enabled workspace
	 */
	'{mad.bus.element} workspace_enabled': function (el, event, workspace) {
		this.workspace = workspace;

		if (this.workspace instanceof passbolt.component.PasswordWorkspace) {
			this.options.keywordsFormElement.element.attr("placeholder", "search passwords");
		}
		else if (this.workspace instanceof passbolt.component.PeopleWorkspace) {
			this.options.keywordsFormElement.element.attr("placeholder", "search people");
		}
		else if (this.workspace instanceof passbolt.component.SettingsWorkspace) {
			this.options.keywordsFormElement.element.attr("placeholder", "search people");
			this.reset();
		}
	},

	/**
	 * Listen to the browser filter
	 * @param {jQuery} element The source element
	 * @param {Event} event The jQuery event
	 * @param {passbolt.model.Filter} filter The filter to apply
	 */
	'{mad.bus.element} filter_workspace': function (element, evt, filter) {
		var formData =  this.filterForm.getData(),
			keywords = filter.getRule('keywords');

		if (formData['passbolt.model.Filter']['keywords'] != keywords) {
			this.options.keywordsFormElement.setValue(keywords)
		}
	}

});

export default AppFilter;