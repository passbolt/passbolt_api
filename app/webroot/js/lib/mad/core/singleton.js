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
             * Singleton instance
             * @type {jQuery.Class}
             */
            'instance': null,
            
            /**
             * Get instance of the singleton
             * @return {jQuery.Class}
             */
            'singleton': function()
            {
                var returnValue = null;
                
                if(this.instance != null){
                    returnValue = this.instance;
                }else{
                    this.instance = 'CALL_FROM_SINGLETON';
                    returnValue = this.newInstance.apply(this, arguments);
                }
                
                return returnValue;
            }
        },
        
        /** @prototype */
        {
            
            /**
             * Class Constructor
             * @private
             */
            'init': function()
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
