steal(
	MAD_ROOT + '/form/formElement.js',
	MAD_ROOT + '/view/form/element/dropdownView.js',
	MAD_ROOT + '/view/template/component/input.ejs'
).then(function ($) {

	/*
	 * @class mad.form.element.DropdownController
	 * @inherits mad.form.FormElement
	 * @view mad.view.form.element.Dropdown
	 * @parent mad.form
	 * 
	 * The Dropdown Form Element Controller is our implementation of the UI component dropdown
	 * 
	 * @constructor
	 * Creates a new Dropdown Form Element Controller
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller. These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.form.element.DropdownController}
	 */
	mad.form.FormElement.extend('mad.form.element.DropdownController', /** @static */ {

		'defaults': {
			'label': 'DropdownController Form Element Controller',
			'templateUri': '//' + MAD_ROOT + '/view/template/component/input.ejs',
			'viewClass': mad.view.form.element.DropdownView
		},

		'listensTo': [
			'changed'
		]

	}, /** @prototype */ {

		/**
		 * Available values
		 * @type {array}
		 */
		'availableValues': { },

		// constructor like
		'init': function (el, options) {
			this._super(el, options);
		},

		/**
		 * Set the dropdown's available values
		 * @param {array} options The available values of the dropdown
		 * {
		 *	value1: value1Label,
		 *	value2: value2Label,
		 *	value3: value3Label
		 * }
		 * @return {void}
		 */
		'setAvailableValues': function (availableValues) {
			this.availableValues = availableValues;
			this.view.setAvailableValues(this.availableValues);
		},

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Ready
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateReady': function (go) {
			if (go) {
				this.setAvailableValues(this.options.availableValues);
			}
			this._super(go);
		}

		/* ************************************************************** */
		/* LISTEN TO THE VIEW EVENTS */
		/* ************************************************************** */


	});

});