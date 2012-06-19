steal( 
    'jquery/class'
)
.then( 
    function($){
        
        /*
         * Override the Javascript MVC class with our needed feetures
         * <br/>
         * decorate function
         * BLABLABLA
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
            }

            return this;
        }
    }
);
