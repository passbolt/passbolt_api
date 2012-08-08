steal( 
    MAD_ROOT+'/controller/component/containerController.js'
)
.then(
    function($){
        
		/*
        * @class mad.controller.component.WorkspaceController
        * @inherits {mad.controller.ContainerController}
        * @parent index
        *
		* Our implementation of a workspace controller. The component
		* is by definition an organized container which will carry other
		* components
		*
        * @constructor
        * Create a workspace controller
		* @param {array} options Optional parameters
        * @return {mad.controller.component.WorkspaceController}
        */
        mad.controller.component.ContainerController.extend('mad.controller.component.WorkspaceController', 
		/** @static */
		{
            'defaults': {
                'label': 'WorkspaceController',
				'templateUri' : '//app/view/template/workspace.ejs'
            }
        }
		/** @prototype */
        ,{  });
        
    }
);
