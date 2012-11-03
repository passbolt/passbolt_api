steal("jquery", "jquery/event/drop", function($) { //needs drop to determine if respondable

/**
 * @add jQuery.Drag.prototype
 */
$.Drag.prototype.
	/**
	 * @plugin jquery/event/drag/scroll
	 * @download  http://jmvcsite.heroku.com/pluginify?plugins[]=jquery/event/drag/scroll/scroll.js
	 * 
	 * `drag.scrolls(elements, [options])` scroll elements with 
	 * a scroll bar as the drag moves to borders.
	 * 
	 * The following sets up the drag motions to scroll `#todos` and the window.  Scrolling will
	 * start 50px away from a boundary and the speed will increase to 50px of scroll every 15ms.
	 * 
	 *     $('#todos').on(".todo","draginit", function(ev, drag){
	 *       drag.scrolls($('#todos').add(window), {
	 *         distance : 50,
	 *         delta : function(diff) { return (50 - diff) / 2},
	 *         direction : "y"
	 *       })
	 *     })
	 * 
	 * @param {jQuery} elements an array of elements to scroll.  The window can be in this array.
	 * @param {Object} [options] changes the default settings.
	 * 
	 *   - `distance` {number} 30 - how many pixels away from a boundry where we start scrolling
	 *   - `delta(diff)` {Function} - returns how far we should scroll.  It is passed how many pixels the cursor is
	 *     from the boundry.
	 *   - `direction` {String} - direction scrolling should happen.  "xy" is the default.
	 */
	scrolls = function(elements, options){
		var elements = $(elements);
		
		for(var i = 0 ; i < elements.length; i++){
			this.constructor.responder._elements.push( elements.eq(i).data("_dropData", new $.Scrollable(elements[i], options) )[0] )
		}
	},
	
$.Scrollable = function(element, options){
	this.element = jQuery(element);
	this.options = $.extend({
		// when  we should start scrolling
		distance : 30,
		// how far we should move
		delta : function(diff, distance){
			return (distance - diff) / 2;
		},
		direction: "xy"
	}, options);
	this.x = this.options.direction.indexOf("x") != -1;
	this.y = this.options.direction.indexOf("y") != -1;
}
$.extend($.Scrollable.prototype,{
	init: function( element ) {
		this.element = jQuery(element);
	},
	callHandlers: function( method, el, ev, drag ) {
		this[method](el || this.element[0], ev, this, drag)
	},
	dropover: function() {
		
	},
	dropon: function() {
		this.clear_timeout();
	}, 
	dropout: function() {
		this.clear_timeout();
	},
	dropinit: function() {
		
	},
	dropend: function() {},
	clear_timeout: function() {
		if(this.interval){
			clearTimeout(this.interval)
			this.interval = null;
		}
	},
	distance: function( diff ) {
		return (30 - diff) / 2;
	},
	dropmove: function( el, ev, drop, drag ) {
		
		//if we were about to call a move, clear it.
		this.clear_timeout();
		
		//position of the mouse
		var mouse = ev.vector(),
		
		//get the object we are going to get the boundries of
			location_object = $(el == document.documentElement ? window : el),
		
		//get the dimension and location of that object
			dimensions = location_object.dimensionsv('outer'),
			position = location_object.offsetv(),
		
		//how close our mouse is to the boundries
			bottom = position.y()+dimensions.y() - mouse.y(),
			top = mouse.y() - position.y(),
			right = position.x()+dimensions.x() - mouse.x(),
			left = mouse.x() - position.x(),
		
		//how far we should scroll
			dx =0, dy =0,
			distance =  this.options.distance;

		//check if we should scroll
		if(bottom < distance && this.y)
			dy = this.options.delta(bottom,distance);
		else if(top < distance && this.y)
			dy = -this.options.delta(top,distance)
		if(right < distance && this.options && this.x)
			dx = this.options.delta(right,distance);
		else if(left < distance && this.x)
			dx = -this.options.delta(left,distance);
		
		//if we should scroll
		if(dx || dy){
			//set a timeout that will create a mousemove on that object
			var self = this;
			this.interval =  setTimeout( function(){
				self.move($(el), drag.movingElement, dx, dy, ev, ev.clientX, ev.clientY, ev.screenX, ev.screenY)
			},15)
		}
	},
	/**
	 * Scrolls an element then calls mouse a mousemove in the same location.
	 * @hide
	 */
	move: function( scroll_element, drag_element, dx, dy, ev/*, x,y,sx, sy*/ ) {
		scroll_element.scrollTop( scroll_element.scrollTop() + dy);
		scroll_element.scrollLeft(scroll_element.scrollLeft() + dx);
		
		drag_element.trigger(
			$.event.fix({type: "mousemove", 
					 clientX: ev.clientX, 
					 clientY: ev.clientY, 
					 screenX: ev.screenX, 
					 screenY: ev.screenY,
					 pageX:   ev.pageX,
					 pageY:   ev.pageY}))
		//drag_element.synthetic('mousemove',{clientX: x, clientY: y, screenX: sx, screenY: sy})
	}
})

return $;
})