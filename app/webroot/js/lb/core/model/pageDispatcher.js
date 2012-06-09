steal( 
    'lb/core/model/moduleControllerActionDispatcher.js'
)
.then( 
    function($){

        /*
        * @class lb.core.model.lb.core.model.PageDispatcher
        * The page dispatcher is the common way to dispatch actions in page. Here the controller which uses 
        * this dispatcher is a page.
        * This page dispatcher has to be a little bit refactored because of hard value !
        * Document it later, when the refactoring is ok.
        *   
        * @parent index
        */
        lb.core.model.ModuleControllerActionDispatcher.extend('lb.core.model.PageDispatcher', {
            
            /**
             * Dispatch the route to the convenient actions
             * @static
             */
            'dispatch' : function(route, options)
            {
                var controllers;
                var pluginNameController = lb.core.helper.routeHelper.pluginNameController(route);
                
                // The controllerId is given
                if(typeof(options.ControllerClass) == 'undefined'){
                    throw new Error("lb.core.model.ModuleControllerActionDispatcher error : The options.ControllerClass has to be defined");
                }
                
                var $pageController = $('#gacd-page-controller');
                // if a page has yet been loaded
                controllers = $pageController.data("controllers");
                if(typeof controllers != 'undefined'){
                    // if the current controller is not the targeted one
                    if(typeof controllers[pluginNameController] == 'undefined'){
                        // destroy the old one
                        for(var pluginNameController in controllers){
                            $pageController[pluginNameController]("destroy");
                        }
                        // build the new one
                        new options.ControllerClass($pageController);
                    }
                }
                // no controller = no page, create the new one
                else{
                    new options.ControllerClass($pageController);
                }
                
                options.controllerId = 'gacd-page-controller';
                this._super(route, options);
            }
        }
        , {});
        
    }
);
