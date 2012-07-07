steal( 
    'jquery/controller'
    , MAD_ROOT+'/controller/component/containerController.js'
)
.then(
    function($){
        
        mad.controller.component.ContainerController.extend('mad.controller.component.WorkspaceController', {
            'default': {
                'label': 'WorkspaceController'
            }
        }
        ,{
            'init': function() 
            {
                this._super();
            }
            
        });
        
    }
);
