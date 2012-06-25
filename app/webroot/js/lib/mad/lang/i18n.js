steal( 
    MAD_ROOT+'/core/singleton.js'
)
.then( 
    function($){

        /**
        * Our implementation of the I18n system.
        * Alias to the I18n Object
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
            var args = Array.prototype.slice.call(arguments);
            delete args.callee;
            delete args.caller;
            for(var i in args){
                variables.push(args[i]);
            }
            
            var i18n = mad.lang.I18n.singleton();
            translation = i18n.translate(str, variables.slice(1));
            return translation;
        };

        /*
        * @class lb.core.model.Bootstrap
        * The ajax wrapper allows developper to make their ajax request, moreover
        * it allows them to make ajax transaction to minimize server calls by 
        * aggregating ajax requests 
        * @parent index
        * @constructor
        * Creates a new ajax transaction builder
        * @return {lb.core.controller.AjaxWrapper}
        */
        mad.core.Singleton.extend('mad.lang.I18n',
        
        /** @prototype */
        {
            
            /**
            * The current dictionnary to use
            * @type {array}
            * @private
            */ 
            'dico': {},
            
            /**
             * Translate the string
             * @public
             * @param {string} str The string to translate
             * @param {array} vars The array of variables to inject in the string
             * @return {string} The translated string
             */
            'translate': function(str, vars)
            {
                var vars = typeof vars != 'undefined' ? vars : [];
                return this.replaceHooks(this.getEntry(str), vars);
            },
            
            /**
            * Load the given dictionnary as dictionnary of translated terms
            * @public
            * @param {array} dico The dictionnary to use
            * @return {void}
            */
            'loadDico': function(dico)
            {
                for(var i in dico){
                    this.dico[i] = dico[i]; //make a copy of the data to be sure there will be existing in the app scope
                }
            },

            /**
            * Replace the variables' hooks in the translated string
            * @private
            * @param {string} str The translated string
            * @param {array} vars The variables to inject in the string
            * @return {string}
            */
            'replaceHooks': function(str, vars)
            {
                var returnValue = ''
                    split = [];
                
                // split the string by the variable's hooks
                split = str.split('%s');

                // if the string does not contain the proper number of variables throw an exception
                if(split.length != vars.length+1){
                    throw new mad.error.WrongParameters('I18n Error : The sentence to translate does not contain as many hooks as they are variables');
                }
                // no hook found in the string
                if(split.length < 2){
                    return str;
                }
                // replace string's hooks with the given variables
                var j;
                for(var i in vars){
                    j = parseInt(i);
                    if(typeof vars[j] != 'string' && typeof vars[j] != 'number' && typeof vars[j] != 'boolean'){
                        throw new mad.error.WrongParameters('I18n Error : Variables has to be a scalar');
                    }
                    returnValue += split[i]+vars[j];
                }
                returnValue += (typeof split[j+1] != 'undefined' ? split[j+1] : '');

                return returnValue;
            },

            /**
            * Get entry in the dictionnary
            * @private
            * @param {string} str The dictionnary key
            * @return {string}
            */
            'getEntry': function(str)
            {
                var returnValue = str;
                if(typeof this.dico[str] != 'undefined' && this.dico[str] != ''){
                    returnValue =  this.dico[str];
                }
                return returnValue;
            }
        });
   
    }
);

