import 'mad/mad';
import XRegExp from 'xregexp';
var __ = function(input){return input};

/**
* @parent Mad.core_api
* @inherits can.Construct
*
* The aim of the object Validation is to offer to developers a way to validate their
* data. This utility is massively used by the mad.Form component.
*/
var Validation = mad.Validation = can.Construct.extend('mad.Validation', /** @static */ {

    /**
     * Validate a value following a given rule. This model helper is used by the
     * model to validate their attributes.
     *
     * @param {string} rule The rule name
     * @param {mixed} value The value to validate
     * @param {array} options Optional parameters
     */
    validate: function (rule, value, values, options) {

        if (typeof rule == 'object') {
            options = rule;

            // The target rule has not been defined.
            if (typeof rule.rule == 'undefined') {
                throw mad.Exception.get(mad.error.WRONG_PARAMETER, 'rule.rule');
            }
            // The rule is a regex (based on the cakephp structure).
            if (rule.rule.indexOf('/') == '0') {
                rule = 'regex';
            }
            // The rule is an array as defined by CakePHP ex: rule => array(between, 3, 64)
            else if ($.isArray(rule.rule)) {
                options.params = rule.rule.slice(1);
                rule = rule.rule[0];
            }
            else {
                rule = rule.rule;
            }
        }

        if (typeof mad.Validation[rule] == 'undefined') {
            throw mad.Exception.get(mad.error.WRONG_PARAMETER, 'rule');
        }

        return mad.Validation[rule](value, values, options);
    },

    // get alpha condition
    _getAlphaRegExp: function (type) {
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
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    regex: function (value, values, options) {
        options = options || {};
        var returnValue = true,
            regexp = options.rule,
            not = options.not || false;

        // XRegExp expects an expresion without starting/ending slashes.
        // It's a little dirty now because we don't take care of flags.
        if (regexp.indexOf('/') == 0) {
            regexp = regexp.substr(1, regexp.length - (regexp.length - regexp.lastIndexOf('/') + 1));
        }
        var xregexp = new XRegExp(regexp);
        var match = xregexp.test(value);

        if ((not && match) || (!not && !match)) {
            returnValue = options.message || __('The regex is not validated');
        }
        return returnValue;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    notEmpty: function (value, values, options) {
        if (typeof value == 'undefined'
            || value == null
            || ($.isArray(value) && !value.length)
            || $.trim(value) == '') {
            return options.message || __('Should not be empty');
        }
        return true;
    },

    /**
     * Alias for notEmpty.
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    notBlank: function (value, values, options) {
        return this.notEmpty(value, values, options);
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    uuid: function (value, values, options) {
        options = options || {};
        var regexp = "^[abcdef0-9]{8}-[abcdef0-9]{4}-[abcdef0-9]{4}-[abcdef0-9]{4}-[abcdef0-9]{12}$";
        var xregexp = new XRegExp(regexp);

        if (!xregexp.test(value)) {
            return __('Not valid uuid');
        }
        return true;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    alphaNumeric: function (value, values, options) {
        options = options || {};
        var alphaRegExp = mad.Validation._getAlphaRegExp(options.type);
        var xregexp = new XRegExp("^[" + alphaRegExp + " \'0-9]*$");

        if (!xregexp.test(value)) {
            return __('Only alpha-numeric characters allowed');
        }
        return true;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    alpha: function (value, values, options) {
        options = options || {};
        var alphaRegExp = mad.Validation._getAlphaRegExp(options.type);
        var xregexp = XRegExp("^[" + alphaRegExp + " \']*$");
        if (!xregexp.test(value)) {
            return __('Only ' + options.type + ' characters allowed');
        }
        return true;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    num: function (value) {
        var xregexp = XRegExp("^-?[0-9]+\.?[0-9]*$");
        if (!xregexp.test(value)) {
            return __('Only numeric characters allowed');
        }
        return true;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    required: function (value) {
        var xregexp = XRegExp("^[\s\n\t ]*$");
        if (typeof value == 'undefined' || value === null || xregexp.test(value)) {
            return __('This information is required');
        }
        return true;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    text: function (value) {
        var xregexp = XRegExp("<(.|\n)*?>");
        if (xregexp.test(value)) {
            return __('No HTML tags allowed');
        }
        return true;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    url: function (value) {
        var regex = "^\
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

        var xregexp = XRegExp(regex);
        if (xregexp.test(value)) {
            return __('Not valid url.');
        }
        return true;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    nospace: function (value) {
        var xregexp = XRegExp("[ ]+");
        if (xregexp.test(value)) {
            return __('No space are allowed');
        }
        return true;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    email: function (value) {
        var xregexp = XRegExp("^[a-zA-Z0-9_.-]+[@]{1}[a-zA-Z0-9_.-]+\.[a-zA-Z]+$");
        if (!xregexp.test(value)) {
            return __('Only email format is allowed');
        }
        return true;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    date: function (value, values, options) {
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
                monthPos = 1;
                dayPos = 2;
                yearPos = 3;
                break;

            case 'mm/dd/yy':
                dateRegExp = /^(\d{1,2})[./-](\d{1,2})[./-](\d{2})$/;
                monthPos = 1;
                dayPos = 2;
                yearPos = 3;
                break;

            case 'mm/dd/yyyy':
                dateRegExp = /^(\d{1,2})[./-](\d{1,2})[./-](\d{4})$/;
                monthPos = 1;
                dayPos = 2;
                yearPos = 3;
                break;

            case 'dd/mm/yyyy':
                dateRegExp = /^(\d{1,2})[./-](\d{1,2})[./-](\d{4})$/;
                monthPos = 2;
                dayPos = 1;
                yearPos = 3;
                break;

            case 'd/m/yy':
                dateRegExp = /^(\d{1,2})[./-](\d{1,2})[./-](\d{2}|\d{4})$/;
                monthPos = 2;
                dayPos = 1;
                yearPos = 3;
                break;

            case 'y/m/d':
                dateRegExp = /^(\d{2}|\d{4})[./-](\d{1,2})[./-](\d{1,2})$/;
                monthPos = 2;
                dayPos = 3;
                yearPos = 1;
                break;

            case 'yy/mm/dd':
                dateRegExp = /^(\d{4}|\d{1,2})[./-](\d{1,2})[./-](\d{1,2})$/;
                monthPos = 2;
                dayPos = 3;
                yearPos = 1;
                break;

            case 'yyyy/mm/dd':
                dateRegExp = /^(\d{4})[./-](\d{1,2})[./-](\d{1,2})$/;
                monthPos = 2;
                dayPos = 3;
                yearPos = 1;
                break;
        }

        var dateParts = value.match(dateRegExp);
        if (!dateParts) {
            returnValue = __('The date format is incorect, expected : ') + format;
        } else {
            var year = dateParts[yearPos] * 1;
            var month = dateParts[monthPos] * 1;
            var day = dateParts[dayPos] * 1;

            // check date numbers
            if (day < 1 || day > days[month] ||
                month < 1 || month > 12) {
                returnValue = __('The date format is incorect, expected : ') + format;
            }

            // check leap year
            if (month == 2 && day == 29) {
                var isLeapYear = (year % 4 != 0 ? false :
                    (year % 100 != 0 ? true :
                        (year % 1000 != 0 ? false : true)));

                if (!isLeapYear) {
                    returnValue = __('The year %s is not a leap year', year);
                }
            }
        }
        return returnValue;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    lengthBetween: function (value, values, options) {
        value = value || '';
        options = options || [];
        var returnValue = true,
            min = options.params[0] || null,
            max = options.params[1] || null;

        if ((min != null && value.length < min) || (max!=null && value.length > max)) {
            returnValue = options.message ? __(options.message, min, max) : __('Must be between %s and %s characters long', min, max);
        }

        return returnValue;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    foreignRule: function (value, options) {
        var returnValue = true;
        if (options.model && options.model.validateRules && options.attribute) {
            for (var i in options.model.validateRules[options.attribute]) {
                var rule = options.model.validateRules[options.attribute][i];
                var foreignReturnValue = mad.Validation.validate(rule, value);
                if (foreignReturnValue !== true) {
                    returnValue = foreignReturnValue;
                    break;
                }
            }
        }
        return returnValue;
    },

    /**
     * @param {mixed} value The value to validate
     * @param {array} values The contextual values
     * @param {array} options Optional parameters
     */
    choice: function (value, options) {
        var returnValue = true,
            choices = [];
        value = (typeof value == 'undefined') ? null : value;

        if (options.choices) {
            choices = options.choices;
        } else if (options.callback) {
            choices = options.callback.apply(this);
        }

        if (choices.indexOf(value) == -1) {
            returnValue = __("%s is not a valid value", value);
        }

        return returnValue;
    }

}, /** @prototype */ {});

export default Validation;
