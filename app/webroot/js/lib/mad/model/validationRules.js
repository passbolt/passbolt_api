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

			if (typeof rule == 'object') {
				options = rule.options || options || {};
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
			var xregexp = XRegExp("^[\s\n\t ]*$");
			if (value === null || xregexp.test(value)) {
				return __('Required');
			}
			return true;
		},

		/**
		 * 
		 */
		'text': function (value) {
			var xregexp = XRegExp("<(.|\n)*?>");
			if (xregexp.test(value)) {
				return __('No HTML tags allowed');
			}
			return true;
		},

		/**
		 * 
		 */
		'uri': function (value) {
//			var regexUri = /^([a-z0-9+.-]+):(?://(?:((?:[a-z0-9-._~!$&'()*+,;=:]|%[0-9A-F]{2})*)@)?((?:[a-z0-9-._~!$&'()*+,;=]|%[0-9A-F]{2})*)(?::(\d*))?(/(?:[a-z0-9-._~!$&'()*+,;=:@/]|%[0-9A-F]{2})*)?|(/?(?:[a-z0-9-._~!$&'()*+,;=:@]|%[0-9A-F]{2})+(?:[a-z0-9-._~!$&'()*+,;=:@/]|%[0-9A-F]{2})*)?)(?:\?((?:[a-z0-9-._~!$&'()*+,;=:/?@]|%[0-9A-F]{2})*))?(?:#((?:[a-z0-9-._~!$&'()*+,;=:/?@]|%[0-9A-F]{2})*))?$/g;
			var	regexUri = "^\
				([a-z0-9+.-]+):\
					(?:\
						(?:((?:[a-z0-9-._~!$&'()*+,;=:]|%[0-9A-F]{2})*)@)?\
						((?:[a-z0-9-._~!$&'()*+,;=]|%[0-9A-F]{2})*)\
						(?::(\d*))?\
						(/(?:[a-z0-9-._~!$&'()*+,;=:@/]|%[0-9A-F]{2})*)?\
					|\
						(/?(?:[a-z0-9-._~!$&'()*+,;=:@]|%[0-9A-F]{2})+(?:[a-z0-9-._~!$&'()*+,;=:@/]|%[0-9A-F]{2})*)?\
				)\
				(?:\
					\((?:[a-z0-9-._~!$&'()*+,;=:/?@]|%[0-9A-F]{2})*)\
				)?\
				(?:\
					#((?:[a-z0-9-._~!$&'()*+,;=:/?@]|%[0-9A-F]{2})*)\
				)?\
			$";
			//^
			//#scheme								([a-z0-9+.-]+):
			//(?:
			//	#it has an authority:
			//	#userinfo						(?:((?:[a-z0-9-._~!$&'()*+,;=:]|%[0-9A-F]{2})*)@)?
			//	#host								((?:[a-z0-9-._~!$&'()*+,;=]|%[0-9A-F]{2})*)
			//	#port								(?::(\d*))?
			//	#path								(/(?:[a-z0-9-._~!$&'()*+,;=:@/]|%[0-9A-F]{2})*)?
			//	|
			//	#it doesn't have an authority:
			//	#path								(/?(?:[a-z0-9-._~!$&'()*+,;=:@]|%[0-9A-F]{2})+(?:[a-z0-9-._~!$&'()*+,;=:@/]|%[0-9A-F]{2})*)?
			//)
			//(?:
			//	#query string				\?((?:[a-z0-9-._~!$&'()*+,;=:/?@]|%[0-9A-F]{2})*)
			//)?
			//(?:
			//	#fragment						#((?:[a-z0-9-._~!$&'()*+,;=:/?@]|%[0-9A-F]{2})*)
			//)?
			//$
			var xregexp = XRegExp(regexUri);
			if (!xregexp.test(value)) {
				return __('Only URI format is allowed');
			}
			return true;
		},

		/**
		 * 
		 */
		'nospace': function (value) {
			var xregexp = XRegExp("[ ]+");
			if (xregexp.test(value)) {
				return __('No space are allowed');
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
			value = value || '';
			options = options || {};
			var format = options.format || 'dd/mm/yyyy',
				yearPos = null,
				monthPos = null,
				dayPos = null,
				days = [0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
				dateRegExp = '',
				returnValue = true;

			switch (format) {
			case 'm/d/y':
				dateRegExp = /^(\d{1,2})[./-](\d{1,2})[./-](\d{2}|\d{4})$/;
				monthPos = 1; dayPos = 2; yearPos = 3;
				break;

			case 'mm/dd/yy':
				dateRegExp = /^(\d{1,2})[./-](\d{1,2})[./-](\d{2})$/;
				monthPos = 1; dayPos = 2; yearPos = 3;
				break;

			case 'mm/dd/yyyy':
				dateRegExp = /^(\d{1,2})[./-](\d{1,2})[./-](\d{4})$/;
				monthPos = 1; dayPos = 2; yearPos = 3;
				break;

			case 'dd/mm/yyyy':
				dateRegExp = /^(\d{1,2})[./-](\d{1,2})[./-](\d{4})$/;
				monthPos = 2; dayPos = 1; yearPos = 3;
				break;

			case 'd/m/yy':
				dateRegExp = /^(\d{1,2})[./-](\d{1,2})[./-](\d{2}|\d{4})$/;
				monthPos = 2; dayPos = 1; yearPos = 3;
				break;

			case 'y/m/d':
				dateRegExp = /^(\d{2}|\d{4})[./-](\d{1,2})[./-](\d{1,2})$/;
				monthPos = 2; dayPos = 3; yearPos = 1;
				break;

			case 'yy/mm/dd':
				dateRegExp = /^(\d{4}|\d{1,2})[./-](\d{1,2})[./-](\d{1,2})$/;
				monthPos = 2; dayPos = 3; yearPos = 1;
				break;

			case 'yyyy/mm/dd':
				dateRegExp = /^(\d{4})[./-](\d{1,2})[./-](\d{1,2})$/;
				monthPos = 2; dayPos = 3; yearPos = 1;
				break;
			}

			var dateParts = value.match(dateRegExp);
			if (!dateParts) {
				returnValue = __('The date format is incorect, expected : ') + format;
			} else {
				year = dateParts[yearPos] * 1;
				month = dateParts[monthPos] * 1;
				day = dateParts[dayPos] * 1;

				// check date numbers
				if (day < 1 || day > days[month] ||
					month < 1 || month > 12) {
					returnValue = __('The date format is incorect, expected : ') + format;
				}

				// check leap year
				if (month == 2 && day == 29) {
					var isLeapYear = (year % 4 != 0 ? false :
						(year % 100 != 0 ? thrue:
						(year % 1000 != 0 ? false : true)));
					
					if (!isLeapYear) {
						returnValue = __('The year %s is not a leap year', year);
					}
				}
			}
			return returnValue;
		},

		/**
		 * 
		 */
		'uid': function (value, options) {
			return true;
		},

		/**
		 * 
		 */
		'size': function (value, options) {
			value = value || '';
			options = options || {};
			var returnValue = true,
				min = options.min || null,
				max = options.max || null;

			if (min) {
				if (value.length < min) {
					returnValue = __("A least ") + min + __(" characters");
				}
			}
			if (max) {
				if (value.length > max) {
					returnValue = __("Cannot exceed ") + max + __(" characters");
				}
			}

			return returnValue;
		}

	}, /** @prototype */ { });
});
