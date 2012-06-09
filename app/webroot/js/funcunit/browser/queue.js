(function(){
	/**
	 * @add FuncUnit
	 */
	/**
	 * True when we are in a callback function (something we pass to a FuncUnit plugin).
	 */
	FuncUnit._incallback = false;
	//where we should add things in a callback
	var currentPosition = 0,
		startedQueue = false;
		
		
	/**
	 * A global timeout value for wait commands.  Defaults to 10 seconds.
	 */
	FuncUnit.timeout = 10000;
	/**
	 * A queue of methods.  Each method in the queue are run in order.  After the method is complete, it 
	 * calls FuncUnit._done, which pops the next method off the queue and runs it.
	 */
	FuncUnit._queue = [];
	/**
	 * @hide
	 * Logic that determines if this next query needs to be sync, or if we can optimize it.
	 * Returns false if there are actual actions in the queue, returns true if the only queued methods are 
	 * S methods. If the only method is an S query, remove it from the queue.
	 */
	FuncUnit._needSyncQuery = function(){
		// if only method is query, need sync
		if(FuncUnit._queue.length === 1){
			if(FuncUnit._queue[0].type === "query"){
				FuncUnit._queue = [];
				return true;
			}
		}
		// if empty queue, need sync
		if(FuncUnit._queue.length === 0){
			return true;
		}
		return false
	}
	/**
	 * @hide
	 * Return last item in the queue.
	 */
	FuncUnit._lastQueuedItem = function(){
		if(!FuncUnit._queue.length){
			return null;
		}
		return FuncUnit._queue[FuncUnit._queue.length-1];
	}
	/**
	 * @hide
	 * Return true if there are already async methods queued.  If true, getters need throw errors.
	 */
	FuncUnit._haveAsyncQueries = function(){
		for(var i=0; i < FuncUnit._queue.length; i++){
			if(FuncUnit._queue[i].type === "action" || FuncUnit._queue[i].type === "wait")
				return true;
		}
		return false;
	}
	FuncUnit.
	/**
	 * Adds a function to the queue.
	 * @param {Object} handler An object that contains the method to run along with other properties:

 - method : the method to be called.  It will be provided a success and error function to call
 - success : an optional callback to be called after the function is done
 - error : an error message if the command fails
 - timeout : the time until success should be called
 - bind : an object that will be 'this' of the success
 - type: the type of method (optional)
 
	 */
	add = function(handler){
		//if we are in a callback, add to the current position
		if (FuncUnit._incallback) {
			FuncUnit._queue.splice(currentPosition, 0, handler);
			currentPosition++;
		}
		else {
			//add to the end
			FuncUnit._queue.push(handler);
		}
		//if our queue has just started, stop qunit
		//call done to call the next command
        if (FuncUnit._queue.length == 1 && ! FuncUnit._incallback) {
			stop();
    		setTimeout(FuncUnit._done, 13)
        }
	}
	var currentEl;
	/**
	 * Every queued method calls this when its complete.  It gets the next function from the queue and calls it.
	 * @param {Object} el the current jQuery collection
	 * @param {Object} selector
	 */
	FuncUnit._done = function(el, selector){
		var next, 
			timer,
			speed = 0;
			
		if(FuncUnit.speed == "slow"){
			speed = 500;
		}
		if (FuncUnit._queue.length > 0) {
			next = FuncUnit._queue.shift();
			currentPosition = 0;
			// set a timer that will error
			
			//call next method
			setTimeout(function(){
				timer = setTimeout(function(){
						next.stop && next.stop();
						ok(false, next.error);
						FuncUnit._done();
					}, 
					(next.timeout || FuncUnit.timeout) + speed)
				// if the last successful method had a collection, save it
				if(el && el.jquery){
					currentEl = el;
				}
				// make the new collection the last successful collection
				if(currentEl){
					next.bind = currentEl;
				}
				next.selector = selector;
				next.method(	//success
					function(el){
						if(el && el.jquery){
							next.bind = el;
						}
						//make sure we don't create an error
						clearTimeout(timer);
						
						//mark in callback so the next set of add get added to the front
						
						FuncUnit._incallback = true;
						if (next.success) {
							// callback's "this" is the collection
							next.success.apply(next.bind, arguments);
						}
						FuncUnit._incallback = false;
						
						
						FuncUnit._done(next.bind, next.selector);
					}, //error
					function(message){
						clearTimeout(timer);
						ok(false, message);
						FuncUnit._done();
					})
				
				
			}, speed);
			
		}
		else {
			start();
		}
	}
})()