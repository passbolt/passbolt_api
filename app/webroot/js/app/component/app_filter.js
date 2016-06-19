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
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		this.workspace = 'password';

		// Instantiate the filter form
		this.filterForm = new mad.Form('#js_app_filter_form', {});
		this.filterForm.start();

		// Instantiate the textbox which will get the user search
		var keywordsFormElement = this.filterForm.addElement(new mad.form.Textbox('#js_app_filter_keywords', {
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
		this.keywordsFormElement.setValue('');
	},

	/* ************************************************************** */
	/* LISTEN TO APP EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when category is selected
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {passbolt.model.Category} category The selected category
	 */
	'{mad.bus.element} category_selected': function (el, ev, category) {
		this.reset();
	},

	/**
	 * Observe when a workspace is selected.
	 * @param {HTMLElement} el
	 * @param {HTMLEvent} event
	 * @param workspace
	 */
	'{mad.bus.element} workspace_selected': function (el, event, workspace) {
		this.workspace = workspace;

		if (this.workspace == 'password') {
			this.options.keywordsFormElement.element.attr("placeholder", "search passwords");
		}
		else {
			this.options.keywordsFormElement.element.attr("placeholder", "search people");
		}
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
		// Build the filter data from the filter form and the default.
		var formData =  this.filterForm.getData(),
			filterData = $.extend({
				type: passbolt.model.Filter.KEYWORD
			}, formData['passbolt.model.Filter']),
			// Build the filter based on the filter data.
			filter = new passbolt.model.Filter(filterData);

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
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
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

export default AppFilter;