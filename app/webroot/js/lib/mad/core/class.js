steal( 
    'jquery/class'
)
.then( 
    function($){
        
        /*
         * @class jQuery.Class
         * Override the Javascript MVC class with our needed features :
         * <br/>
         * - Allow developpers to decorate an instance with a subset of function (prototype function right now)
         * - Allow developpers to augment an class with properties of another one (prototype and static vars and functions, test the controller)
         */
        
        /* @function decorate
         * Decorate an instance with a subset of functions
         * @param  {String} decoratorName The name of the object to use as decorator
         * @return  {Object} Returns the decorated instance
        */  
        $.Class.prototype.decorate = function(decoratorName)
        {
            var Decorator = $.String.getObject(decoratorName)
                lvl = null;

            // Not yet decorated
            if(typeof this._decorator == 'undefined'){
                this._decorator = [];
            }
            // Current decorator level
            lvl = this._decorator.length;
            // Add a new decoration layer
            this._decorator[lvl] = {'name':decoratorName, f:{}};

            // decorate the instance with the functions of the Decorator Class
            for(var i in Decorator){
//                    console.log('decorate '+i+' by '+decoratorName+' in level '+lvl);
                if(typeof this[i] != 'undefined'){

                    // store the old function
                    this._decorator[lvl].f[i] = this[i];

                    // add the decorate function
                    this[i] = (function(instance, fn, lvl) {
                        return function(){
                            var tmp = this._super,
                                returnValue;
                            this._super = instance._decorator[lvl].f[fn];
                            var returnValue = Decorator[fn].apply(instance, arguments);
                            this._super = tmp;
                            return returnValue;
                        }
                    })(this, i, lvl);

                }
                else{
                    this[i] = (function(instance, fn, lvl) {
                        return function(){
                            var returnValue = Decorator[fn].apply(instance, arguments);
                            return returnValue;
                        }
                    })(this, i, lvl);
                }
            }

            return this;
        }
        
        /* @function augment
         * Augment a class with properties of anoter
         * @param  {String} objectName The name of the object to use to augment the class
         * @return  {void}
        */  
        $.Class.augment = function(objectName)
        {
            var Augmentator = $.String.getObject(objectName);
            
            // Add static properties to the class to augment
            var blackListedStaticProperties = ['namespace', 'shortName', 'fullName', 'defaults'];
            for(var i in Augmentator){
                
                if(typeof $.Class[i] == 'undefined' && $.inArray(i, blackListedStaticProperties) == -1){
                    this[i] = Augmentator[i];
                }
            }
            
            // Add prototype properties to the class to augment
            var blackListedPrototypeProperties = ['Class'];
            for(var i in Augmentator.prototype){
                if(typeof $.Class.prototype[i] == 'undefined' && $.inArray(i, blackListedPrototypeProperties) == -1){
                    
                    // augment a constructor, try something closed to multiple inheritance
                    if(i=='init'){
                        if(typeof this.prototype.__inits == 'undefined') {
                            // List of constructors
                            this.prototype.__inits = [];
                            this.prototype.__inits.push(this.prototype[i]);
                            // replace the init function, with a function which will execute all the init function
                            this.prototype[i] = (function(clazz, fn){
                                return function(el, options, test){
                                    for(var i in this.__inits){
                                        this.__inits[i].apply(this, arguments);
                                    }
                                }
                            })(this, 'init');
                        }
                        // Add the init function of the Augmentator to the list of init function to execute when creating an instance of the class
                        this.prototype.__inits.push(Augmentator.prototype[i]);
                    }
                    // add the function to the brol
                    else {
                        this.prototype[i] = Augmentator.prototype[i];
                    }
                }
            }
        }
    }
);
