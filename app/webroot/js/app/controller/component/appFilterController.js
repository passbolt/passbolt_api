steal(
	'mad/controller/componentController.js',
	'mad/form/element/listController.js',
	'app/view/component/appFilter.js',
	'app/view/template/component/appFilter.ejs'
).then(function () {

	/*
	 * @class passbolt.controller.component.AppFilterController
	 * @inherits {mad.controller.ComponentController}
	 * @parent index
	 * 
	 * 
	 * @constructor
	 * Instanciate the application filter controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.AppFilterController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.AppFilterController', /** @static */ {

		'defaults': {
			'templateBased': false,
			'viewClass': passbolt.view.component.AppFilter
		}

	}, /** @prototype */ {

		'init': function (el, options) {
			var filterForm = new mad.form.FormController('#js_filter_form', {
				'callbacks': {
					'submit': function (data) {
						var filter = new passbolt.model.Filter(data['passbolt.model.Filter']);
						mad.bus.trigger('filter_resources_browser', filter);
					}
				}
			});
			this.keywordsFormElement = filterForm.addElement(new mad.form.element.TextboxController('#js_filter_keywords', {
				modelReference: 'passbolt.model.Filter.keywords'
			}));
			this.listFormElement = filterForm.addElement(new mad.form.element.ListController('#js_filter_tags', {
				modelReference: 'passbolt.model.Filter.tags'
			}));
			this._super(el, options);
		},

		/**
		 * Reset the filter
		 * @return {void}
		 */
		'reset': function () {
			this.listFormElement.setValue([]);
			this.keywordsFormElement.setValue('');
		},

		/**
		 * Render the application filter
		 * @see {mad.controller.ComponentController.prototype.render}
		 */
		'render': function (options) {
			this._super(options);
			this.listFormElement.render();
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
			this.reset();
			this.listFormElement.setValue([category]);
			this.keywordsFormElement.setValue('');
		},

		/* ************************************************************** */
		/* LISTEN TO VIEW EVENTS */
		/* ************************************************************** */

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