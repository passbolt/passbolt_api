steal( 
    MAD_ROOT
)
.then( 
    function($){

        /*
        * @class lb.core.controller.EventBusController
        * The class EventBusController allows developpers to create a bus to manage all events
        * in a specific context. This bus will be attached to a DOM Node and developpers could
        * fire and bind events on it.
        * @parent index
        * @constructor
        * Creates a new event bus controller
        * @return {lb.core.controller.EventBusController}
        */
        mad.controller.Controller.extend('lb.core.controller.EventBusController', 
        
        /** @Static */
        {
            //pluginName: "lb_core_eventbus"
        }, 
        
        /** @prototype */
        {
            'init': function(){
                // the parent use the event bus controller, not the best way, but for the moment
                // do not call the parent init function
            },
            
            /**
             * Trigger an element on the event bus
             * @param {String} eventName Event name
             * @param {Array} eventData (Optional) Parameters to send with the event
             * @return {}
             */
            'trigger': function(eventName, eventData)
            {
                var data = typeof eventData != 'undefined' ? eventData : {};
                this.element.trigger(eventName, data);
            }
        });
        
    }
);
