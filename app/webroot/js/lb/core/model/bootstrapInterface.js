steal( 
    'jquery/class'
    , 'lb/core/error/callInterfaceConstructor.js'
    , 'lb/core/error/callInterfaceFunction.js'
)
.then( 
    function($){
        
        /*
        * @class lb.core.model.Bootstrap
        * The core Interface Bootstrap is a representation of a Bootstrap process
        * @parent index
        */
        $.Class('lb.core.model.BootstrapInterface', 
                
        /*
        * @prototype
        */
        {
            
            /**
             * Constructor of the Bootstrap Class
             */
            'init' : function(options){
                throw new lb.core.error.CallInterfaceConstructor();
            }
            
            /**
             * The bootstrap process execute this function when the process
             * is finished
             */
            , 'ready': function(){
                throw new lb.core.error.CallInterfaceFunction();
            }
            
        });
        
    }
);