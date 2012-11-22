steal('jquery', 'jquery/event/livehack', function($) {

/**
 * @class jQuery.Mousehold
 * @plugin jquery/event/mousehold
 * @download  http://jmvcsite.heroku.com/pluginify?plugins[]=jquery/event/mousehold/mousehold.js
 * @parent jQuery.event.mousehold
 *
 * Creates a new mousehold. The constructor should not be called directly.
 *
 * An instance of `$.Mousehold` is passed as the second argument to each
 * [jQuery.event.mousehold] event handler:
 *
 *      $('#button').on("mousehold", function(ev, mousehold) {
 *          // Set the mousehold delay to 100ms
 *          mousehold.delay(100);
 *      });
 */
$.Mousehold = function(){
	this._delay =  $.Mousehold.delay;
	this.fireCount = 0;
};

/**
 * @Static
 */
$.extend($.Mousehold,{
	/**
	 * @attribute delay
	 *
	 * `$.Mousehold.delay` is the delay (in milliseconds) after which the hold is
	 * activated by default.
	 *
	 * Set this value as a global default. The default is 500ms.
	 *
	 *      // Set the global hover delay to 1 second
	 *      $.Mousehold.delay = 1000;
	 */
	delay: 100
});

/**
 * @Prototype
 */
$.extend($.Mousehold.prototype,{
	/**
	 * `mousehold.delay(time)` sets the delay (in ms) for this mousedown.
	 * This method should only be used in [jQuery.event.mousehold mousehold]:
	 *
	 *      $('.holdable').on('mousehold', function(ev, mousehold) {
	 *          // Set the delay to 100ms
	 *          mousehold.delay(100);
	 *      });
	 *
	 * @param {Number} delay the number of milliseconds used to determine a mousehold
	 * @return {$.Mousedown} The mousehold object
	 */
	delay: function( delay ) {
		this._delay = delay;
		return this;
	}
});

 var event = $.event, 
	onmousehold = function(ev) {

		var mousehold = new $.Mousehold(),
			fireStep = 0,
			delegate = ev.delegateTarget || ev.currentTarget,
			selector = ev.handleObj.selector,
			downEl = $(this),
			timeout;

		var updateCoordinates = function(event){
			ev.offsetX = event.offsetX;
			ev.offsetY = event.offsetY;
			ev.target = event.target;
		}, clearMousehold = function() {
			clearInterval(timeout);

			if (fireStep == 1) {
				$.each(event.find(delegate, ["mousehold"], selector), function(){
					mousehold.fireCount = 1;
					this.call(downEl, ev, mousehold)
				})
			}

			fireStep = 0;
			downEl.unbind("mouseleave", clearMousehold)
			  	  .unbind("mouseup", clearMousehold)
			  	  .unbind("mousemove", updateCoordinates);
		};

		fireStep = 1;
		timeout = setTimeout(function() {
			$.each(event.find(delegate, ["mousehold"], selector), function(){
				mousehold.fireCount++;
				this.call(downEl, ev, mousehold)
			});

			fireStep = 2;
			timeout = setTimeout(arguments.callee, mousehold._delay)
		}, mousehold._delay);

		downEl.bind("mouseleave", clearMousehold)
			  .bind("mouseup", clearMousehold)
			  .bind('mousemove', updateCoordinates)
	};

 /**
 * @add jQuery.event.special
 */
// Attach events
event.setupHelper([
/**
 * @attribute mousehold
 * @parent jQuery.event.mousehold
 *
 * `mousehold` is called while the mouse is being held down.
 *
 * This is different from `mousedown` in the fact that `mousedown`
 * will only fire once no matter how long you hold down the mouse.
 *
 * For example, if you were to create a 'button' and hold
 * down the button, 'mousehold' would fire until you release
 * your cursor.
 *
 * Optionally, can set the delay between the event triggering.
 * The `mousehold` class will contain the current delay and
 * the number of times the event has fired.
 *
 * The `mousehold` event also updates the `offsetX` and `offsetY`
 * of the event since this would be for the original event mouse offset.
 *
 */
"mousehold" ], "mousedown", onmousehold );
return $;
});