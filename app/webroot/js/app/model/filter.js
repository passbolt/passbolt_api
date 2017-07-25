import 'mad/model/model';

/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The Filter model is used to manipulate workspace filters.
 *
 * @constructor
 * Creates a filter
 * @param {array} options
 * @return {passbolt.model.Filter}
 */
var Filter = passbolt.model.Filter = passbolt.Model.extend('passbolt.model.Filter', /** @static */ {

	attributes: {
		// Filter identifier
		id: 'string',
		// Filter label
		label: 'string',
		// Filter rules
		rules: 'object',
		// Filter order, should the result be ordered
		order: 'array'
	}

}, /** @prototype */ {

	/**
	 * Instantiate a new filter.
	 */
	init: function() {
		if (!this.id) {
			this.attr('id', uuid());
		}
		if(!this.rules) {
			this.rules = new can.Map();
		}
		if(!this.order) {
			this.order = new can.Map();
		} else if(typeof this.order.attr() === 'string') {
			this.order = new can.Map(this.order.attr());
		}
	},

	/**
	 * Get the order.
	 * @returns {*}
     */
	getOrders: function() {
		if (this.order) {
			return this.order.attr();
		}
		return [];
	},

	/**
	 * Get a rule.
	 * @param name {string} The rule name
	 * @return {mixed} The rule value
	 */
	getRule: function(name) {
		return this.rules.attr(name);
	},

	/**
	 * Set a new rule, or change an existing rule value.
	 * @param name {string} The rule name
	 * @param value {mixed} The rule value
	 */
	setRule: function(name, value) {
		this.rules.attr(name, value);
	},

	/**
	 * Get filters.
	 * @param excludedRules {array} The rules to exclude
	 * @return {object} Return the data that compose this filter
	 */
	getRules: function(excludedRules) {
		var rules = this.rules ? this.rules.serialize() : {};

		// Exclude the rules given as parameters.
		if(excludedRules && excludedRules.length) {
			for(var i in excludedRules) {
				if(typeof rules[excludedRules[i]] != 'undefined'){
					delete rules[excludedRules[i]];
				}
			}
		}
		return rules;
	}

});

export default Filter;
