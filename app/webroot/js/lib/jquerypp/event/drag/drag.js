steal('jquery', 'jquery/lang/vector', 'jquery/event/livehack', 'jquery/event/reverse', function( $ ) {

	if(!$.event.special.move) {
		$.event.reverse('move');
	}

	//modify live
	//steal the live handler ....
	var bind = function( object, method ) {
			var args = Array.prototype.slice.call(arguments, 2);
			return function() {
				var args2 = [this].concat(args, $.makeArray(arguments));
				return method.apply(object, args2);
			};
		},
		event = $.event,
		// function to clear the window selection if there is one
		clearSelection = window.getSelection ? function(){
			window.getSelection().removeAllRanges()
		} : function(){},

		supportTouch = "ontouchend" in document,
		// Use touch events or map it to mouse events
		startEvent = supportTouch ? "touchstart" : "mousedown",
		stopEvent = supportTouch ? "touchend" : "mouseup",
		moveEvent = supportTouch ? "touchmove" : "mousemove",
		// On touchmove events the default (scrolling) event has to be prevented
		preventTouchScroll = function(ev) {
			ev.preventDefault();
		};

	/**
	 * @class jQuery.Drag
	 * @parent jQuery.event.drag
	 * @plugin jquery/event/drag
	 * @download  http://jmvcsite.heroku.com/pluginify?plugins[]=jquery/event/drag/drag.js
	 * @test jquery/event/drag/qunit.html
	 *
	 * The `$.Drag` constructor is never called directly but an instance of `$.Drag` is passed as the second argument
	 * to the `dragdown`, `draginit`, `dragmove`, `dragend`, `dragover` and `dragout` event handlers:
	 *
	 *      $('#dragger').on('draginit', function(el, drag) {
	 *          // drag -> $.Drag
	 *      });
	 */
	$.Drag = function() {};

	/**
	 * @Static
	 */
	$.extend($.Drag, {
		lowerName: "drag",
		current: null,
		distance: 0,
		/**
		 * Called when someone mouses down on a draggable object.
		 * Gathers all callback functions and creates a new Draggable.
		 * @hide
		 */
		mousedown: function( ev, element ) {
			var isLeftButton = ev.button === 0 || ev.button == 1,
				doEvent = isLeftButton || supportTouch;

			if (!doEvent || this.current ) {
				return;
			}

			//create Drag
			var drag = new $.Drag(),
				delegate = ev.delegateTarget || element,
				selector = ev.handleObj.selector,
				self = this;
			this.current = drag;

			drag.setup({
				element: element,
				delegate: ev.delegateTarget || element,
				selector: ev.handleObj.selector,
				moved: false,
				_distance: this.distance,
				callbacks: {
					dragdown: event.find(delegate, ["dragdown"], selector),
					draginit: event.find(delegate, ["draginit"], selector),
					dragover: event.find(delegate, ["dragover"], selector),
					dragmove: event.find(delegate, ["dragmove"], selector),
					dragout: event.find(delegate, ["dragout"], selector),
					dragend: event.find(delegate, ["dragend"], selector),
					dragcleanup: event.find(delegate, ["dragcleanup"], selector)
				},
				destroyed: function() {
					self.current = null;
				}
			}, ev);
		}
	});

	/**
	 * @Prototype
	 */
	$.extend($.Drag.prototype, {
		setup: function( options, ev ) {
			$.extend(this, options);

			this.element = $(this.element);
			this.event = ev;
			this.moved = false;
			this.allowOtherDrags = false;
			var mousemove = bind(this, this.mousemove),
				mouseup = bind(this, this.mouseup);
			this._mousemove = mousemove;
			this._mouseup = mouseup;
			this._distance = options.distance ? options.distance : 0;

			//where the mouse is located
			this.mouseStartPosition = ev.vector();

			$(document).bind(moveEvent, mousemove);
			$(document).bind(stopEvent, mouseup);
			if(supportTouch) {
				// On touch devices we want to disable scrolling
				$(document).bind(moveEvent, preventTouchScroll);
			}

			if (!this.callEvents('down', this.element, ev) ) {
			    this.noSelection(this.delegate);
				//this is for firefox
				clearSelection();
			}
		},
		/**
		 * @attribute element
		 * A reference to the element that is being dragged. For example:
		 *
		 *      $('.draggable').on('draginit', function(ev, drag) {
		 *          drag.element.html('I am the drag element');
		 *      });
		 */

		/**
		 * Unbinds listeners and allows other drags ...
		 * @hide
		 */
		destroy: function() {
			// Unbind the mouse handlers attached for dragging
			$(document).unbind(moveEvent, this._mousemove);
			$(document).unbind(stopEvent, this._mouseup);
			if(supportTouch) {
				// Enable scrolling again for touch devices when the drag is done
				$(document).unbind(moveEvent, preventTouchScroll);
			}

			if (!this.moved ) {
				this.event = this.element = null;
			}

			if(!supportTouch) {
                this.selection(this.delegate);
			}
			this.destroyed();
		},
		mousemove: function( docEl, ev ) {
			if (!this.moved ) {
				var dist = Math.sqrt( Math.pow( ev.pageX - this.event.pageX, 2 ) + Math.pow( ev.pageY - this.event.pageY, 2 ));
				// Don't initialize the drag if it hasn't been moved the minimum distance
				if(dist < this._distance){
					return false;
				}
				// Otherwise call init and indicate that the drag has moved
				this.init(this.element, ev);
				this.moved = true;
			}

			this.element.trigger('move', this);
			var pointer = ev.vector();
			if ( this._start_position && this._start_position.equals(pointer) ) {
				return;
			}
			this.draw(pointer, ev);
		},
		
		mouseup: function( docEl, event ) {
			//if there is a current, we should call its dragstop
			if ( this.moved ) {
				this.end(event);
			}
			this.destroy();
		},

        /**
         * The `drag.noSelection(element)` method turns off text selection during a drag event.
         * This method is called by default unless a event is listening to the 'dragdown' event.
         *
         * ## Example
         *
         *      $('div.drag').bind('dragdown', function(elm,event,drag){
         *          drag.noSelection();
         *      });
         *      
         * @param [elm] an element to prevent selection on.  Defaults to the dragable element.
         */
		noSelection: function(elm) {
            elm = elm || this.delegate
			document.documentElement.onselectstart = function() {
                // Disables selection
				return false;
			};
			document.documentElement.unselectable = "on";
			this.selectionDisabled = (this.selectionDisabled ? this.selectionDisabled.add(elm) : $(elm));
			this.selectionDisabled.css('-moz-user-select', '-moz-none');
		},

        /**
         * @hide
         * `drag.selection()` method turns on text selection that was previously turned off during the drag event.
         * This method is always called.
         * 
         * ## Example
         *
         *     $('div.drag').bind('dragdown', function(elm,event,drag){
         *       drag.selection();
         *     });
         */
		selection: function() {
            if(this.selectionDisabled) {
                document.documentElement.onselectstart = function() {};
                document.documentElement.unselectable = "off";
                this.selectionDisabled.css('-moz-user-select', '');
            }
		},

		init: function( element, event ) {
			element = $(element);
			//the element that has been clicked on
			var startElement = (this.movingElement = (this.element = $(element)));
			//if a mousemove has come after the click
			//if the drag has been cancelled
			this._cancelled = false;
			this.event = event;
			
			/**
			 * @attribute mouseElementPosition
			 * The position of start of the cursor on the element
			 */
			this.mouseElementPosition = this.mouseStartPosition.minus(this.element.offsetv()); //where the mouse is on the Element
			this.callEvents('init', element, event);

			// Check what they have set and respond accordingly if they canceled
			if ( this._cancelled === true ) {
				return;
			}
			// if they set something else as the element
			this.startPosition = startElement != this.movingElement ? this.movingElement.offsetv() : this.currentDelta();

			this.makePositioned(this.movingElement);
			// Adjust the drag elements z-index to a high value
			this.oldZIndex = this.movingElement.css('zIndex');
			this.movingElement.css('zIndex', 1000);
			if (!this._only && this.constructor.responder ) {
				// calls $.Drop.prototype.compile if there is a drop element
				this.constructor.responder.compile(event, this);
			}
		},
		makePositioned: function( that ) {
			var style, pos = that.css('position');

			// Position properly, set top and left to 0px for Opera
			if (!pos || pos == 'static' ) {
				style = {
					position: 'relative'
				};

				if ( window.opera ) {
					style.top = '0px';
					style.left = '0px';
				}
				that.css(style);
			}
		},
		callEvents: function( type, element, event, drop ) {
			var i, cbs = this.callbacks[this.constructor.lowerName + type];
			for ( i = 0; i < cbs.length; i++ ) {
				cbs[i].call(element, event, this, drop);
			}
			return cbs.length;
		},
		/**
		 * Returns the position of the movingElement by taking its top and left.
		 * @hide
		 * @return {$.Vector}
		 */
		currentDelta: function() {
			return new $.Vector(parseInt(this.movingElement.css('left'), 10) || 0, parseInt(this.movingElement.css('top'), 10) || 0);
		},
		//draws the position of the dragmove object
		draw: function( pointer, event ) {
			// only drag if we haven't been cancelled;
			if ( this._cancelled ) {
				return;
			}
			clearSelection();
			/**
			 * @attribute location
			 * `drag.location` is a [jQuery.Vector] specifying where the element should be in the page.  This
			 * takes into account the start position of the cursor on the element.
			 * 
			 * If the drag is going to be moved to an unacceptable location, you can call preventDefault in
			 * dragmove to prevent it from being moved there.
			 * 
			 *     $('.mover').bind("dragmove", function(ev, drag){
			 *       if(drag.location.top() < 100){
			 *         ev.preventDefault()
			 *       }
			 *     });
			 *     
			 * You can also set the location to where it should be on the page.
			 * 
			 */
				// the offset between the mouse pointer and the representative that the user asked for
			this.location = pointer.minus(this.mouseElementPosition);

			// call move events
			this.move(event);
			if ( this._cancelled ) {
				return;
			}
			if (!event.isDefaultPrevented() ) {
				this.position(this.location);
			}

			// fill in
			if (!this._only && this.constructor.responder ) {
				this.constructor.responder.show(pointer, this, event);
			}
		},
		/**
		 * `drag.position( newOffsetVector )` sets the position of the movingElement.  This is overwritten by
		 * the [$.Drag::scrolls], [$.Drag::limit] and [$.Drag::step] plugins 
		 * to make sure the moving element scrolls some element
		 * or stays within some boundary.  This function is exposed and documented so you could do the same.
		 * 
		 * The following approximates how step does it:
		 * 
		 *     var oldPosition = $.Drag.prototype.position;
		 *     $.Drag.prototype.position = function( offsetPositionv ) {
		 *       if(this._step){
		 *         // change offsetPositionv to be on the step value
		 *       }
		 *       
		 *       oldPosition.call(this, offsetPosition)
		 *     }
		 * 
		 * @param {jQuery.Vector} newOffsetv the new [$.Drag::location] of the element.
		 */
		position: function( newOffsetv ) { //should draw it on the page
			var style, dragged_element_css_offset = this.currentDelta(),
				//  the drag element's current left + top css attributes
				// the vector between the movingElement's page and css positions
				// this can be thought of as the original offset
				dragged_element_position_vector =   this.movingElement.offsetv().minus(dragged_element_css_offset);
			this.required_css_position = newOffsetv.minus(dragged_element_position_vector);

			this.offsetv = newOffsetv;
			style = this.movingElement[0].style;
			if (!this._cancelled && !this._horizontal ) {
				style.top = this.required_css_position.top() + "px";
			}
			if (!this._cancelled && !this._vertical ) {
				style.left = this.required_css_position.left() + "px";
			}
		},
		move: function( event ) {
			this.callEvents('move', this.element, event);
		},
		over: function( event, drop ) {
			this.callEvents('over', this.element, event, drop);
		},
		out: function( event, drop ) {
			this.callEvents('out', this.element, event, drop);
		},
		/**
		 * Called on drag up
		 * @hide
		 * @param {Event} event a mouseup event signalling drag/drop has completed
		 */
		end: function( event ) {
			// If canceled do nothing
			if ( this._cancelled ) {
				return;
			}
			// notify the responder - usually a $.Drop instance
			if (!this._only && this.constructor.responder ) {
				this.constructor.responder.end(event, this);
			}

			this.callEvents('end', this.element, event);

			if ( this._revert ) {
				var self = this;
				// animate moving back to original position
				this.movingElement.animate({
					top: this.startPosition.top() + "px",
					left: this.startPosition.left() + "px"
				}, function() {
					self.cleanup.apply(self, arguments);
				});
			}
			else {
				this.cleanup(event);
			}
			this.event = null;
		},
		/**
		 * Cleans up drag element after drag drop.
		 * @hide
		 */
		cleanup: function(event) {
			this.movingElement.css({
				zIndex: this.oldZIndex
			});
			if ( this.movingElement[0] !== this.element[0] && 
				!this.movingElement.has(this.element[0]).length && 
				!this.element.has(this.movingElement[0]).length ) {
				this.movingElement.css({
					display: 'none'
				});
			}
			if ( this._removeMovingElement ) {
				// Remove the element when using drag.ghost()
				this.movingElement.remove();
			}

			if(event) {
				this.callEvents('cleanup', this.element, event);
			}

			this.movingElement = this.element = this.event = null;
		},
		/**
		 * `drag.cancel()` stops a drag motion from from running.  This also stops any other events from firing, meaning
		 * that "dragend" will not be called.
		 * 
		 *     $("#todos").on(".handle", "draginit", function( ev, drag ) {
		 *       if(drag.movingElement.hasClass("evil")){
		 *         drag.cancel();	
		 *       }
		 *     })
		 * 
		 */
		cancel: function() {
			this._cancelled = true;
			if (!this._only && this.constructor.responder ) {
				// clear the drops
				this.constructor.responder.clear(this.event.vector(), this, this.event);
			}
			this.destroy();

		},
		/**
		 * `drag.ghost( [parent] )` clones the element and uses it as the 
		 * moving element, leaving the original dragged element in place.  The `parent` option can
		 * be used to specify where the ghost element should be temporarily added into the 
		 * DOM.  This method should be called in "draginit".
		 * 
		 *     $("#todos").on(".handle", "draginit", function( ev, drag ) {
		 *       drag.ghost();
		 *     })
		 * 
		 * @param {HTMLElement} [parent] the parent element of the newly created ghost element. If not provided the 
		 * ghost element is added after the moving element.
		 * @return {jQuery.fn} the ghost element to do whatever you want with it.
		 */
		ghost: function( parent ) {
			// create a ghost by cloning the source element and attach the clone to the dom after the source element
			var ghost = this.movingElement.clone().css('position', 'absolute');
			if( parent ) {
				$(parent).append(ghost);
			} else {
				$(this.movingElement).after(ghost)
			}
			ghost.width(this.movingElement.width()).height(this.movingElement.height());
			// put the ghost in the right location ...
			ghost.offset(this.movingElement.offset())
			
			// store the original element and make the ghost the dragged element
			this.movingElement = ghost;
			this.noSelection(ghost)
			this._removeMovingElement = true;
			return ghost;
		},
		/**
		 * `drag.representative( element, [offsetX], [offsetY])` tells the drag motion to use
		 * a different element than the one that began the drag motion. 
		 * 
		 * For example, instead of
		 * dragging an drag-icon of a todo element, you want to move some other representation of
		 * the todo element (or elements).  To do this you might:
		 * 
		 *     $("#todos").on(".handle", "draginit", function( ev, drag ) {
		 *       // create what we'll drag
		 *       var rep = $('<div/>').text("todos")
		 *         .appendTo(document.body)
		 *       // indicate we want our mouse on the top-right of it
		 *       drag.representative(rep, rep.width(), 0);
		 *     })
		 * 
		 * @param {HTMLElement} element the element you want to actually drag.  This should be 
		 * already in the DOM.
		 * @param {Number} offsetX the x position where you want your mouse on the representative element (defaults to 0)
		 * @param {Number} offsetY the y position where you want your mouse on the representative element (defaults to 0)
		 * @return {drag} returns the drag object for chaining.
		 */
		representative: function( element, offsetX, offsetY ) {
			this._offsetX = offsetX || 0;
			this._offsetY = offsetY || 0;

			var p = this.mouseStartPosition;
			// Just set the representative as the drag element
			this.movingElement = $(element);
			this.movingElement.css({
				top: (p.y() - this._offsetY) + "px",
				left: (p.x() - this._offsetX) + "px",
				display: 'block',
				position: 'absolute'
			}).show();
			this.noSelection(this.movingElement)
			this.mouseElementPosition = new $.Vector(this._offsetX, this._offsetY);
			return this;
		},
		/**
		 * `drag.revert([val])` makes the [$.Drag::representative representative] element revert back to it
		 * original position after the drag motion has completed.  The revert is done with an animation.
		 * 
		 *     $("#todos").on(".handle","dragend",function( ev, drag ) {
		 *       drag.revert();
		 *     })
		 * 
		 * @param {Boolean} [val] optional, set to false if you don't want to revert.
		 * @return {drag} the drag object for chaining
		 */
		revert: function( val ) {
			this._revert = val === undefined ? true : val;
			return this;
		},
		/**
		 * `drag.vertical()` isolates the drag to vertical movement.  For example:
		 * 
		 *     $("#images").on(".thumbnail","draginit", function(ev, drag){
		 *       drag.vertical();
		 *     });
		 * 
		 * Call `vertical()` in "draginit" or "dragdown".
		 * 
		 * @return {drag} the drag object for chaining.
		 */
		vertical: function() {
			this._vertical = true;
			return this;
		},
		/**
		 * `drag.horizontal()` isolates the drag to horizontal movement.  For example:
		 * 
		 *     $("#images").on(".thumbnail","draginit", function(ev, drag){
		 *       drag.horizontal();
		 *     });
		 * 
		 * Call `horizontal()` in "draginit" or "dragdown".
		 * 
		 * @return {drag} the drag object for chaining.
		 */
		horizontal: function() {
			this._horizontal = true;
			return this;
		},
		/**
		 * `drag.only([only])` indicates if you __only__ want a drag motion and the drag should
		 * not notify drops.  The default value is `false`.  Call it with no arguments or pass it true
		 * to prevent drop events.
		 * 
		 *     $("#images").on(".thumbnail","dragdown", function(ev, drag){
		 * 	     drag.only();
		 *     });
		 * 
		 * @param {Boolean} [only] true if you want to prevent drops, false if otherwise.
		 * @return {Boolean} the current value of only.
		 */
		only: function( only ) {
			return (this._only = (only === undefined ? true : only));
		},
		
		/**
		 * `distance([val])` sets or reads the distance the mouse must move before a drag motion is started.  This should be set in
		 * "dragdown" and delays "draginit" being called until the distance is covered.  The distance
		 * is measured in pixels.  The default distance is 0 pixels meaning the drag motion starts on the first
		 * mousemove after a mousedown.
		 * 
		 * Set this to make drag motion a little "stickier" to start.
		 * 
		 *     $("#images").on(".thumbnail","dragdown", function(ev, drag){
		 *       drag.distance(10);
		 *     });
		 * 
		 * @param {Number} [val] The number of pixels the mouse must move before "draginit" is called.
		 * @return {drag|Number} returns the drag instance for chaining if the drag value is being set or the
		 * distance value if the distance is being read.
		 */
		distance: function(val){
			if(val !== undefined){
				this._distance = val;
				return this;
			}else{
				return this._distance
			}
		}
	});
	/**
	 * @add jQuery.event.special
	 */
	event.setupHelper([
	/**
	 * @attribute dragdown
	 * @parent jQuery.event.drag
	 * 
	 * `dragdown` is called when a drag movement has started on a mousedown.
	 * The event handler gets an instance of [jQuery.Drag] passed as the second
	 * parameter.  Listening to `dragdown` allows you to customize 
	 * the behavior of a drag motion, especially when `draginit` should be called.
	 * 
	 *     $(".handles").delegate("dragdown", function(ev, drag){
	 *       // call draginit only when the mouse has moved 20 px
	 *       drag.distance(20);
	 *     })
	 * 
	 * Typically, when a drag motion is started, `event.preventDefault` is automatically
	 * called, preventing text selection.  However, if you listen to 
	 * `dragdown`, this default behavior is not called. You are responsible for calling it
	 * if you want it (you probably do).
	 *
	 * ### Why might you not want to call `preventDefault`?
	 *
	 * You might want it if you want to allow text selection on element
	 * within the drag element.  Typically these are input elements.
	 *
	 *     $(".handles").delegate("dragdown", function(ev, drag){
	 *       if(ev.target.nodeName === "input"){
	 *         drag.cancel();
	 *       } else {
	 *         ev.preventDefault();
	 *       }
	 *     })
	 */
	'dragdown',
	/**
	 * @attribute draginit
	 * @parent jQuery.event.drag
	 *
	 * `draginit` is triggered when the drag motion starts. Use it to customize the drag behavior
	 * using the [jQuery.Drag] instance passed as the second parameter:
	 *
	 *     $(".draggable").on('draginit', function(ev, drag) {
	 *       // Only allow vertical drags
	 *       drag.vertical();
	 *       // Create a draggable copy of the element
	 *       drag.ghost();
	 *     });
	 */
	'draginit',
	/**
	 * @attribute dragover
	 * @parent jQuery.event.drag
	 *
	 * `dragover` is triggered when a drag is over a [jQuery.event.drop drop element].
	 * The event handler gets an instance of [jQuery.Drag] passed as the second
	 * parameter and an instance of [jQuery.Drop] passed as the third argument:
	 *
	 *      $('.draggable').on('dragover', function(ev, drag, drop) {
	 *          // Add the drop-here class indicating that the drag
	 *          // can be dropped here
	 *          drag.element.addClass('drop-here');
	 *      });
	 */
	'dragover',
	/**
	 * @attribute dragmove
	 * @parent jQuery.event.drag
	 *
	 * `dragmove` is triggered when the drag element moves (similar to a mousemove).
	 * The event handler gets an instance of [jQuery.Drag] passed as the second
	 * parameter.
	 * Use [jQuery.Drag::location] to determine the current position
	 * as a [jQuery.Vector vector].
	 *
	 * For example, `dragmove` can be used to create a draggable element to resize
	 * a container:
	 *
	 *      $('.resizer').on('dragmove', function(ev, drag) {
	 *          $('#container').width(drag.location.x())
	 *              .height(drag.location.y());
	 *      });
	 */
	'dragmove',
	/**
	 * @attribute dragout
	 * @parent jQuery.event.drag
	 *
	 * `dragout` is called when the drag leaves a drop point.
	 * The event handler gets an instance of [jQuery.Drag] passed as the second
	 * parameter.
	 *
	 *      $('.draggable').on('dragout', function(ev, drag) {
	 *      	 // Remove the drop-here class
	 *      	 // (e.g. crossing the drag element out indicating that it
	 *      	 // can't be dropped here
	 *          drag.element.removeClass('drop-here');
	 *      });
	 */
	'dragout',
	/**
	 * @attribute dragend
	 * @parent jQuery.event.drag
	 *
	 * `dragend` is called when the drag operation is completed.
	 * The event handler gets an instance of [jQuery.Drag] passed as the second
	 * parameter.
	 *
	 *     $('.draggable').on('dragend', function(ev, drag)
	 *       // Calculation on whether revert should be invoked, alterations based on position of the end event
	 *     });
	 */
	'dragend',
        /**
	 * @attribute dragcleanup
	 * @parent jQuery.event.drag
	 *
	 * `dragcleanup` is called after dragend and revert (if applied)
         * The event handler gets an instance of [jQuery.Drag] passed as the second
	 * parameter.
	 *
	 *     $('.draggable').on('dragcleanup', function(ev, drag)
	 *         // cleanup 
	 *     });
	 */
	'dragcleanup'], startEvent, function( e ) {
		$.Drag.mousedown.call($.Drag, e, this);
	});

	return $;
});