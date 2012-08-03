steal( 
    'jquery/class'
)
.then( 
    function($){
        
        $.Class('mad.event.Eventable', 
                
        /** @static */
        { },
        
        /** @prototype */
        {
			/**
			* Trigger an element on the event bus
			* @param {String} eventName Event name
			* @param {Array} eventData (Optional) Parameters to send with the event 
			* @return {void}
			*/
			'trigger': function(eventName, eventData)
			{
				var data = typeof eventData != 'undefined' ? eventData : {};
				this.element.trigger(eventName, data);
			},

			/**
				* Bind an event on the event bus
				* @param {String} eventName Event name
				* @param {function} func The function to execute when the event is fired
				* @return {void}
				*/
			'bind': function(eventName, func)
			{
				this.element.bind(eventName, func)
			}
		});
        
    }
);
