steal(
    MAD_ROOT
)
.then( 
    function($){
        
        mad.bootstrap.AppBootstrap.extend('passbolt.bootstrap.AppBootstrap', {    
            
            'init': function(options)
            {
                this._super(options);
            }
        });
    }
);
