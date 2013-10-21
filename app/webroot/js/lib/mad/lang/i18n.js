/*
 * @page mad.lang Internationalization
 * @tag mad.lang
 * @parent index
 * 
 * In order to translate sentences either in PHP or in Javascript use the global function 
 * __(str [, var1, var2 ...])
 * 
 * @codestart
__('Hello the %s world', 'mad')
 * @codeend
 * 
 * %s : Variables' hook. Put as many as you want, but provide to the function as many variables as they are hooks in the string
 */

steal(
	'can/construct'
).then(function () {

	/**
	 * Translate the given string.
	 * @param {string} str The string to translate
	 * @param {...} arguments Add as many variable as they are hooks in the variable
	 * to translate
	 * @return {string} The translated string
	 */
	__ = function (str) {
		var variables = [],
			translation = '';

		// extract variables from arguments  
		var args = Array.prototype.slice.call(arguments);
		delete args.callee;
		delete args.caller;
		for (var i in args) {
			variables.push(args[i]);
		}

		return mad.lang.I18n.translate(str, variables.slice(1));
	};

	/*
	 * @class mad.lang.I18n
	 * @inherits can.Construct
	 * Our implementation of the I18n system.
	 * @parent mad.lang
	 * 
	 * @constructor
	 * Create a I18n object to manage translation
	 * @return {mad.lang.I18n}
	 */
	can.Construct.extend('mad.lang.I18n', /** @static */ {

		/**
		 * The current dictionnary to use
		 * @type {array}
		 */
		'dico': {},

		/**
		 * Translate the string
		 * @param {string} str The string to translate
		 * @param {array} vars The array of variables to inject in the string
		 * @return {string} The translated string
		 */
		'translate': function (str, vars) {
			var vars = typeof vars != 'undefined' ? vars : [];
			return mad.lang.I18n.replaceHooks(this.getEntry(str), vars);
		},

		/**
		 * Load the given dictionnary as dictionnary of translated terms
		 * @param {array} dico The dictionnary to use
		 * @return {void}
		 */
		'loadDico': function (dico) {
			for(var i in dico) {
				mad.lang.I18n.dico[i] = dico[i]; //make a copy of the data to be sure there will be existing in the app scope
			}
		},

		/**
		 * Replace the variables' hooks in the translated string
		 * @param {string} str The translated string
		 * @param {array} vars The variables to inject in the string
		 * @return {string}
		 */
		'replaceHooks': function (str, vars) {
			var returnValue = ''
			split = [];

			// split the string by the variable's hooks
			split = str.split('%s');

			// if the string does not contain the proper number of variables throw an exception
			if(split.length != vars.length + 1) {
				throw new mad.error.WrongParametersException('I18n Error : The sentence to translate does not contain as many hooks as they are variables');
			}
			// no hook found in the string
			if(split.length < 2) {
				return str;
			}
			// replace string's hooks with the given variables
			var j;
			for(var i in vars) {
				j = parseInt(i);
				if(typeof vars[j] != 'string' && typeof vars[j] != 'number' && typeof vars[j] != 'boolean' && vars[j] !== null) {
					throw new mad.error.WrongParametersException('I18n Error : Variables has to be a scalar');
				}
				returnValue += split[i] + vars[j];
			}
			returnValue += (typeof split[j + 1] != 'undefined' ? split[j + 1] : '');

			return returnValue;
		},

		/**
		 * Get entry in the dictionnary
		 * @param {string} str The dictionnary key
		 * @return {string}
		 */
		'getEntry': function (str) {
			var returnValue = str;
			if(typeof mad.lang.I18n.dico[str] != 'undefined' && this.dico[str] != '') {
				returnValue = mad.lang.I18n.dico[str];
			}
			return returnValue;
		}
	}, /** @prototype */ { });

});