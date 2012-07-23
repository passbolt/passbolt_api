steal( 
    MAD_ROOT+'/controller/component/containerController.js'
)
.then(
    function($){
        
        mad.controller.component.ContainerController.extend('mad.controller.component.WorkspaceController', 
		/** @static */
		{
            'defaults': {
                'label': 'WorkspaceController'
            }
        }
		/** @prototype */
        ,{
            'init': function(elt, options) 
            {
                this._super();
            }
            
        });
        
    }
);
