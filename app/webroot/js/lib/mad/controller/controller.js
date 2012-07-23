steal( 
    'jquery/view/ejs',
    'jquery/controller',
    'jquery/controller/view',
    MAD_ROOT+'/route/extensionControllerActionDispatcher.js'
)
.then( 
    function($){

        /*
        * @class mad.controller.Controller
        * @inherits $.Controller
        * @parent index
        * 
        * The core class Controller is an extension of the JavascriptMVC Controller. This
        * class provides to developpers specific common tools to create application's 
        * controllers.
        * 
        * @constructor
        * Creates a new application controller.
        * <br/>
        * References it to the application controller.
        * @return {mad.controller.Controller}
        */
        $.Controller('mad.controller.Controller',
        
        /** @static */
        {
            /**
             * Get the controller dispatcher. The Dispatcher explain how the routes have to
             * be dispatch for this controller.
             * 
             * @return {mad.route.Dispatcher} By default return the common extension -> controller -> action
             * dispatcher.
             */
            'getDispatcher': function()
            {
                return mad.route.ExtensionControllerActionDispatcher;
            }
        },
        
        /** @prototype */
        {
            
            // Class constructor
            'init' : function(el, options)
            {
                // set the options
                this.options = $.extend(true, {}, this.options, options);
                // reference the controller to the application
                this.getApp().referenceComponent(this);
                // notice all a new controller has been released
                if(mad.eventBus) mad.eventBus.trigger(mad.APP_NS_ID+'_controller_released', {'component':this});
            },
            
            /**
             * Destroy the component and unreference it
             * @return {void}
             */
            'destroy': function()
            {
                // unreference the application to the app
                this.getApp().unreferenceComponent(this);
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
                    if(mad.app != null){
                        returnValue = mad.app;
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
