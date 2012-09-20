steal('jquery/model').then(function ($) {

	/*
	 * @class mad.model.Model
	 * @inherits jQuery.Model
	 * @parent mad.core
	 * 
	 * The core class Model is an extension of the JavascriptMVC Model. This
	 * class allow us to easily hook common behavior, such as :
	 * <ul>
	 *	<li>
	 *		validating model attribute
	 *	</li>
	 * </ul>
	 * 
	 * @constructor
	 * creates a new model
	 * @return {mad.model.Model}
	 */
	$.Model('mad.model.Model', /** @static */ {

		/**
		 * The options to use to validate the model attributes.
		 * @type array
		 * @protected
		 */
		'validateRules': {},

		/**
		 * Validate an attribute
		 * @param {string} attrName The attribute name
		 * @param {mixed} value The attribute value
		 * @param {array} modelValues The model attributes values
		 * @return {boolean}
		 */
		'validateAttribute': function (attrName, value, modelValues) {
			var returnValue = true;

			if (this.validateRules[attrName]) {
				var rules = this.validateRules[attrName];
				if ($.isArray(rules)) {
					for (var i in rules) {
						var validateResult = mad.model.ValidationRules.validate(rules[i], value, modelValues);
						if (validateResult !== true) {
							returnValue = false;
						} else {
							validateResult += validateResult;
						}
					}
				} else {
					returnValue = mad.model.ValidationRules.validate(rules, value, modelValues);
				}
			}
			
			return returnValue;
		}

	}, /** @prototype */ {

		// Destructor like
		'destroy': function () {}

	});

});