steal('funcunit/browser/resources/jquery.js').then(function($){

(function($){
	var getWindow = function( element ) {
		return element.ownerDocument.defaultView || element.ownerDocument.parentWindow
	}

/**
 * @add FuncUnit
 */
// prototype
/**
 * @Prototype
 */
//do traversers
var traversers = [
	/**
	 * @function closest
	 * Asynchronous version of jQuery's closest.  Performs the exact same functionality as the jQuery method 
	 * but adds itself to the queue.
	 * 
	 * @codestart
	 * // after the click, filter the collection, then wait for result to be visible
	 * S(".foo").click().closest(".bar").visible();
	 * @codeend
	 */
	"closest",
	/**
	 * @function next
	 * Asynchronous version of next. Performs the exact same functionality as the jQuery method 
	 * but adds itself to the queue.
	 * 
	 * @codestart
	 * // after the click, filter the collection, then wait for result to be visible
	 * S(".foo").click().next().visible();
	 * @codeend
	 */
	"next",
	/**
	 * @function prev
	 * Asynchronous version of prev. Performs the exact same functionality as the jQuery method 
	 * but adds itself to the queue.
	 * 
	 * @codestart
	 * // after the click, filter the collection, then wait for result to be visible
	 * S(".foo").click().prev().visible();
	 * @codeend
	 */
	"prev",
	/**
	 * @function siblings
	 * Asynchronous version of siblings. Performs the exact same functionality as the jQuery method 
	 * but adds itself to the queue.
	 * 
	 * @codestart
	 * // after the click, filter the collection, then wait for result to be visible
	 * S(".foo").click().siblings().visible();
	 * @codeend
	 */
	"siblings",
	/**
	 * @function last
	 * Asynchronous version of last. Performs the exact same functionality as the jQuery method 
	 * but adds itself to the queue.
	 * 
	 * @codestart
	 * // after the click, filter the collection, then wait for result to be visible
	 * S(".foo").click().last().visible();
	 * @codeend
	 */
	"last",
	/**
	 * @function first
	 * Asynchronous version of first. Performs the exact same functionality as the jQuery method 
	 * but adds itself to the queue.
	 * 
	 * @codestart
	 * // after the click, filter the collection, then wait for result to be visible
	 * S(".foo").click().first().visible();
	 * @codeend
	 */
	"first", 
	/**
	 * @function find
	 * Asynchronous version of find. Performs the exact same functionality as the jQuery method 
	 * but adds itself to the queue.
	 * 
	 * @codestart
	 * // after the click, filter the collection, then wait for result to be visible
	 * S(".foo").click().find(".bar").visible();
	 * @codeend
	 */
	"find"
],
	makeTraverser = function(name){
		var orig = FuncUnit.prototype[name];
		FuncUnit.prototype[name] = function(selector){
			var args = arguments;
			// find is called (with "this" as document) from FuncUnit.fn.init, so in this case don't queue it up, just run the regular find
			if (FuncUnit.win && this[0] && this[0].nodeType !== 9) { // document nodes are 9
				FuncUnit.add({
					method: function(success, error){
						// adjust the collection by using the real traverser method
						var newBind = orig.apply(this.bind, args);
						newBind.prevTraverser = name;
						newBind.prevTraverserSelector = selector;
						success(newBind)
					},
					error: "Could not traverse: " + name + " " + selector,
					bind: this
				});
			}
			return orig.apply(this, arguments);
		}
	};
for(var i  =0; i < traversers.length; i++){
	makeTraverser(traversers[i]);
}


}(window.jQuery  || window.FuncUnit.jQuery));


})