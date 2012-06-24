steal( 
    'jquery/class'
)
.then( 
    function($){
        
        /*
        * @class mad.core.Singleton
        * The Singleton class
        * @parent index
        * @constructor
        * Create a new singleton
        * @return {mad.core.Singleton}
        */
        $.Class('mad.core.Singleton', 
                
        /** @static */
        
        {
            /**
             * Ajax wrapper instance.
             */
            'instance': null,
            
            /**
             * Get instance of the Ajax Wrapper singleton
             */
            'singleton' : function()
            {
                if(this.instance != null){
                    return this.instance;
                }else{
                    this.instance = 'CALL_FROM_SINGLETON';
                    return new this(arguments);
                }
            }
        },
        
        /** @prototype */
        {
            
            /**
             * Class Constructor. Singleton
             * @private
             */
            'init': function(options)
            {
                if(this.Class.fullName == 'mad.core.Singleton'){
                    throw new mad.error.CallAbstractFunction();
                }
                else if(this.Class.instance != 'CALL_FROM_SINGLETON'){
                    throw new mad.error.CallPrivateFunction();
                }
                
                this.Class.instance = this;
            }
            
        });
        
    }
);
