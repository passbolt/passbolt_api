steal('jquery', './init', function(jQuery, oldFuncUnit) {
	
	if(!window.QUnit && !window.jasmine){
		steal('funcunit/qunit')
	}

	var FuncUnit = oldFuncUnit.jQuery.sub();
	var origFuncUnit = FuncUnit;
	// override the subbed init method
	// context can be an object with frame and forceSync:
	// - a number or string: this is a frame name/number, and means only do a sync query
	// - true: means force the query to be sync only
	FuncUnit = function( selector, frame ) {
		// if you pass true as context, this will avoid doing a synchronous query
		var frame,
			forceSync, 
			isSyncOnly = false;
		
		if(frame && frame.forceSync){
			forceSync = frame.forceSync;
		}
		
		if(frame && typeof frame.frame !== "undefined"){ // its passed as an object
			frame = frame.frame;
		}
		
		isSyncOnly = typeof forceSync === "boolean"? forceSync: isSyncOnly;
		// if its a function, just run it in the queue
		if(typeof selector == "function"){
			return FuncUnit.wait(0, selector);
		}
		// if the app window already exists, adjust the params (for the sync return value)
		this.selector = selector;
		// run this method in the queue also
		if(isSyncOnly === true){
			var collection = performSyncQuery(selector, frame);
			return collection;
		} else { // do both
			performAsyncQuery(selector, frame, this);
			var collection = performSyncQuery(selector, frame);
			return collection;
		}
	}


	
	var getContext = function(context){
			if (typeof context === "number" || typeof context === "string") {
				// try to get the context from an iframe in the funcunit document
				var sel = (typeof context === "number" ? "iframe:eq(" + context + ")" : "iframe[name='" + context + "']"),
					frames = new origFuncUnit.fn.init(sel, FuncUnit.win.document.documentElement, true);
				var frame = (frames.length ? frames.get(0).contentWindow : FuncUnit.win).document.documentElement;
				
			} else {
				frame = FuncUnit.win.document.documentElement;
			}
			return frame;
		},
		performAsyncQuery = function(selector, frame, self){
			FuncUnit.add({
				method: function(success, error){
					this.frame = frame;
					if (FuncUnit.win) {
						frame = getContext(frame);
					}
					this.selector = selector;
					this.bind = new origFuncUnit.fn.init( selector, frame, true );
					success();
					return this;
				},
				error: "selector failed: " + selector,
				type: "query"
			});
		},
		performSyncQuery = function(selector, frame){
			var origFrame = frame;
			if (FuncUnit.win) {
				frame = getContext(frame);
			}
			var obj = new origFuncUnit.fn.init( selector, frame, true );
			obj.frame = origFrame;
			return obj;
		}
	
	oldFuncUnit.jQuery.extend(FuncUnit, oldFuncUnit, origFuncUnit)
	FuncUnit.prototype = origFuncUnit.prototype;
	return FuncUnit;
})()
