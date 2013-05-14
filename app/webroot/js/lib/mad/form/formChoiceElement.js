steal(
	'mad/form/formElement.js',
	'mad/view/form/formElementView.js'
).then(function () {

	/*
	 * @class mad.form.FormChoiceElement
	 * @inherits mad.form.FormElement
	 * @parent mad.form
	 * 
	 * Our Form Choice Element class which will be the parent of any Form Choice Element
	 * (dropdown, radio, checkbox ...)	
	 * 
	 * @constructor
	 * Creates a new Form Choice Element
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable
	 * @return {mad.controller.FormChoiceElement}
	 */
	mad.form.FormElement.extend('mad.form.FormChoiceElement', /** @static */ {

		'defaults': {
			'availableValues': []
		}

	},/** @prototype */ {

		'beforeRender': function() {
			this._super();
			this.setViewData('availableValues', this.options.availableValues);
		},

		// /**
		 // * Available values
		 // * @type {array}
		 // */
		// 'availableValues': { },
// 
		// /**
		 // * Set the dropdown's available values
		 // * @param {array} options The available values of the dropdown
		 // * {
		 // *	value1: value1Label,
		 // *	value2: value2Label,
		 // *	value3: value3Label
		 // * }
		 // * @return {void}
		 // */
		// 'setAvailableValues': function (availableValues) {
			// this.availableValues = availableValues;
			// this.view.setAvailableValues(this.availableValues);
		// },
// 
		// /**
		 // * Render the component
		 // * @see {mad.view.View}
		 // * @param {array} options Associative array of options
		 // * @param {boolean} options.display Display the rendered component. If true
		 // * the rendered component will be push in the DOM else the rendered component
		 // * will be stored in the instance's variable renderedView
		 // * @return {mixed} Return true if the method does not encountered troubles else
		 // * return false. If the option display is set to false, return the rendered view
		 // */
		// 'render': function (options) {
			// this.setAvailableValues(this.options.availableValues);
			// return this._super();
		// },

		/* ************************************************************** */
		/* LISTEN TO THE STATE CHANGES */
		/* ************************************************************** */

		/**
		 * Listen to the change relative to the state Ready
		 * @param {boolean} go Enter or leave the state
		 * @return {void}
		 */
		'stateReady': function (go) {
			this._super(go);
		}

	});
});