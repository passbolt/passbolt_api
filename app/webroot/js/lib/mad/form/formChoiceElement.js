steal(
	MAD_ROOT + '/form/formElement.js',
	MAD_ROOT + '/view/form/formElementView.js'
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

		'defaults': { }

	},/** @prototype */ {

		/**
		 * Available values
		 * @type {array}
		 */
		'availableValues': { },

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

	});
});