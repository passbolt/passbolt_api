steal('jquery', 'jquery/event/livehack', function ($) {
	/**
	 * @class jQuery.Hover
	 * @plugin jquery/event/hover
	 * @download  http://jmvcsite.heroku.com/pluginify?plugins[]=jquery/event/hover/hover.js
	 * @parent jQuery.event.hover
	 *
	 * Creates a new hover. The constructor should not be called directly.
	 *
	 * An instance of `$.Hover` is passed as the second argument to each
	 * [jQuery.event.hover] event handler:
	 *
	 *      $('#menu').on("hoverinit", function(ev, hover) {
	 *          // Set the hover distance to 20px
	 *          hover.distance(20);
	 *      });
	 */
	$.Hover = function () {
		this._delay = $.Hover.delay;
		this._distance = $.Hover.distance;
		this._leave = $.Hover.leave
	};
	/**
	 * @Static
	 */
	$.extend($.Hover, {
		/**
		 * @attribute delay
		 *
		 * `$.Hover.delay` is the delay (in milliseconds) after which the hover is
		 * activated by default.
		 *
		 * Set this value as a global default. The default is 100ms.
		 *
		 *      // Set the global hover delay to 1 second
		 *      $.Hover.delay = 1000;
		 */
		delay : 100,
		/**
		 * @attribute distance
		 *
		 * `$.Hover.distance` is the maximum distance (in pixels) that the mouse is allowed to
		 * travel within the time of [jQuery.Hover.delay] in order to activate a hover.
		 *
		 * Set this value as a global default. The default is 10px.
		 *
		 *      // Set the global hover distance to 1 pixel
		 *      $.Hover.distance = 1;
		 */
		distance : 10,
		leave : 0
	})

	/**
	 * @Prototype
	 */
	$.extend($.Hover.prototype, {
		/**
		 * `hover.delay(time)` sets the delay (in ms) for this hover.
		 * This method should only be used in [jQuery.event.hover.hoverinit hoverinit]:
		 *
		 *      $('.hoverable').on('hoverinit', function(ev, hover) {
		 *          // Set the delay to 500ms
		 *          hover.delay(500);
		 *      });
		 *
		 * @param {Number} delay the number of milliseconds used to determine a hover
		 * @return {$.Hover} The hover object
		 */
		delay : function (delay) {
			this._delay = delay;
			return this;
		},
		/**
		 * `hover.distance(px) sets the maximum distance (in pixels) the mouse is allowed to travel in order to activate
		 * the hover. This method should only be used in [jQuery.event.hover.hoverinit hoverinit]:
		 *
		 *      $('.hoverable').on('hoverinit', function(ev, hover) {
		 *          // Set the distance to 1px
		 *          hover.distance(1);
		 *      });
		 *
		 * @param {Number} distance the max distance in pixels a mouse can move to be considered a hover
		 * @return {$.Hover} The hover object
		 */
		distance : function (distance) {
			this._distance = distance;
			return this;
		},
		/**
		 * `hover.leave(delay)` sets a delay for how long the hover should stay active after the mouse left.
		 * This method should only be used in [jQuery.event.hover.hoverinit hoverinit]:
		 *
		 *      $('.hoverable').on('hoverinit', function(ev, hover) {
		 *          // Stay active for another second after the mouse left
		 *          hover.leave(1000);
		 *      });
		 *
		 * @param {Number} delay the number of milliseconds the hover should stay active after the mouse leaves
		 * @return {$.Hover} The hover object
		 */
		leave : function (leave) {
			this._leave = leave;
			return this;
		}
	})
	var event = $.event,
		handle = event.handle,
		onmouseenter = function (ev) {
			// now start checking mousemoves to update location
			var delegate = ev.delegateTarget || ev.currentTarget;
			var selector = ev.handleObj.selector;
			// prevents another mouseenter until current has run its course
			if ($.data(delegate, "_hover" + selector)) {
				return;
			}
			$.data(delegate, "_hover" + selector, true)
			var loc = {
					pageX : ev.pageX,
					pageY : ev.pageY
				},
			// The current distance
			dist = 0,
			// Timer that checks for the distance travelled
			timer,
			enteredEl = this,
			// If we are hovered
			hovered = false,
			// The previous event
			lastEv = ev,
			// The $.Hover instance passed to events
			hover = new $.Hover(),
			// timer if hover.leave has been called
			leaveTimer,
			// Callback for triggering hoverleave
			callHoverLeave = function () {
				$.each(event.find(delegate, ["hoverleave"], selector), function () {
					this.call(enteredEl, ev, hover)
				})
				cleanUp();
			},
			mousemove = function (ev) {
				clearTimeout(leaveTimer);
				// Update the distance and location
				dist += Math.pow(ev.pageX - loc.pageX, 2) + Math.pow(ev.pageY - loc.pageY, 2);
				loc = {
					pageX : ev.pageX,
					pageY : ev.pageY
				}
				lastEv = ev
			},
			mouseleave = function (ev) {
				clearTimeout(timer);
				if (hovered) {
					// go right away
					if (hover._leave === 0) {
						callHoverLeave();
					} else {
						clearTimeout(leaveTimer);
						// leave the hover after the time set in hover.leave(time)
						leaveTimer = setTimeout(function () {
							callHoverLeave();
						}, hover._leave)
					}
				} else {
					cleanUp();
				}
			},
			cleanUp = function () {
				// Unbind all events and data
				$(enteredEl).unbind("mouseleave", mouseleave)
				$(enteredEl).unbind("mousemove", mousemove);
				$.removeData(delegate, "_hover" + selector)
			},
			hoverenter = function() {
				$.each(event.find(delegate, ["hoverenter"], selector), function () {
					this.call(enteredEl, lastEv, hover)
				})
				hovered = true;
			};

			// Bind the mousemove event
			$(enteredEl).bind("mousemove", mousemove).bind("mouseleave", mouseleave);
			// call hoverinit for each element with the hover instance
			$.each(event.find(delegate, ["hoverinit"], selector), function () {
				this.call(enteredEl, ev, hover)
			})

			if (hover._delay === 0) {
				hoverenter();
			} else {
				timer = setTimeout(function () {
					// check that we aren't moving around
					if (dist < hover._distance && $(enteredEl).queue().length == 0) {
						hoverenter();
						return;
					} else {
						// Reset distance and timer
						dist = 0;
						timer = setTimeout(arguments.callee, hover._delay)
					}
				}, hover._delay);
			}
		};

	/**
	 * @add jQuery.event.special
	 */
// Attach events
	event.setupHelper([
	/**
	 * @attribute hoverinit
	 * @parent jQuery.event.hover
	 *
	 * `hoverinit` is called when a hover is about to start (on `mouseenter`). Listen for `hoverinit` events to configure
	 * [jQuery.Hover::delay delay] and [jQuery.Hover::distance distance]
	 * for this specific event:
	 *
	 *      $(".option").on("hoverinit", function(ev, hover){
	 *          //set the distance to 10px
	 *          hover.distance(10);
	 *          //set the delay to 200ms
	 *          hover.delay(10);
	 *          // End the hover one second after the mouse leaves
	 *          hover.leave(1000);
	 *      })
	 */
		"hoverinit",
	/**
	 * @attribute hoverenter
	 * @parent jQuery.event.hover
	 *
	 * `hoverenter` events are called when the mouses less than [jQuery.Hover.prototype.distance] pixels in
	 * [jQuery.Hover.prototype.delay delay] milliseconds.
	 *
	 *      $(".option").on("hoverenter", function(ev, hover){
	 *          $(this).addClass("hovering");
	 *      })
	 */
		"hoverenter",
	/**
	 * @attribute hoverleave
	 * @parent jQuery.event.hover
	 *
	 * `hoverleave` is called when the mouse leaves an element that has been hovered.
	 *
	 *      $(".option").on("hoverleave", function(ev, hover){
	 *          $(this).removeClass("hovering");
	 *      })
	 */
		"hoverleave",
	/**
	 * @attribute hovermove
	 * @parent jQuery.event.hover
	 *
	 * `hovermove` is called when a `mousemove` occurs on an element that has been hovered.
	 *
	 *      $(".option").on("hovermove", function(ev, hover){
	 *          // not sure why you would want to listen for this
	 *          // but we provide it just in case
	 *      })
	 */
		"hovermove"], "mouseenter", onmouseenter)


	return $;
})