steal( 
    'jquery/view/ejs',
    MAD_ROOT+'/controller/component/containerController.js'
)
.then(
    MAD_ROOT+'/view/template/component/tab.ejs',
    function($){
        
        /*
        * @class mad.controller.TabController
        * @parent index 
        * @constructor
        * Creates a new TabController
        * @return {mad.controller.TabController}
        */
        mad.controller.component.ContainerController.extend('mad.controller.component.TabController', {
            'defaults' : {
                'label': 'TabController'
            }
        }
        ,{
            
            'init' : function(el, options)
            {
                this._super();
                //this.render();
            },
            
            /**
             * Add a component to the container
             * @param {String} componentClass The component class to use to instantiate the component
             * @param {Array} componentOptions The optional data to pass to the component constructor
             * @param {String} area The area to add the component. Default : lb-container-main
             */
            'addComponent': function(componentClass, componentOptions, area)
            {
                var returnValue = null;
                
                // add a tag for the component to add
                var $component = $('<div id="'+componentOptions.id+'"></div>').appendTo(this.element);
                // Add the tab with the jquery tabs API
                this.element.tabs('add', '#'+componentOptions.id, componentOptions.label);
                // Instantiate the component
                var component = new componentClass($component, componentOptions);
                
                returnValue = component;
                return returnValue;
            },
            
            /**
             * Render the tab container
             */
            'render': function()
            {
                this._super();
                this.element.tabs();
            }
            
        });
        
    }
);
