steal('jquery', 'jquery/event/default', function($) {


var current,
	rnamespaces = /\.(.*)$/,
	returnFalse = function(){return false},
	returnTrue = function(){return true};

$.Event.prototype.isPaused = returnFalse

/**
 * @function jQuery.Event.prototype.pause
 * @parent jQuery.event.pause
 *
 * `event.paused()` pauses an event (to be resumed later):
 *
 *      $('.tab').on('show', function(ev) {
 *          ev.pause();
 *          // Resume the event after 1 second
 *          setTimeout(function() {
 *              ev.resume();
 *          }, 1000);
 *      });
 */
$.Event.prototype.pause = function(){
	// stop the event from continuing temporarily
	// keep the current state of the event ...
	this.pausedState = {
		isDefaultPrevented : this.isDefaultPrevented() ?
			returnTrue : returnFalse,
		isPropagationStopped : this.isPropagationStopped() ?
			returnTrue : returnFalse
	};
	
	this.stopImmediatePropagation();
	this.preventDefault();
	this.isPaused = returnTrue;
};

/**
 * @function jQuery.Event.prototype.resume
 * @parent jQuery.event.pause
 *
 * `event.resume()` resumes a paused event:
 *
 *      $('.tab').on('show', function(ev) {
 *          ev.pause();
 *          // Resume the event after 1 second
 *          setTimeout(function() {
 *              ev.resume();
 *          }, 1000);
 *      });
 */
$.Event.prototype.resume = function(){
	// temporarily remove all event handlers of this type 
	var handleObj = this.handleObj,
		currentTarget = this.currentTarget;
	// temporarily overwrite special handle
	var origType = jQuery.event.special[ handleObj.origType ],
		origHandle = origType && origType.handle;
		
	if(!origType){
		jQuery.event.special[ handleObj.origType ] = {};
	}
	jQuery.event.special[ handleObj.origType ].handle = function(ev){
		// remove this once we have passed the handleObj
		if(ev.handleObj === handleObj && ev.currentTarget === currentTarget){
			if(!origType){
				delete jQuery.event.special[ handleObj.origType ];
			} else {
				jQuery.event.special[ handleObj.origType ].handle = origHandle;
			}
		}
	}
	delete this.pausedState;
	// reset stuff
	this.isPaused = this.isImmediatePropagationStopped = returnFalse;
	
	if(!this.isPropagationStopped()){
		// fire the event again, no events will get fired until
		// same currentTarget / handler
		$.event.trigger(this, [], this.target);
	}
	
};

return $;
});