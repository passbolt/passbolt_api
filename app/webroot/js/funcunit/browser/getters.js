(function($){
	
	/**
	 * @add FuncUnit
	 */
	//list of jQuery functions we want, number is argument index
	//for wait instead of getting value
	FuncUnit.funcs = {
	/**
	 * @Prototype
	 */
	// methods
	/**
	 * @function size
	 * Gets the number of elements matched by the selector or
	 * waits until the the selector is size.  You can also 
	 * provide a function that continues to the next action when
	 * it returns true.
	 * @codestart
	 * S(".recipe").size() //gets the number of recipes
	 * 
	 * S(".recipe").size(2) //waits until there are 2 recipes
	 * 
	 * //waits until size is count
	 * S(".recipe").size(function(size){
	 *   return size == count;
	 * })
	 * @codeend
	 * @param {Number|Function} [size] number or a checking function.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {Number} if the size parameter is not provided, size returns the number
	 * of elements matched.
	 */
	'size' : 0,
	/**
	 * @function attr
	 * Gets the value of an attribute from an element or waits until the attribute
	 * equals the attr param.
	 * @codestart
	 *  //gets the abc attribute
	 * S("#something").attr("abc")
	 * 
	 * //waits until the abc attribute == some
	 * S("#something").attr("abc","some") 
	 * @codeend
	 * @param {String} data The attribute to get, or wait for.
	 * @param {String|Function} [value] If provided uses this as a check before continuing to the next action
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {Object} if the attr parameter is not provided, returns
	 * the attribute.
	 */
	'attr' : 1, 
	/**
	 * @function hasClass
	 * @codestart
	 * //returns if the element has the class in its className
	 * S("#something").hasClass("selected");
	 * 
	 * //waits until #something has selected in its className
	 * S("#something").hasClass("selected",true);
	 * 
	 * //waits until #something does not have selected in its className
	 * S("#something").hasClass("selected",false);
	 * @codeend
	 * @param {String} className The part of the className to search for.
	 * @param {Boolean|Function} [value] If provided uses this as a check before continuing to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {Boolean|funcUnit} if the value parameter is not provided, returns
	 * if the className is found in the element's className.  If a value paramters is provided, returns funcUnit for chaining.
	 */
	'hasClass' : 1, //makes wait
	/**
	 * @function html
	 * Gets the [http://api.jquery.com/html/ html] from an element or waits until the html is a certain value.
	 * @codestart
	 * //checks foo's html has "JupiterJS"
	 * ok( /JupiterJS/.test( S('#foo').html() ) )
	 * 
	 * //waits until bar's html has JupiterJS
	 * S('#foo').html(/JupiterJS/)
	 * 
	 * //waits until bar's html is JupiterJS
	 * S('#foo').html("JupiterJS")
	 * @codeend
	 * 
	 * @param {String|Function} [html] If provided uses this as a check before continuing to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if the html parameter is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the html of the selector.
	 */
	'html' : 0, 
	/**
	 * @function text
	 * Gets the [http://api.jquery.com/text/ text] from an element or waits until the text is a certain value.
	 * @codestart
	 * //checks foo's text has "JupiterJS"
	 * ok( /JupiterJS/.test( S('#foo').text() ) )
	 * 
	 * //waits until bar's text has JupiterJS
	 * S('#foo').text(/JupiterJS/)
	 * 
	 * //waits until bar's text is JupiterJS
	 * S('#foo').text("JupiterJS")
	 * @codeend
	 * 
	 * @param {String|Function} [text] If provided uses this as a check before continuing to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if the text parameter is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the html of the selector.
	 */
	'text' : 0, 
	/**
	 * @function val
	 * Gets the [http://api.jquery.com/val/ val] from an element or waits until the val is a certain value.
	 * @codestart
	 * //checks foo's val has "JupiterJS"
	 * ok( /JupiterJS/.test( S('input#foo').val() ) )
	 * 
	 * //waits until bar's val has JupiterJS
	 * S('input#foo').val(/JupiterJS/)
	 * 
	 * //waits until bar's val is JupiterJS
	 * S('input#foo').val("JupiterJS")
	 * @codeend
	 * 
	 * @param {String|Function} [val] If provided uses this as a check before continuing to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if the val parameter is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the html of the selector.
	 */
	'val' : 0, 
	/**
	 * @function css
	 * Gets a [http://api.jquery.com/css/ css] property from an element or waits until the property is 
	 * a specified value.
	 * @codestart
	 * // gets the color
	 * S("#foo").css("color")
	 * 
	 * // waits until the color is red
	 * S("#foo").css("color","red") 
	 * @codeend
	 * 
	 * @param {String} prop A css property to get or wait until it is a specified value.
	 * @param {String|Function} [val] If provided uses this as a check before continuing to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if the val parameter is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the css of the selector.
	 */
	'css': 1, 
	/**
	 * @function offset
	 * Gets an element's [http://api.jquery.com/offset/ offset] or waits until 
	 * the offset is a specified value.
	 * @codestart
	 * // gets the offset
	 * S("#foo").offset();
	 * 
	 * // waits until the offset is 100, 200
	 * S("#foo").offset({top: 100, left: 200}) 
	 * @codeend
	 * 
	 * @param {Object|Function} [offset] If provided uses this as a check before continuing to the next action.  Or you can 
	 * provide a function that returns true to continue to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if the offset parameter is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the css of the selector.
	 */
	'offset' : 0,
	/**
	 * @function position
	 * Gets an element's [http://api.jquery.com/position/ position] or waits until 
	 * the position is a specified value.
	 * @codestart
	 * // gets the position
	 * S("#foo").position();
	 * 
	 * // waits until the position is 100, 200
	 * S("#foo").position({top: 100, left: 200}) 
	 * @codeend
	 * 
	 * @param {Object|Function} [position] If provided uses this as a check before continuing to the next action.  Or you can 
	 * provide a function that returns true to continue to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if the position parameter is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the offset of the selector.
	 */
	'position' : 0,
	/**
	 * @function scrollTop
	 * Gets an element's [http://api.jquery.com/scrollTop/ scrollTop] or waits until 
	 * it equals a specified value.
	 * @codestart
	 * // gets the scrollTop
	 * S("#foo").scrollTop();
	 * 
	 * // waits until the scrollTop is 100
	 * S("#foo").scrollTop(100) 
	 * @codeend
	 * 
	 * @param {Number|Function} [scrollTop] If provided uses this as a check before continuing to the next action.  Or you can 
	 * provide a function that returns true to continue to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if scrollTop is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the scrollTop of the selector.
	 */ 
	'scrollTop' : 0, 
	/**
	 * @function scrollLeft
	 * Gets an element's [http://api.jquery.com/scrollLeft/ scrollLeft] or waits until 
	 * it equals a specified value.
	 * @codestart
	 * // gets the scrollLeft
	 * S("#foo").scrollLeft();
	 * 
	 * // waits until the scrollLeft is 100
	 * S("#foo").scrollLeft(100) 
	 * @codeend
	 * 
	 * @param {Number|Function} [scrollLeft] If provided uses this as a check before continuing to the next action.  Or you can 
	 * provide a function that returns true to continue to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if scrollLeft is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the scrollLeft of the selector.
	 */ 
	'scrollLeft' : 0, 
	/**
	 * @function height
	 * Gets an element's [http://api.jquery.com/height/ height] or waits until 
	 * it equals a specified value.
	 * @codestart
	 * // gets the height
	 * S("#foo").height();
	 * 
	 * // waits until the height is 100
	 * S("#foo").height(100) 
	 * @codeend
	 * 
	 * @param {Number|Function} [height] If provided uses this as a check before continuing to the next action.  Or you can 
	 * provide a function that returns true to continue to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if height is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the height of the selector.
	 */
	'height' : 0, 
	/**
	 * @function width
	 * Gets an element's [http://api.jquery.com/width/ width] or waits until 
	 * it equals a specified value.
	 * @codestart
	 * // gets the width
	 * S("#foo").width();
	 * 
	 * // waits until the width is 100
	 * S("#foo").width(100) 
	 * @codeend
	 * 
	 * @param {Number|Function} [width] If provided uses this as a check before continuing to the next action.  Or you can 
	 * provide a function that returns true to continue to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if width is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the width of the selector.
	 */
	'width' : 0, 
	/**
	 * @function innerHeight
	 * Gets an element's [http://api.jquery.com/innerHeight/ innerHeight] or waits until 
	 * it equals a specified value.
	 * @codestart
	 * // gets the innerHeight
	 * S("#foo").innerHeight();
	 * 
	 * // waits until the innerHeight is 100
	 * S("#foo").innerHeight(100) 
	 * @codeend
	 * 
	 * @param {Number|Function} [innerHeight] If provided uses this as a check before continuing to the next action.  Or you can 
	 * provide a function that returns true to continue to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if innerHeight is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the innerHeight of the selector.
	 */
	'innerHeight' : 0, 
	/**
	 * @function innerWidth
	 * Gets an element's [http://api.jquery.com/innerWidth/ innerWidth] or waits until 
	 * it equals a specified value.
	 * @codestart
	 * // gets the innerWidth
	 * S("#foo").innerWidth();
	 * 
	 * // waits until the innerWidth is 100
	 * S("#foo").innerWidth(100) 
	 * @codeend
	 * 
	 * @param {Number|Function} [innerWidth] If provided uses this as a check before continuing to the next action.  Or you can 
	 * provide a function that returns true to continue to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if innerWidth is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the innerWidth of the selector.
	 */
	'innerWidth' : 0, 
	/**
	 * @function outerHeight
	 * Gets an element's [http://api.jquery.com/outerHeight/ outerHeight] or waits until 
	 * it equals a specified value.
	 * @codestart
	 * // gets the outerHeight
	 * S("#foo").outerHeight();
	 * 
	 * // waits until the outerHeight is 100
	 * S("#foo").outerHeight(100) 
	 * @codeend
	 * 
	 * @param {Number|Function} [outerHeight] If provided uses this as a check before continuing to the next action.  Or you can 
	 * provide a function that returns true to continue to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if outerHeight is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the outerHeight of the selector.
	 */
	'outerHeight' : 0, 
	/**
	 * @function outerWidth
	 * Gets an element's [http://api.jquery.com/outerWidth/ outerWidth] or waits until 
	 * it equals a specified value.
	 * @codestart
	 * // gets the outerWidth
	 * S("#foo").outerWidth();
	 * 
	 * // waits until the outerWidth is 100
	 * S("#foo").outerWidth(100) 
	 * @codeend
	 * 
	 * @param {Number|Function} [outerWidth] If provided uses this as a check before continuing to the next action.  Or you can 
	 * provide a function that returns true to continue to the next action.
	 * @param {Number} [timeout] overrides FuncUnit.timeout.  If provided, the wait will fail if not completed before this timeout.
	 * @param {Function} [success] a callback that will run after this action completes.
	 * @param {String} [message] if provided, an assertion will be passed when this wait condition completes successfully
	 * @return {String|funcUnit} if outerWidth is provided, 
	 * returns the funcUnit selector for chaining, otherwise returns the outerWidth of the selector.
	 */
	'outerWidth' : 0}
	
	
	//makes a jQuery like command.
	FuncUnit.makeFunc = function(fname, argIndex){
		var orig = FuncUnit.fn[fname];
		//makes a read / wait function
		FuncUnit.prototype[fname] = function(){
			//assume last arg is success
			var args = FuncUnit.makeArray(arguments), 
				isWait = args.length > argIndex,
				success;
			
			args.unshift(this.selector,this.context,fname)
			if(isWait){
				//get the args greater and equal to argIndex
				var tester = args[argIndex+3],
					timeout = args[argIndex+4],
					success = args[argIndex+5],
					message = args[argIndex+6],
					testVal = tester,
					errorMessage = "waiting for "+fname +" on " + this.selector,
					frame = this.frame,
					logMessage = "Checking "+fname+" on '"+this.selector+"'";
				
				// can pass in an object or list of arguments
				if(typeof tester == 'object'){
					timeout = tester.timeout;
					success = tester.success;
					message = tester.message;
					if(tester.errorMessage){
						errorMessage = tester.errorMessage
					}
					if(typeof tester.logMessage !== "undefined"){
						logMessage = tester.logMessage
					}
					tester = tester.condition;
				}
				if(typeof timeout == 'function'){
					success = timeout;
					message = success;
					timeout = undefined;
				}
				if(typeof timeout == 'string'){
					message = timeout;
					timeout = undefined;
					success = undefined;
				}
				if(typeof message !== 'string'){
					message = undefined;
				}
				args.splice(argIndex+3, args.length- argIndex - 3);
				
				if(typeof tester != 'function'){
					errorMessage += " !== "+testVal
					tester = function(val){
						
						return QUnit.equiv(val, testVal) || 
							(testVal instanceof RegExp && testVal.test(val) );
					}
				}
				if(message){
					errorMessage = message;
				}
				FuncUnit.repeat({
					method : function(print){
						// keep getting new collection because the page might be updating, we need to keep re-checking
						if(this.bind.prevObject && this.bind.prevTraverser){
							var prev = this.bind;
							this.bind = this.bind.prevObject[this.bind.prevTraverser](this.bind.prevTraverserSelector)
							this.bind.prevTraverser = prev.prevTraverser;
							this.bind.prevTraverserSelector = prev.prevTraverserSelector;
						} else {
							// pass false so it will only do one synchronous request
							this.bind = S(this.selector, {
								frame: frame, 
								forceSync: true
							})
						}
						if(logMessage){
							print(logMessage)
						}
						var methodArgs = [];
						// might need an argument
						if(argIndex > 0){
							methodArgs.push(args[3]);
						}
						// lazy flag to ignore the getter error below
						FuncUnit._ignoreGetterError = true;
						var ret = this.bind[fname].apply(this.bind, methodArgs)
						FuncUnit._ignoreGetterError = false;
						
						var passed = tester.call(this.bind, ret);
						
						// unless this is a "size" command, require size to be non-zero (don't pass through if there's no elements)
						if(this.bind.length === 0 && fname !== "size"){
							passed = false;
						}
						
						if(passed){
							// if document is still loading
							if(!FuncUnit.documentLoaded()){
								passed = false;
							} else {
								// after every successful wait, check for a new document (if someone clicked a link), 
								// and overwrite alert/confirm/prompt
								FuncUnit.checkForNewDocument();
							}
						}
						return passed;
					},
					success : function(){
						if(message){
							ok(true, message)
						}
						success && success.apply(this, arguments);
					},
					error : errorMessage,
					timeout : timeout,
					bind: this,
					type: "wait"
				})
				return this;
			}else{
				// throw a warning if user tries to use a getter after the start of the test (if there are other async methods)
				if(!FuncUnit._ignoreGetterError && !FuncUnit._incallback && FuncUnit._haveAsyncQueries()){
					console && console.error("You can't run getters after actions and waits. Please put your getters in a callback or at the beginning of the test.")
				}
				// just call the original jQ method
				var methodArgs = [];
				if(argIndex > 0){
					methodArgs.push(args[3]);
				}
				return orig.apply(this, methodArgs);
			}
		}
	}
	
	for (var prop in FuncUnit.funcs) {
		FuncUnit.makeFunc(prop, FuncUnit.funcs[prop]);
	}
})(window.jQuery || window.FuncUnit.jQuery)
