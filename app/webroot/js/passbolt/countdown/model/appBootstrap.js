steal( 
    'lb/core/model/appBootstrap.js'
)
.then( 
    function($){
        
        lb.core.model.AppBootstrap.extend('passbolt.countdown.model.AppBootstrap', {    
            
            'init': function(options)
            {
                this._super(options);
            }
        });
    }
);
