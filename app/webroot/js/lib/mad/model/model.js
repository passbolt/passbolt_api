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
							if (returnValue === true) {
								returnValue = '';
							}
							returnValue += validateResult;
						}
					}
				} else {
					returnValue = mad.model.ValidationRules.validate(rules, value, modelValues);
				}
			}

			return returnValue;
		},

		/**
		 * Get a model instance in an array function of the parameters key (id, Category.id ...)
		 * and its value
		 * @param {array} data The array to search in
		 * @param {string} key The key to search
		 * @param {string} value The value of the key to search
		 * @return {mad.model.Model}
		 */
		'search': function (data, key, value) {
			var split = key.split('.');
			for (var i in data) {
				var compare = data[i];
				for (var j in split) {
					compare = compare[split[j]];
				}
				if (compare == value) {
					return data[i];
				}
				// search in children
				if (data[i].children) {
					var childrenSearch = mad.model.Model.search (data[i].children, key, value);
					if (childrenSearch != null) {
						return childrenSearch;
					}
				}
			}
			return null;
		}

	}, /** @prototype */ {

		// Destructor like
		'destroy': function () {},

		/**
		 * Override the jmvc model serialize function, to serialize associated models
		 * @return {object}
		 */
		'serialize' : function () {
			var returnValue = this._super();
			// Check the serialization contain an array of Class
			// Change done for JMVC 3.2.4
			for (var i in returnValue) {
				if ($.isArray(returnValue[i])) {
					for (var j in returnValue[i]) {
						returnValue[i][j] = returnValue[i][j].serialize();
					}
				}
			}
			return returnValue;
		}

	});

});