steal('jquery', 'jquery/event/livehack', function( $ ) {

	var supportTouch = "ontouchend" in document,
		scrollEvent = "touchmove scroll",
		touchStartEvent = supportTouch ? "touchstart" : "mousedown",
		touchStopEvent = supportTouch ? "touchend" : "mouseup",
		touchMoveEvent = supportTouch ? "touchmove" : "mousemove",
		data = function(event){
			var d = event.originalEvent.touches ?
				event.originalEvent.touches[ 0 ] || event.originalEvent.changedTouches[ 0 ] :
				event;
			return {
				time: (new Date()).getTime(),
				coords: [ d.pageX, d.pageY ],
				origin: $( event.target )
			};
		},
		touchStartTime = Date.now(),
		touchStart = {};

	// Listen and record information on touch start
	$(document.body).on(touchStartEvent, function(ev) {
		touchStart = data(ev);
		touchStartTime = Date.now();
	});

	/**
	* @add jQuery.event.special
	*/
	$.event.setupHelper( ["tap"], touchStopEvent, function( ev ) {
		//listen to mouseup
		var stop = data(ev),
			start = touchStart,
			delegate = ev.delegateTarget || ev.currentTarget,
			$delegate = $(delegate),
			originalEvent = ev,
			selector = ev.handleObj.selector,
			entered = this,
			moved = false,
			touching = true,
			timer,
			now = new Date();
		

		// If the time between touch up and down was small and user's finger
		// didn't move far, find all the tap events and trigger.
		if(now - touchStartTime < 500 && ( Math.abs( start.coords[0] - stop.coords[0] ) < 10) &&
				( Math.abs( start.coords[1] - stop.coords[1] ) < 10 )) {
			$.each($.event.find( delegate, ["tap"], selector ), function() {

				var tap = new $.Event('tap');

				var result = this.call( entered, tap, {
					start : start, 
					end: stop
				});

				if(result == false || tap.isDefaultPrevented()) {
					originalEvent.preventDefault();
				}
				if(result == false || tap.isPropagationStopped()) {
					originalEvent.stopPropagation();
				}
				if(tap.isImmediatePropagationStopped()) {
					originalEvent.stopImmediatePropagation();
				}
			});
		}
		
	});

	return $;
});
