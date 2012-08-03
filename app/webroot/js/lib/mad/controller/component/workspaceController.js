steal( 
    MAD_ROOT+'/controller/component/containerController.js'
)
.then(
    function($){
        
		/*
        * @class mad.controller.component.WorkspaceController
        * @inherits mad.controller.ComponentController
        * @parent index
        *
        * @constructor
        * 
		* @param {array} options Optional parameters
		* 
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
        ,{
			// constructor like
            'init': function(elt, options) 
            {
                this._super();
				this.setViewData('label', this.options.label);
            }
        });
        
    }
);
