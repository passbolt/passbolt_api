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
		 * Get all model instances in an array which match the search paramaters
		 * @param {array} data The array to search in
		 * @param {string} key The key to search
		 * @param {string} value The value of the key to search
		 * @return {array}
		 */
		'search': function (data, key, value) {
			var returnValue = [],
				split = key.split('.'),
				modelName = split[0],
				attrName = split[1];

			for (var i in data) {
				if ($.isArray(data[i][modelName])) {
					for (var j in data[i][modelName]) {
						if (data[i][modelName][j][attrName] == value) {
							returnValue.push(data[i]);
						}
					}
				} else {
					if (data[i][modelName][attrName] == value) {
						returnValue.push(data[i]);
					}
				}
				// search in children
				if (data[i].children) {
					var childrenSearch = mad.model.Model.search (data[i].children, key, value);
					if (childrenSearch != null) {
						returnValue = $.merge ([], returnValue, childrenSearch);
					}
				}
			}
			return returnValue;
		},
		
		/**
		 * Get on model instance in an array which match the search parameters
		 * and its value
		 * @param {array} data The array to search in
		 * @param {string} key The key to search
		 * @param {string} value The value of the key to search
		 * @return {mad.model.Model}
		 */
		'searchOne': function (data, key, value) {
			var returnValue = null;
			var searchResults = mad.model.Model.search(data, key, value);
			if (searchResults.length) {
				returnValue = searchResults[0];
			}
			return returnValue;
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