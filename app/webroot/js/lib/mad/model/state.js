steal(
	'mad/model'
).then(function () {

	/*
	 * @class mad.model.State
	 * @inherits mad.model.Model
	 * @parent mad.controller.component
	 * 
	 * The State model will carry the state of a given controller.
	 * <br/>
	 * Controllers will listen to any change on it and it will adapt its behavior.
	 * 
	 * @constructor
	 * Creates a new State
	 * @param {array} options
	 * @return {mad.model.State}
	 */
	mad.model.Model.extend('mad.model.State', /** @static */ {

		'attributes': {
			// previous state name
			'previous': 'can.List',
			// current states
			'current': 'can.List'
		}

	}, /** @prototype */ {

		'init': function(){
			this.previous = new can.List([]);
			this.current = new can.List([]);
		},

		/**
		 * Check if the current state is equal to the given state
		 * @param {string} stateName The state to check
		 * @return {boolean}
		 */
		'is': function (stateName) {
			// stateName is null, check that the current states list is null
			if(stateName == null && !this.current.length) {
				return true;
			}
			return this.current.indexOf(stateName) != -1 ? true : false;
		},

		/**
		 * Check if the previous state is equal to the given state
		 * @param {string} stateName The state to check
		 * @return {boolean}
		 */
		'was': function (stateName) {
			// stateName is null, check that the current states list is null
			if(stateName == null && !this.previous.length) {
				return true;
			}
			return this.previous.indexOf(stateName) != -1 ? true : false;
		},

		/**
		 * Set the current states, store the previous states in the variable previous
		 * @param {string|array} statesName the new state name or an array of states name
		 * @return {void}
		 */
		'setState': function (statesName) {
			statesName = $.isArray(statesName) ? statesName : [statesName];
			this.previous.replace(this.current.attr());
			this.current.replace(statesName);
		},

		/**
		 * Add states to the current list of states, store the previous states in the variable previous
		 * @param {string|array} statesName the state name or an array of states name
		 * @return {void}
		 */
		'addState': function (statesName) {
			statesName = $.isArray(statesName) ? statesName : [statesName];
			this.previous.replace(this.current.attr());
			$.each(this.current.attr(), function(i, val) {
				statesName.push(val);
			});
			this.current.replace(statesName);
		},

		/**
		 * Remove states to the current list of states, store the previous states in the variable previous
		 * @param {string|array} statesName the state name or an array of states name
		 * @return {void}
		 */
		'removeState': function (statesName) {
			statesName = $.isArray(statesName) ? statesName : [statesName];
			var newStatesName = [];
			this.previous.replace(this.current.attr());
			$.each(this.current.attr(), function(i, val) {
				if (statesName.indexOf(val) == -1) {
					newStatesName.push(val);
				}
			});
			this.current.replace(newStatesName);
		},

		/**
		 * Get the current state name
		 * @return {string} The current state name
		 */
		'getState': function () {
			return this.current;
		},

		/**
		 * Get a string of state.
		 */
		'toString': function(separator) {
			if (typeof separator == 'undefined') {
				separator = ',';
			}
			return this.current.join(separator);
		}

	});
});
