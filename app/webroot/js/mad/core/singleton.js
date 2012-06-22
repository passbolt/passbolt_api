steal( 
    'jquery/class'
)
.then( 
    function($){
        
        /*
        * @class madsquirrel.core.Singleton
        * The Singleton class
        * @parent index
        * @constructor
        * Create a new singleton
        * @return {madsquirrel.core.Singleton}
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
                return new this(arguments);
            }
        },
        
        /** @prototype */
        {
            
            /**
             * Class Constructor. Singleton
             */
            'init': function(options)
            {
                if(this.Class.instance != null){
                   return this.Class.instance;
                }
                this.Class.instance = this;
            }
            
        });
        
    }
);
