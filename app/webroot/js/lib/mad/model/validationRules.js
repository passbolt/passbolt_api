steal(
	'jquery/model'
).then(function () {

	/*
	 * @class mad.model.ValidationRules
	 * @inherits jQuery.Class
	 * 
	 * Our validation rules library
	 */
	$.Class('mad.model.ValidationRules', /** @static */ {

		'validate': function (rule, value, context, msg) {
			if (typeof mad.model.ValidationRules[rule] == 'undefined') {
				throw new Exception ('The rule ' + rule + 'does not exist');
			}
			return mad.model.ValidationRules[rule](value, msg);
		},

		/**
		 * 
		 */
		'alphanum': function (str) {
			var xregexp = XRegExp("^[\\p{L} \']*$");
			if (!xregexp.test(str)) {
				return __('Only alpha-numeric characters allowed');
			}
			return true;
		},

		/**
		 * 
		 */
		'alpha': function (str) {
			var xregexp = XRegExp("^[\\p{L}]*$");
			if (!xregexp.test(str)) {
				return __('Only alpha characters allowed');
			}
			return true;
		},

		/**
		 * 
		 */
		'num': function (str) {
			var xregexp = XRegExp("^[\d]*$");
			if (!xregexp.test(str)) {
				return __('Only numeric characters allowed');
			}
			return true;
		},

		/**
		 * 
		 */
		'required': function (str) {
			var xregexp = XRegExp("^\s*$");
			if (!xregexp.test(str)) {
				return __('Required');
			}
			return true;
		}

	}, /** @prototype */ { });
});
