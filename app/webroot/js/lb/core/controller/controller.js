steal( 
    'jquery/controller'
    , 'lb/core/model/moduleControllerActionDispatcher.js'
)
.then( 
    function($){

        /*
        * @class lb.core.controller.Controller
        * The core class Controller is an extension of the JavascriptMVC Controller. This
        * class provides to developpers specific common tools to create application's 
        * controllers.
        * 
        * @parent index
        * @constructor
        * Creates a new controller
        * @return {lb.core.controller.Controller}
        */
        $.Controller('lb.core.controller.Controller',
        
        /** @static */
        {
            /**
             * Get the controller dispatcher. The Dispatcher explain how the routes have to
             * be dispatch for this controller.
             * 
             * @return {lb.core.model.Dispatcher} By default return the common module -> controller -> action
             * dispatcher.
             */
            'getDispatcher': function()
            {
                return lb.core.model.ModuleControllerActionDispatcher;
            }
        },
        
        /** @prototype */
        {
            
            // Class constructor
            'init' : function(el, options)
            {
                // send to the event bus the information about the component creation
                this.options = $.extend(true, {}, this.options, options);
                // reference the application to the app
                this.getApp().referenceComponent(this);
                
                this.getEventBus().trigger('lb_controller_released', {'component':this});
            },
            
            /**
             * Destroy the component and unreference it
             * @return {void}
             */
            'destroy': function()
            {
                // unreference the application to the app
                var app = this.getApp();
                if(app){
                    app.unreferenceComponent(this);
                }
                
                this._super();
            },
            
            /**
             * Get the application controller
             * @return {lb.core.controller.AppController} The application controller
             */
            'getApp' : function()
            {
                var returnValue = null;
                var appController = $('#'+lb.APP_CONTROLLER_ID); 
//                var appController = lb.app; // not initialized for the appController, maybe override this function in the app controller class
                if(appController.length){
                    returnValue = appController.controller();
                }
                return returnValue;
            },
            
            /**
             * Get the application event bus controller
             * @return {lb.core.controller.EventBusController} Get the application event bus
             */
            'getEventBus' : function()
            {
                return this.getApp().getEventBus();
            },
            
            /**
             * Get id of the controller
             * @return {String} Id of the component
             */
            'getId': function()
            {
                return this.element[0].id;
            }
            
        });
        
    }
);
