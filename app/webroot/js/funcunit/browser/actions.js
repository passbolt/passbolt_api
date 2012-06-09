(function($){
	/**
	 * @add FuncUnit
	 */
	var clicks = [
	/**
	 * @Prototype
	 */
	// methods
	/**
	 * @function click
	 * Clicks an element.  This uses [Syn.prototype.click] to issue a:
	 * <ul>
	 * 	<li><code>mousedown</code></li>
	 *  <li><code>focus</code> - if the element is focusable</li>
	 *  <li><code>mouseup</code></li>
	 *  <li><code>click</code></li>
	 * </ul>
	 * If no clientX/Y or pageX/Y is provided as options, the click happens at the 
	 * center of the element.
	 * <p>For a right click or double click use [FuncUnit.prototype.rightClick] or
	 *   [FuncUnit.prototype.dblclick].</p>
	 * <h3>Example</h3>
	 * @codestart
	 * //clicks the bar element
	 * S("#bar").click()
	 * @codeend
	 * @param {Object} [options] options to pass to the click event.  Typically, this is clientX/Y or pageX/Y:
	 * @codestart
	 * $('#foo').click({pageX: 200, pageY: 100});
	 * @codeend
	 * You can pass it any of the serializable parameters you'd send to 
	 * [http://developer.mozilla.org/en/DOM/event.initMouseEvent initMouseEvent], but command keys are 
	 * controlled by [FuncUnit.prototype.type].
	 * @param {Function} [success] a callback that runs after the click, but before the next action.
	 * @return {funcUnit} returns the funcunit object for chaining.
	 */
	'click',
	/**
	 * @function dblclick
	 * Double clicks an element by [FuncUnit.prototype.click clicking] it twice and triggering a dblclick event.
	 * @param {Object} options options to add to the mouse events.  This works
	 * the same as [FuncUnit.prototype.click]'s options.
	 * @param {Function} [success] a callback that runs after the double click, but before the next action.
	 * @return {funcUnit} returns the funcunit object for chaining.
	 */
	'dblclick',
	/**
	 * @function rightClick
	 * Right clicks an element.  This typically results in a contextmenu event for browsers that
	 * support it.
	 * @param {Object} options options to add to the mouse events.  This works
	 * the same as [FuncUnit.prototype.click]'s options.
	 * @param {Function} [success] a callback that runs after the click, but before the next action.
	 * @return {funcUnit} returns the funcunit object for chaining.
	 */
	'rightClick'],
		makeClick = function(name){
			FuncUnit.prototype[name] = function(options, success){
				this._addExists();
				if(typeof options == 'function'){
					success = options;
					options = {};
				}
				var selector = this.selector, 
					context = this.context;
				FuncUnit.add({
					method: function(success, error){
						options = options || {}
						steal.dev.log("Clicking " + selector)
						this.bind.triggerSyn("_" + name, options, success);
					},
					success: success,
					error: "Could not " + name + " '" + this.selector+"'",
					bind: this,
					type: "action"
				});
				return this;
			}
		}
	
	for(var i=0; i < clicks.length; i++){
		makeClick(clicks[i])
	}
	
	$.extend(FuncUnit.prototype, {
		// perform check even if last queued item is a wait beacuse certain waits don't guarantee the element is visible (like text)
		_addExists: function(){
			this.exists(false);
		},
		/**
		 * Types text into an element.  This makes use of [Syn.type] and works in 
		 * a very similar way.
		 * <h3>Quick Examples</h3>
		 * @codestart
		 * //types hello world
		 * S('#bar').type('hello world')
		 * 
		 * //submits a form by typing \r
		 * S("input[name=age]").type("27\r")
		 * 
		 * //types FuncUnit, then deletes the Unit
		 * S('#foo').type("FuncUnit\b\b\b\b")
		 * 
		 * //types JavaScriptMVC, then removes the MVC
		 * S('#zar').type("JavaScriptMVC[left][left][left]"+
		 *                      "[delete][delete][delete]")
		 *          
		 * //types JavaScriptMVC, then selects the MVC and
		 * //deletes it
		 * S('#zar').type("JavaScriptMVC[shift]"+
		 *                "[left][left][left]"+
		 *                "[shift-up][delete]")
		 * @codeend
		 *
		 * <h2>Characters</h2>
		 * 
		 * For a list of the characters you can type, check [Syn.keycodes].
		 * 
		 * @param {String} text the text you want to type
		 * @param {Function} [success] a callback that is run after typing, but before the next action.
		 * @return {FuncUnit} returns the funcUnit object for chaining.
		 */
		type: function( text, success ) {
			this._addExists();
			var selector = this.selector, 
				context = this.context;
			FuncUnit.add({
				method : function(success, error){
					steal.dev.log("Typing "+text+" on "+selector)
					this.bind.triggerSyn("_type", text, success);
				},
				success : success,
				error : "Could not type " + text + " into " + this.selector,
				bind : this,
				type: "action"
			});
			return this;
		},
		trigger: function(evName, success){
			this._addExists();
			FuncUnit.add({
				method : function(success, error){
					steal.dev.log("Triggering "+evName+" on "+this.bind.selector)
					// need to use the page's jquery to trigger events
					FuncUnit.win.jQuery(this.bind.selector).trigger(evName)
					success()
				},
				success : success,
				error : "Could not trigger " + evName,
				bind : this,
				type: "action"
			});
			return this;
		},
		/**
		 * Drags an element into another element or coordinates.  
		 * This takes the same paramameters as [Syn.prototype.move move].
		 * @param {String|Object} options A selector or coordinates describing the motion of the drag.
		 * <h5>Options as a Selector</h5>
		 * Passing a string selector to drag the mouse.  The drag runs to the center of the element
		 * matched by the selector.  The following drags from the center of #foo to the center of #bar.
		 * @codestart
		 * S('#foo').drag('#bar') 
		 * @codeend
		 * <h5>Options as Coordinates</h5>
		 * You can pass in coordinates as clientX and clientY:
		 * @codestart
		 * S('#foo').drag('100x200') 
		 * @codeend
		 * Or as pageX and pageY
		 * @codestart
		 * S('#foo').drag('100X200') 
		 * @codeend
		 * Or relative to the start position
		 * S('#foo').drag('+10 +20')
		 * <h5>Options as an Object</h5>
		 * You can configure the duration, start, and end point of a drag by passing in a json object.
		 * @codestart
		 * //drags from 0x0 to 100x100 in 2 seconds
		 * S('#foo').drag({
		 *   from: "0x0",
		 *   to: "100x100",
		 *   duration: 2000
		 * }) 
		 * @codeend
		 * @param {Function} [success] a callback that runs after the drag, but before the next action.
		 * @return {funcUnit} returns the funcunit object for chaining.
		 */
		drag: function( options, success ) {
			this._addExists();
			if(typeof options == 'string'){
				options = {to: options}
			}
			options.from = this.selector;
	
			var selector = this.selector, 
				context = this.context;
			FuncUnit.add({
				method: function(success, error){
					steal.dev.log("dragging " + selector)
					this.bind.triggerSyn("_drag", options, success);
				},
				success: success,
				error: "Could not drag " + this.selector,
				bind: this,
				type: "action"
			})
			return this;
		},
		/**
		 * Moves an element into another element or coordinates.  This will trigger mouseover
		 * mouseouts accordingly.
		 * This takes the same paramameters as [Syn.prototype.move move].
		 * @param {String|Object} options A selector or coordinates describing the motion of the move.
		 * <h5>Options as a Selector</h5>
		 * Passing a string selector to move the mouse.  The move runs to the center of the element
		 * matched by the selector.  The following moves from the center of #foo to the center of #bar.
		 * @codestart
		 * S('#foo').move('#bar') 
		 * @codeend
		 * <h5>Options as Coordinates</h5>
		 * You can pass in coordinates as clientX and clientY:
		 * @codestart
		 * S('#foo').move('100x200') 
		 * @codeend
		 * Or as pageX and pageY
		 * @codestart
		 * S('#foo').move('100X200') 
		 * @codeend
		 * Or relative to the start position
		 * S('#foo').move('+10 +20')
		 * <h5>Options as an Object</h5>
		 * You can configure the duration, start, and end point of a move by passing in a json object.
		 * @codestart
		 * //drags from 0x0 to 100x100 in 2 seconds
		 * S('#foo').move({
		 *   from: "0x0",
		 *   to: "100x100",
		 *   duration: 2000
		 * }) 
		 * @codeend
		 * @param {Function} [success] a callback that runs after the drag, but before the next action.
		 * @return {funcUnit} returns the funcunit object for chaining.
		 */
		move: function( options, success ) {
			this._addExists();
			if(typeof options == 'string'){
				options = {to: options}
			}
			options.from = this.selector;
	
			var selector = this.selector, 
				context = this.context;
			FuncUnit.add({
				method: function(success, error){
					steal.dev.log("moving " + selector)
					this.bind.triggerSyn("_move", options, success);
				},
				success: success,
				error: "Could not move " + this.selector,
				bind: this,
				type: "action"
			});
			return this;
		},
		/**
		 * Scrolls an element in a particular direction by setting the scrollTop or srollLeft.
		 * @param {String} direction "left" or "top"
		 * @param {Number} amount number of pixels to scroll
		 * @param {Function} success
		 */
		scroll: function( direction, amount, success ) {
			this._addExists();
			var selector = this.selector, 
				context = this.context,
				direction = /left|right|x/i.test(direction)? "Left" : "Right";
			FuncUnit.add({
				method: function(success, error){
					steal.dev.log("setting " + selector + " scroll" + direction + " " + amount + " pixels")
					this.bind.each(function(i, el){
						this["scroll" + direction] = amount;
					})
					success();
				},
				success: success,
				error: "Could not scroll " + this.selector,
				bind: this,
				type: "action"
			});
			return this;
		}
	})
})(window.jQuery || window.FuncUnit.jQuery)
