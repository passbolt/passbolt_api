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
        { 
            /**
             * The application namespace
             * @static
             */
            'APP_NAMESPACE_ID': null,
            
            /**
             * get global
             * @param {string} name Name of the variable 
             * @return {mixed} Value of the variable 
             * @static
             */
            'getGlobal': function(name)
            {
                return window[mad.controller.Controller.APP_NAMESPACE_ID][name];
            },
            
            /**
             * set global
             * @param {string} name Name of the variable 
             * @param {mixed} value Value of the variable 
             * @static
             */
            'setGlobal': function(name, value)
            {
                window[mad.controller.Controller.APP_NAMESPACE_ID][name] = value;
                return window[mad.controller.Controller.APP_NAMESPACE_ID][name];
            }
        
        },
        /** @prototype */
        {   
            /**
             * Reference to application's components
             * @type mad.controller.ComponentController
             * @private
             */
            '_components': {},
            
//            /**
//             * Get the event bus controller of the application
//             * @return {mad.event.EventBus}
//             * @deprecated
//             */
//            'getEventBus' : function()
//            {
//                throw new Error ('the function getEventBus is deprecated');
//                return mad.eventBus;
//            },
            
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
            
            '{mad.eventBus} {mad.appNamespaceId}_controller_released' : function(element, evt, data)
            {
                steal.dev.log(__('new controller (%s) has been released', data.component.element[0].id));
            },
            
            '{mad.eventBus} {mad.appNamespaceId}_component_released' : function(element, evt, data)
            {
                steal.dev.log(__('new component (%s) has been released', data.component.element[0].id));
            },
            
            '{mad.eventBus} {mad.appNamespaceId}_container_released' : function(element, evt, data)
            {
                steal.dev.log(__('new container (%s) has been released', data.component.element[0].id));
            },
            
            '{mad.eventBus} {mad.appNamespaceId}_app_ready' : function(element, evt, data)
            {
                steal.dev.log(__('application is ready'));
            }
            
        });
        
        // Bon a la fin de la classe comme ca, c'est un peu laid
        mad.controller.AppController.augment('mad.core.Singleton');
        
        // make aliases with some function
        mad.getGlobal = mad.controller.AppController.getGlobal;
        mad.setGlobal = mad.controller.AppController.setGlobal;
    }
);
