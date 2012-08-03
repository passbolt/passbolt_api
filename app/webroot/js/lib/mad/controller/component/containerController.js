steal(
    MAD_ROOT+'/controller/componentController.js'
)
.then( 
    function($){
        
        mad.controller.ComponentController.extend('mad.controller.component.ContainerController', {
            'default' : {
                'label': 'ContainerComponentController'
            }
        }
        ,{
            
            /**
             * Container's components
             * @type {Array}
             */
            'components': [],
            
            // Class Constructor
            'init' : function() 
            {
                this._super();
            }
            
            /**
             * Add a component to the container
             * @param {String} componentClass The component class to use to instantiate the component
             * @param {Array} componentOptions The optional data to pass to the component constructor
             * @param {String} area The area to add the component. Default : mad-container-main
             */
            , 'addComponent': function(componentClass, componentOptions, area)
            {
                var returnValue = null;
                
                var area = typeof area != 'undefined' ? area : 'mad-container-main';
                var $area = this.element.find('.'+area);
                
                var $component = $('<div id="'+componentOptions.id+'"/>').appendTo($area);
				// if the component is a singleton
				// @todo do not forget to check about the instanceof
				if(typeof componentClass.singleton != 'undefined'){
					var component = componentClass.singleton($component, componentOptions);
				}else{
					var component = new componentClass($component, componentOptions);
				}
                
                // reference the component
//                this.referenceComponent({
//                    'id':           componentOptions.id,
//                    'component':    component,
//                    'area':         area
//                });
//                //use a model maybe
//                this.components.push({
//                    'id':           componentOptions.id,
//                    'component':    component,
//                    'area':         area
//                });
                
                returnValue = component;
                return returnValue;
            }
            
//            /**
//             * Get a container's component
//             * @param {String} componentId The id of the target component
//             */
//            , 'getComponent': function()
//            {
//                this._super()
//            }
        });
        
    }
);
