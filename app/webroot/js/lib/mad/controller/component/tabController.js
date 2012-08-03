steal( 
    'jquery/view/ejs',
    MAD_ROOT+'/controller/component/containerController.js',
	MAD_ROOT+'/view/component/tab.js',
	MAD_ROOT+'/view/template/component/tab.ejs'
)
.then(
    function($){
        
        /*
        * @class mad.controller.component.TabController
        * @inherits mad.controller.ComponentController
        * @parent index 
		* 
        * @constructor
        * Creates a new TabController
        * @return {mad.controller.component.TabController}
        */
        mad.controller.component.ContainerController.extend('mad.controller.component.TabController', {
            'defaults' : {
                'label': 'TabController',
				'viewClass':		mad.view.component.Tab
            }
        }
        ,{
            
            /**
             * Add a component to the container
             * @param {String} componentClass The component class to use to instantiate the component
             * @param {Array} componentOptions The optional data to pass to the component constructor
             * @param {String} area The area to add the component. Default : mad-container-main
             */
            'addComponent': function(componentClass, componentOptions, area)
            {
                var returnValue = null,
					$component = null;
				
				$component = this.view.addComponent(componentOptions);
				returnValue = new componentClass($component, componentOptions);
				
                return returnValue;
            }
            
        });
        
    }
);