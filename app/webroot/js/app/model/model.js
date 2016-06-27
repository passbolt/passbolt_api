import 'mad/model/model';

/**
 * @inherits {mad.Model}
 *
 * The passbolt model
 *
 * @constructor
 * The passbolt model
 * @param {array} options
 * @return {passbolt.Model}
 */
var Model = passbolt.Model = mad.Model.extend('passbolt.Model', /** @static */ {

	/**
	 * Return the fields to filter on regarding a case given in parameters.
	 *
	 * @param filteredCase
	 * @returns {mixed} An array of fields to filter on, or false if the case doesn't require to be filtered.
	 */
	getFilteredFields: function(filteredCase) {
		return false;
	},

	/**
	 * Filter the attributes regarding a given case.
	 *
	 * canjs doesn't allow to filter the save and update attributes send to the back-end.
	 * We allow passbolt developers to filter the request by adding a __FILTER_CASE__
	 * attribute. This case attribute will be used to get the fields to filter on (see the function
	 * getFilteredFields).
	 *
	 * @param attrs
	 * @returns {{}}
	 */
	filterAttributes: function(attrs) {
		var filteredAttrs = {};

		// If a filtered case has been given in parameter.
		if (typeof attrs.__FILTER_CASE__ != 'undefined') {
			var fields = this.getFilteredFields(attrs.__FILTER_CASE__);

			// If the case requires to filter the attributes.
			if (fields !== false) {
				for (var i in fields) {
					var value = can.getObject(fields[i], attrs);
					can.getObject(fields[i], filteredAttrs, true, value);
				}
			} else {
				filteredAttrs = attrs;
				delete filteredAttrs.__FILTER_CASE__;
			}
		}
		// If no filter case has been given, return all the attributes.
		else {
			filteredAttrs = attrs;
		}

		return filteredAttrs;
	},

}, /** @prototype */ {

	/**
	 * Clone a model instance
	 * @return {passbolt.Model} The cloned model instance
	 */
	clone: function() {
		var data = this.attr();
		delete data[this.constructor.id];
		return new this.constructor(data);
	}
});

export default Model;
