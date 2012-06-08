steal( 
    'lb/core/model/appBootstrap.js'
)
.then( 
    function($){
        
        lb.core.model.AppBootstrap.extend('passbolt.password.model.AppBootstrap', {    
            
            'init': function(options)
            {
                this._super(options);
            }
        });
    }
);
