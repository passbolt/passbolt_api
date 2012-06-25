steal(
    MAD_ROOT+'/controller/controller.js'
)
.then(
    function($){
        
        /*
        * @class mad.controller.AppController
        * The main application controller.
        * This class is by definition a singleton.
        * 
        * @parent index
        * @constructor
        * Creates the application controller
        * @return {mad.controller.AppController}
        */
        mad.controller.Controller.extend('mad.controller.AppController', 
        /** @static */
        { },
        /** @prototype */
        {   
            /**
             * Reference to application's components
             * @type lb.core.controller.ComponentController
             * @private
             */
            '_components': {},
            
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
             * @param {mad.controller.ComponentController} component The component to reference
             * @return {void}             * 
             */
            'referenceComponent': function(component)
            {
                this._components[component.getId()] = component;
            },
            
            /**
             * Unreference a component to the application.
             * @param {mad.controller.ComponentController} component The component to unreference
             * @return {void}
             */
            'unreferenceComponent': function(component)
            {
                delete this._components[component.getId()];
            },
            
            /**
             * Get a referenced component
             * @param {String} componentId Id of the target component
             * @return {mad.controller.ComponentController}
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
        
        // Bon a la fin de la classe comme ca, c'est un peu laid
        mad.controller.AppController.augment('mad.core.Singleton');
    }
);
