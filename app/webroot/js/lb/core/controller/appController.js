steal(
    'jquery/controller'
    , 'jquery/controller/view'
    , 'jquery/view/ejs'
    , 'lb/core/controller/eventBusController.js'
    , 'lb/core/controller/workspaceController.js'
)
.then(
    function($){
        
        /*
        * @class lb.core.controller.AppController
        * The main application controller. This class is by definition a singleton.
        * 
        * @parent index
        * @constructor
        * Creates a application controller
        * @return {lb.core.controller.AppController}
        */
        lb.core.controller.Controller.extend('lb.core.controller.AppController', 
        
        /** @static */
        {
            
        },
        
        /** @prototype */
        {    
            
            /**
             * Reference to application's components
             * @type lb.core.controller.ComponentController
             * @private
             * @hide
             */
            '_components': {},
            
            // Class constructor
            'init' : function(el, options)
            {
                this._super(el, options);
            },
            
            /**
             * Get the event bus controller of the application
             * @return {lb.core.controller.EventBusController}
             */
            'getEventBus' : function()
            {
                return $('#'+lb.EVENTBUS_CONTROLLER_ID).controller();
            },
            
            /**
             * Reference a component to the application.
             * @param {lb.core.controller.Controller} controller The component to reference
             */
            'referenceComponent': function(component)
            {
                this._components[component.getId()] = component;
            },
            
            /**
             * Unreference a component to the application.
             * @param {lb.core.controller.Controller} controller The component to unreference
             */
            'unreferenceComponent': function(component)
            {
                delete this._components[component.getId()];
            },
            
            /**
             * Get a referenced component
             * @param {Strign} componentId Id of the target component
             */
            'getComponent': function(componentId)
            {
                var returnValue = null;
                if(typeof this._components[componentId] != 'undefined'){
                    returnValue = this._components[componentId];
                }
                return returnValue;
            },            
            
            // **********************************************************************************
            // Event Bus Observers
            // **********************************************************************************/
            
            '{lb.eventBus} lb_controller_released' : function(element, evt, data)
            {
                steal.dev.log(__('new controller (%s) has been released', data.component.element[0].id));
            },
            
            '{lb.eventBus} lb_component_released' : function(element, evt, data)
            {
                steal.dev.log(__('new component (%s) has been released', data.component.element[0].id));
            },
            
            '{lb.eventBus} lb_container_released' : function(element, evt, data)
            {
                steal.dev.log(__('new container (%s) has been released', data.component.element[0].id));
            },
            
            '{lb.eventBus} lb_app_ready' : function(element, evt, data)
            {
                steal.dev.log(__('application is ready'));
            }
            
        });
        
    }
);