/**
 * Our implementation of the I18n system.
 */

/**
 * Translate the given string.
 * @param {string} str The string to translate
 * @param {...} arguments Add as many variable as they are hooks in the variable
 * to translate
 * @return {string} The translated string
 */
__ = function(str)
{
    var variables = []
        translation = '';
        
    // extract variables from arguments  
    for(var i in arguments){
        variables.push(arguments[i]);
    }
    
    translation = __.replaceHooks(__.getEntry(str), variables.slice(1));
    return translation;
};

/**
 * The current dictionnary to use
 * @type {array}
 * @private
 */ 
__.dico = [];

/**
 * Load the given dictionnary as dictionnary of translated terms
 * @public
 * @param {array} dico The dictionnary to use
 * @return {void}
 */
__.loadDico = function(dico)
{
    __.dico = dico;
}

/**
 * Replace the variables' hooks in the translated string
 * @private
 * @param {string} str The translated string
 * @param {array} variables The variables to inject in the string
 * @return {string}
 */
__.replaceHooks = function(str, variables)
{
    var returnValue = ''
        split = [];
    
    // split the string by the variable's hooks
    split = str.split('%s');
    
    // if the string does not contain the proper number of variables throw an exception
    if(split.length != variables.length+1){
        throw new Error('Transaltion Error : The sentence to translate does not contain as many hooks as they are variables');
    }
    // no hook found in the string
    if(split.length < 2){
        return;
    }
    // replace string's hooks with the given variables
    var j;
    for(var i in variables){
        j = parseInt(i);
        returnValue += split[i]+variables[j];
    }
    returnValue += (typeof split[j+1] != 'undefined' ? split[j+1] : '');
    
    return returnValue;
}

/**
 * Get entry in the dictionnary
 * @private
 * @param {string} str The dictionnary key
 * @return {string}
 */
__.getEntry = function(str)
{
    var returnValue = str;
    if(typeof __.dico[str] != 'undefined' && __.dico[str] != ''){
        returnValue =  __.dico[str];
    }
    return returnValue;
}
