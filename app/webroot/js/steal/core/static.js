/**
 * @add steal
 */
// =============================== STATIC API ===============================
var events = {}, 
	page;

h.extend(st, {
	each: h.each,
	extend: h.extend,
	Deferred: Deferred,
	// Currently used a few places
	isRhino: h.win.load && h.win.readUrl && h.win.readFile,
	/**
	 * @hide
	 * Makes options
	 * @param {Object} options
	 */
	makeOptions: function( options, curId ) {
		// convert it to a uri
		if (!options.id ) {
			throw {
				message: "no id",
				options: options
			}
		}
		options.id = options.toId ? options.toId(options.id, curId) : st.id(options.id, curId);
		// set the ext
		options.ext = options.id.ext();
		options.src = options.idToUri ? options.idToUri(options.id) + "" : steal.idToUri(options.id) + "";
		// Check if it's a configured needs
		var configedExt = config.attr().ext[options.ext];
		// if we have something, but it's not a type
		if ( configedExt && ! config.attr().types[configedExt] ) {
			if (!options.needs ) {
				options.needs = [];
			}

			options.needs.push(configedExt);
		}
		
		return options;
	},
	/**
	 * Calls steal, but waits until all previous steals
	 * have completed loading until loading the
	 * files passed to the arguments:
	 * 
	 *     steal('jquery', 'can/util').then('file/that/depends/on_jquery.js')
	 *
	 * In this case first `jquery` and `can/util` will be loaded in parallel, 
	 * and after both are loaded `file/that/depends/on_jquery.js` will be loaded
	 */
	then: function() {
		var args = h.map(arguments);
		args.unshift(null)
		return st.apply(h.win, args);
	},
	/**
	 * `steal.bind( event, handler(eventData...) )` listens to 
	 * events on steal. Typically these are used by various build processes
	 * to know when steal starts and finish loading resources and their
	 * dependencies. Listen to an event like:
	 * 
	 *     steal.bind('end', function(rootModule){
	 *       rootModule.dependencies // the first stolen resources.
	 *     })
	 * 
	 * Steal supports the following events:
	 * 
	 *  - __start__ - steal has started loading a group of resources and their dependencies.
	 *  - __end__ - steal has finished loading a group of resources and their dependencies.
	 *  - __done__ - steal has finished loading the first set of resources and their dependencies.
	 *  - __ready__ - after both steal's "done" event  and the `window`'s onload event have fired.
	 * 
	 * For example, the following html:
	 * 
	 * @codestart html
	 * &lt;script src='steal/steal.js'>&lt;/script>
	 * &lt;script>
	 * steal('can/control', function(){
	 *   setTimeout(function(){
	 *     steal('can/model')    
	 *   },200)
	 * })
	 * &lt;/script>
	 * @codeend
	 * 
	 * Would fire:
	 * 
	 *  - __start__ - immediately after `steal('can/control')` is called
	 *  - __end__ - after 'can/control', all of it's dependencies, and the callback function have executed and completed.
	 *  - __done__ - fired after the first 'end' event.
	 *  - __ready__ - fired after window.onload and the 'done' event
	 *  - __start__ - immediately after `steal('can/model')` is called
	 *  - __end__ - fired after 'can/model' and all of it's dependencies have fired.
	 * 
	 * 
	 * 
	 * @param {String} event
	 * @param {Function} listener
	 */
	bind: function( event, listener ) {
		if (!events[event] ) {
			events[event] = []
		}
		var special = st.events[event]
		if ( special && special.add ) {
			listener = special.add(listener);
		}
		listener && events[event].push(listener);
		return st;
	},
	/**
	 * `steal.one(eventName, handler(eventArgs...) )` works just like
	 * [steal.bind] but immediately unbinds after `handler` is called.
	 */
	one: function( event, listener ) {
		return st.bind(event, function() {
			listener.apply(this, arguments);
			st.unbind(event, arguments.callee);
		});
	},
	events: {},
	/**
	 * `steal.unbind( eventName, handler )` removes an event listener on steal.
	 * @param {String} event
	 * @param {Function} listener
	 */
	unbind: function( event, listener ) {
		var evs = events[event] || [],
			i = 0;
		while ( i < evs.length ) {
			if ( listener === evs[i] ) {
				evs.splice(i, 1);
			} else {
				i++;
			}
		}
	},
	trigger: function( event, arg ) {
		var arr = events[event] || [];
		// array items might be removed during each iteration (with unbind),
		// so we iterate over a copy
		h.each(h.map(arr), function( i, f ) {
			f(arg);
		})
	},
	/**
	 * @hide
	 * Creates resources and marks them as loading so steal doesn't try 
	 * to load them. 
	 * 
	 *      steal.has("foo/bar.js","zed/car.js");
	 * 
	 * This is used when a file has other resources in it. 
	 */
	has: function() {
		// we don't use IE's interactive script functionality while
		// production scripts are loading
		h.support.interactive = false;
		h.each(arguments, function( i, arg ) {
			var stel = Module.make({
				id: arg,
				idToUri: st.idToUri
			});
			stel.loading = stel.executing = true;
		});
	},
	make: function(id){
		var opts = (typeof id === "string" ? {id: id} : id);
		if(!opts.idToUri){
			opts.idToUri = st.idToUri;
		}
		return Module.make(opts);
	},
	// a dummy function to add things to after the stel is created, but before executed is called
	preexecuted: function() {},
	/**
	 * @hide
	 * Signals that a resource's JS code has been run.  This is used
	 * when a file has other resources in it.
	 * 
	 *     steal.has("foo/bar.js");
	 * 
	 *     //start code for foo/bar.js 
	 *     steal("zed/car.js", function(){ ... });
	 *     steal.executed("foo/bar.js");
	 * 
	 * When a resource is executed, its dependent resources are loaded and eventually 
	 * executed.
	 */
	// called when a script has loaded via production
	executed: function( name ) {
		// create the steal, mark it as loading, then as loaded
		var resource = Module.make({
			id: name,
			idToUri: st.idToUri
		});
		resource.loading = resource.executing = true;
		//convert(stel, "complete");
		st.preexecuted(resource);
		resource.executed()
		return st;
	},
	type: function( type, cb ) {
		var typs = type.split(" ");
		if (!cb ) {
			return config.attr('types')[typs.shift()].require
		}
		
		var types = config.attr('types')

		types[typs.shift()] = {
			require: cb,
			convert: typs
		};

		config.attr('types', types)
	},
	request : h.request
});
// Determine if we're running in IE older than IE9. This 
// will affect loading strategy for JavaScripts.
h.useIEShim = (function(){
	if(st.isRhino) { return false; }

	var d = document.createElement('div');
	d.innerHTML = "<!--[if lt IE 9]>ie<![endif]-->";
	return !!(h.scriptTag().readyState && d.innerText === "ie");
})()