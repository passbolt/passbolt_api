/*
 * @page mad.controller.component Components
 * @tag mad.controller.component
 * @parent mad.model
 * @see mad.controller.ComponentController
 * @see mad.model.ComponentState
 * 
 * 
 */

steal(
	'jquery/model'
).then(function () {

	/*
	 * @class mad.model.ValidationRules
	 * @inherits mad.model
	 * 
	 * Our validation rules library. 
	 */
	$.Class('mad.model.ValidationRules', /** @static */ {

		/**
		 * Validate a value following a given rule. This model helper is used by the
		 * model to validate their attributes.
		 * 
		 * @param {string} rule The rule name
		 * @param {mixed} value The value to validate
		 * @param {array} options Optional parameters
		 */
		'validate': function (rule, value, options) {

			if (typeof rule == 'object'){
				options = options || rule.options || {};
				rule = rule.rule;
			}

			if (typeof mad.model.ValidationRules[rule] == 'undefined') {
				throw new Exception('The rule ' + rule + 'does not exist');
			}
			return mad.model.ValidationRules[rule](value, options);
		},

		// get alpha condition
		'_getAlphaRegExp': function (type) {
			var returnValue = '\\p{L}'; // by default every UTF8 language is allowed

			// @todo allowed options type => exception
			// check http://xregexp.com/addons/unicode/unicode-scripts.js allowed alphabet
			// An options type has been defined
			if (type) {
				switch (type) {
					case 'ASCII':
						returnValue = 'a-zA-Z';
						break;
					default:
						returnValue = '\\p{' + type + '}';
				}
			}

			return returnValue;
		},

		/**
		 * 
		 */
		'alphanum': function (value, options) {
			options = options || {};
			var alphaRegExp = mad.model.ValidationRules._getAlphaRegExp(options.type);
			var xregexp = new XRegExp("^[" + alphaRegExp + " \'0-9]*$");

			if (!xregexp.test(value)) {
				return __('Only alpha-numeric characters allowed');
			}
			return true;
		},

		/**
		 * 
		 */
		'alpha': function (value, options) {
			options = options || {};
			var alphaRegExp = mad.model.ValidationRules._getAlphaRegExp(options.type);

			var xregexp = new XRegExp("^[" + alphaRegExp + " \']*$");
			if (!xregexp.test(value)) {
				return __('Only alpha characters allowed');
			}
			return true;
		},

		/**
		 * 
		 */
		'num': function (value) {
			var xregexp = XRegExp("^-?[0-9]+\.?[0-9]*$");
			if (!xregexp.test(value)) {
				return __('Only numeric characters allowed');
			}
			return true;
		},

		/**
		 * 
		 */
		'required': function (value) {
			var xregexp = XRegExp("^[\s\t\n ]*$");
			if (value === null || xregexp.test(value)) {
				return __('Required');
			}
			return true;
		},

		/**
		 * 
		 */
		'email': function (value) {
			var xregexp = XRegExp("^[a-zA-Z0-9_.-]+[@]{1}[a-zA-Z0-9_.-]+\.[a-zA-Z]+$");
			if (!xregexp.test(value)) {
				return __('Only email format is allowed');
			}
			return true;
		},

		/**
		 * 
		 */
		'date': function (value, options) {
			var xregexp = XRegExp("^[0-9]{4}/[0-9]{2}/[0-9]{2}$");
			if (!xregexp.test(value)) {
				return __('Only date format is allowed');
			}
			return true;
		},

		/**
		 * 
		 */
		'uid': function (value, options) {
			return true;
		}

	}, /** @prototype */ { });
});
