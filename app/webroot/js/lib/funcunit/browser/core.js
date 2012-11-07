steal('./init', function(oldFuncUnit) {
	if(!window.QUnit && !window.jasmine){
		steal('funcunit/qunit')
	}

	var FuncUnit = oldFuncUnit.jQuery.sub();
	var origFuncUnit = FuncUnit;
	// override the subbed init method
	// context can be an object with frame and forceSync:
	// - a number or string: this is a frame name/number, and means only do a sync query
	// - true: means force the query to be sync only
	FuncUnit = function( selector, context ) {
		// if you pass true as context, this will avoid doing a synchronous query
		var frame,
			forceSync, 
			isSyncOnly = false,
			isAsyncOnly = false;
			
		if(typeof context === "string"){
			frame = context;
		}
		if(context && typeof context.forceSync === "boolean"){
			forceSync = context.forceSync;	
			if (typeof context.frame == "number" || typeof context.frame == "string") {
				frame = context.frame;
				context = context.frame;
			}
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
			var collection = performSyncQuery(selector, context, this);
			collection.frame = frame;
			return collection;
		} else if(isAsyncOnly === true){
			performAsyncQuery(selector, context, this);
			return this;
		} else { // do both
			performAsyncQuery(selector, context, this);
			var collection = performSyncQuery(selector, context, this);
			collection.frame = frame;
			return collection;
		}
	}
	
	var getContext = function(context){
			if (typeof context === "number" || typeof context === "string") {
				// try to get the context from an iframe in the funcunit document
				var sel = (typeof context === "number" ? "iframe:eq(" + context + ")" : "iframe[name='" + context + "']"),
					frames = new origFuncUnit.fn.init(sel, FuncUnit.win.document, true);
				context = (frames.length ? frames.get(0).contentWindow : FuncUnit.win).document;
			} else {
				context = FuncUnit.win.document;
			}
			return context;
		},
		performAsyncQuery = function(selector, context, self){
			FuncUnit.add({
				method: function(success, error){
					if (FuncUnit.win) {
						context = getContext(context);
					}
					this.selector = selector;
					this.bind = new origFuncUnit.fn.init( selector, context, true );
					success();
					return this;
				},
				error: "selector failed: " + selector,
				type: "query"
			});
		},
		performSyncQuery = function(selector, context, self){
			if (FuncUnit.win) {
				context = getContext(context);
			}
			return new origFuncUnit.fn.init( selector, context, true );
		}
	
	oldFuncUnit.jQuery.extend(FuncUnit, oldFuncUnit, origFuncUnit)
	FuncUnit.prototype = origFuncUnit.prototype;
	return FuncUnit;
})()
