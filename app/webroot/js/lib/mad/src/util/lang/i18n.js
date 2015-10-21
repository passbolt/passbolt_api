import 'mad/util/util';
var glbl = typeof window !== "undefined" ? window : global

/**
 * Translate a string.
 *
 * @param {string} str The string to translate
 * @param {...} arguments Add as many variable as they are hooks in the variable
 * to translate
 * @return {string} The translated string
 */
var __ = function (str) {
    // Extract variables from arguments.
    var args = Array.prototype.slice.call(arguments, 1);
    return mad.I18n.translate(str, args);
};
glbl.__ = __;

/**
 * @parent Mad.core_api
 * @inherits mad.Construct
 *
 * The aim of the object I18n is to provide an internationalization layer
 *
 * It provides a function __() to translate your string
 * ```
 * __('Hello %s boy', 'mad')
 * ```
 *
 */
var I18n = mad.I18n = can.Construct.extend('mad.I18n', /** @static */ {

    /**
     * The dictionary in use.
     * @type {array}
     */
    dico: {},

    /**
     * Translate a string.
     *
     * @param {string} str The string to translate
     * @param {array} vars The array of variables to inject in the string
     * @return {string} The translated string
     */
    translate: function (str, vars) {
        var vars = typeof vars != 'undefined' ? vars : [];
        return mad.I18n.replaceHooks(this.getEntry(str), vars);
    },

    /**
     * Load a dictionary.
     *
     * @param {array} dico The dictionary to use
     */
    loadDico: function (dico) {
        for (var i in dico) {
            mad.I18n.dico[i] = dico[i]; //make a copy of the data to be sure there will be existing in the app scope
        }
    },

    /**
     * Replace the variables' hooks in a string.
     *
     * @param {string} str The string to work on
     * @param {array} vars The variables to inject in the string
     * @return {string}
     */
    replaceHooks: function (str, vars) {
        var returnValue = '',
            split = [];

        // Split the string regarding the variable's hooks.
        split = str.split('%s');

        // If the string does not contain the proper number of variables throw an exception
        if (split.length != vars.length + 1) {
            throw mad.Exception.get('mad.I18n::replaceHooks() expects as many variables as hooks in the sentence');
        }
        // No hook found in the string.
        if (split.length < 2) {
            return str;
        }
        // Replace string's hooks with the variables.
        var j;
        for (var i in vars) {
            j = parseInt(i);
            if (typeof vars[j] != 'string' && typeof vars[j] != 'number' && typeof vars[j] != 'boolean' && vars[j] !== null) {
                throw mad.Exception.get('mad.I18n::replaceHooks() expects variables to be scalar');
            }
            returnValue += split[i] + vars[j];
        }
        returnValue += (typeof split[j + 1] != 'undefined' ? split[j + 1] : '');

        return returnValue;
    },

    /**
     * Get an entry in the dictionary.
     *
     * @param {string} str The dictionary key
     * @return {string}
     */
    getEntry: function (str) {
        if (typeof mad.I18n.dico[str] != 'undefined' && this.dico[str] != '') {
            return mad.I18n.dico[str];
        }
        return str;
    }

}, /** @prototype */ {});

export default I18n;
