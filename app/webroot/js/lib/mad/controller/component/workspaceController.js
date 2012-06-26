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
                // send to the event bus the information about the component creation
                this.getEventBus().trigger('lb_workspace_released', {'component':this});
                this._super();
            }
            
        });
        
    }
);
