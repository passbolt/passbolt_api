steal( 
    'lb/core/model/bootstrapInterface.js'
)
.then( 
    function($){
        
        lb.core.model.BootstrapInterface.extend('lb.core.model.ModuleBootstrap', {}
        , {    
            
            'init': function(el, options)
            {
                this._super(el, options);
            }
        });
    }
);
