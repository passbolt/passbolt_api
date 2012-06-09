steal( 
    'jquery/controller'
    , 'lb/core/controller/containerController.js'
)
.then(
    function($){
        
        lb.core.controller.ContainerController.extend('lb.core.controller.WorkspaceController', {
            'default': {
                'label': 'WorkspaceController'
            }
        }
        ,{
            'init': function() 
            {
                // send to the event bus the information about the component creation
                this.getEventBus().trigger('lb_workspace_released', {'component':this});
                this._super();
            }
            
        });
        
    }
);
