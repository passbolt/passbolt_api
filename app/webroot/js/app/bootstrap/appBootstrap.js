steal( 
    'lb/core/model/appBootstrap.js'
    , 'app/controller/appController.js'
)
.then( 
    function($){
        
        lb.core.model.AppBootstrap.extend('passbolt.bootstrap.AppBootstrap', {    
            
            'init': function(options)
            {
                this._super(options);
            }
        });
    }
);
