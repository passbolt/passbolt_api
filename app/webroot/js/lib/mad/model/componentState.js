steal(
	'jquery/model'
).then(function () {

	/*
	 * @class mad.model.ComponentState
	 * @inherits jQuery.Model
	 * @parent mad.controller.component
	 * 
	 * The component state model will carry the state of a given component.
	 * Component will listen to any change on it and it will adapt its behavior.
	 * 
	 * @constructor
	 * Creates a component state
	 * @param {array} options
	 * @return {mad.model.ComponentState}
	 */
	$.Model('mad.model.ComponentState', /** @static */ {

		/**
		 * Define attributes of the model
		 * @type {Object}
		 */
		attributes: {
			'label': null,
			// current state name
			'previous': null // previous state name
		}

	}, /** @prototype */ {

		/**
		 * Check if the current state is equal to the given state
		 * @param {string} stateName The state to check
		 * @return {boolean}
		 */
		'is': function (stateName) {
			return this.label == stateName;
		},

		/**
		 * Set the current state, store the previous state in the variable
		 * previous
		 * @param {string} stateName the new state
		 * @return {void}
		 */
		'setState': function (stateName) {
			this.previous = this.label;
			this.attr('label', stateName);
		},

		/**
		 * Get the current state name
		 * @return {string} The current state name
		 */
		'getState': function () {
			return this.attr('label');
		}

	});
});