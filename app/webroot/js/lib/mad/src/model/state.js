import 'mad/model/model';

/**
 * @parent Mad.core_api
 * @inherits mad.Model
 *
 * The aim of the State model is to manage states of a component and its
 * transitions.
 *
 * This state model is used by the mad.Component.
 */
var State = mad.model.State = mad.Model.extend('mad.model.State', /** @static */{

	attributes: {
		// previous states
		previous: 'can.List',
		// current states
		current: 'can.List'
	}

}, /** @prototype */ {

	/**
	 * Constructor.
	 *
	 * @signature `new mad.model.State()`
	 * @return {mad.model.State} A new instance of the constructor function extending mad.model.State.
	 */
	init: function(){
		this.previous = new can.List([]);
		this.current = new can.List([]);
	},

	/**
	 * Check if the state given in parameter was a current states.
	 *
	 * @param {string} state The state to check for
	 * @return {boolean}
	 */
	is: function (state) {
		// state is null, check that the current states list is null
		if(state == null && !this.current.length) {
			return true;
		}
		return this.current.indexOf(state) != -1 ? true : false;
	},

	/**
	 * Check if the state given in parameter was a previous states.
	 *
	 * @param {string} state The state to check for
	 * @return {boolean}
	 */
	was: function (state) {
		// state is null, check that the current states list is null
		if(state == null && !this.previous.length) {
			return true;
		}
		return this.previous.indexOf(state) != -1 ? true : false;
	},

	/**
	 * Switch the current states to the given state(s).
	 * Store the actual states in the previous states list.
	 * Without parameter the function can be used to reset the current states.
	 *
	 * @param {string|array} states (optional) the new state or an array of states.
	 * If no state given, the function will flush the current states list.
	 */
	setState: function (states) {
		// If no states have been given.
		// Flush the current states list.
		if (typeof states == 'undefined') {
			states = [];
		}
		states = $.isArray(states) ? states : [states];
		this.previous.replace(this.current.attr());
		this.current.replace(states);
	},

	/**
	 * Add states to the current states.
	 * Store the actual states in the previous states list.
	 *
	 * @param {string|array} states the state name or an array of states name
	 */
	addState: function (states) {
		states = $.isArray(states) ? states : [states];
		this.previous.replace(this.current.attr());
		$.each(this.current.attr(), function(i, val) {
			states.push(val);
		});
		this.current.replace(states);
	},

	/**
	 * Remove states from the current states.
	 * Store the actual states in the previous states list.
	 *
	 * @param {string|array} states the state name or an array of states name
	 */
	removeState: function (states) {
		states = $.isArray(states) ? states : [states];
		var newStates = [];
		this.previous.replace(this.current.attr());
		$.each(this.current.attr(), function(i, val) {
			if (states.indexOf(val) == -1) {
				newStates.push(val);
			}
		});
		this.current.replace(newStates);
	},

	/**
	 * Transform the current states list into a string.
	 *
	 * @return {string} the string
	 */
	toString: function(separator) {
		if (typeof separator == 'undefined') {
			separator = ',';
		}
		return this.current.join(separator);
	}

});

export default State;
