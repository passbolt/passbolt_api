steal( 
    'jquery/view/ejs',
    'jquery/controller',
    'jquery/controller/view',
    MAD_ROOT+'/route/moduleControllerActionDispatcher.js'
)
.then( 
    function($){

        /*
        * @class mad.controller.Controller
        * The core class Controller is an extension of the JavascriptMVC Controller. This
        * class provides to developpers specific common tools to create application's 
        * controllers.
        * 
        * @parent index
        * @constructor
        * Creates a new controller
        * @return {mad.controller.Controller}
        */
        $.Controller('mad.controller.Controller',
        
        /** @static */
        {
            /**
             * Get the controller dispatcher. The Dispatcher explain how the routes have to
             * be dispatch for this controller.
             * 
             * @return {mad.route.Dispatcher} By default return the common module -> controller -> action
             * dispatcher.
             */
            'getDispatcher': function()
            {
                return mad.route.ModuleControllerActionDispatcher;
            }
        },
        
        /** @prototype */
        {
            
            // Class constructor
            'init' : function(el, options)
            {
                // send to the event bus the information about the component creation
                this.options = $.extend(true, {}, this.options, options);
                // reference the controller to the application
                this.getApp().referenceComponent(this);
                
                mad.eventBus.trigger('mad_controller_released', {'component':this});
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
             * @return {mad.controller.AppController} The application controller
             */
            'getApp' : function()
            {
                var returnValue = null;
                
                if(this instanceof mad.controller.AppController){
                    returnValue = this;
                } 
                else {
                    if(mad.controller.AppController.getGlobal('APP_CONTROLLER_CLASS').instance != null){
                        returnValue = mad.controller.AppController.getGlobal('APP_CONTROLLER_CLASS').singleton();
                    }
                }
                
                return returnValue;
            },
            
//            /**
//             * Get the application event bus controller
//             * @return {mad.controller.EventBusController} Get the application event bus
//             */
//            'getEventBus' : function()
//            {
//                var returnValue = null;
//                if(this.getApp()) returnValue = this.getApp().getEventBus();
//                return returnValue;
//            },
            
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
