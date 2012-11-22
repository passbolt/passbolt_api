// =============================== STARTUP ===============================
var rootSteal = false;

// essentially ... we need to know when we are on our first steal
// then we need to know when the collection of those steals ends ...
// and, it helps if we use a 'collection' steal because of it's natural
// use for going through the pending queue
//
h.extend(st, {
	// modifies src
	/*makeOptions : after(steal.makeOptions,function(raw){
		raw.src = URI.root().join(raw.rootSrc = URI( raw.rootSrc ).insertMapping());
	}),*/

	//root mappings to other locations
	mappings: {},

	/**
	 * Maps a 'rooted' folder to another location. For instance you could use it 
	 * to map from the `foo/bar` location to the `http://foo.cdn/bar`:
	 *
	 *     steal.map('foo/bar', 'http://foo.cdn/bar');
	 *
	 * @param {String|Object} from the location you want to map from.  For example:
	 *   'foo/bar'
	 * @param {String} [to] where you want to map this folder too.  Ex: 'http://foo.cdn/bar'
	 * @return {steal}
	 */
	map: function( from, to ) {
		if ( h.isString(from) ) {
			st.mappings[from] = {
				test: new RegExp("^(\/?" + from + ")([/.]|$)"),
				path: to
			};
			h.each(modules, function( id, module ) {
				if ( module.options.type != "fn" ) {
					// TODO terrible
					var buildType = module.options.buildType;
					module.updateOptions();
					module.options.buildType = buildType;
				}
			})
		} else { // its an object
			h.each(from, st.map);
		}
		return this;
	},
	// called after steals are added to the pending queue
	after: function() {
		// if we don't have a current 'top' steal
		// we create one and set it up
		// to start loading its dependencies (the current pending steals)
		if (!rootSteal ) {
			rootSteal = new Module();
			// keep a reference in case it disappears
			var cur = rootSteal,
				// runs when a steal is starting
				go = function() {
					// indicates that a collection of steals has started
					st.trigger("start", cur);
					cur.completed.then(function() {

						rootSteal = null;
						st.trigger("end", cur);


					});

					cur.executed();
				};
			// If we are in the browser, wait a
			// brief timeout before executing the rootModule.
			// This allows embeded script tags with steal to be part of 
			// the initial set
			if ( h.win.setTimeout ) {
				// we want to insert a "wait" after the current pending
				st.pushPending();
				setTimeout(function() {
					st.popPending();
					go();
				}, 0)
			} else {
				// if we are in rhino, start loading dependencies right away
				go()
			}
		}
	},
	_before: h.before,
	_after: h.after
});

(function(){
	var myPending;
	st.pushPending = function(){
		myPending = Module.pending.slice(0);
		Module.pending = [];
		h.each(myPending, function(i, arg){
			Module.make(arg);
		})
	}
	st.popPending = function(){
		Module.pending = Module.pending.length ? myPending.concat(null,Module.pending) : myPending;
	}
})();

// =============================== jQuery ===============================
(function() {
	var jQueryIncremented = false,
		jQ, ready = false;

	// check if jQuery loaded after every script load ...
	Module.prototype.executed = h.before(Module.prototype.executed, function() {

		var $ = h.win.jQuery;
		if ( $ && "readyWait" in $ ) {

			//Increment jQuery readyWait if ncecessary.
			if (!jQueryIncremented ) {
				jQ = $;
				$.readyWait += 1;
				jQueryIncremented = true;
			}
		}
	});

	// once the current batch is done, fire ready if it hasn't already been done
	st.bind("end", function() {
		if ( jQueryIncremented && !ready ) {
			jQ.ready(true);
			ready = true;
		}
	})

})();

// =========== DEBUG =========

// var name = function(stel){
// 	if(stel.options && stel.options.type == "fn"){
// 		return stel.orig.name? stel.orig.name : stel.options.id+":fn";//(""+stel.orig).substr(0,10)
// 	}
// 	return stel.options ? stel.options.id + "": "CONTAINER"
// }
// 
// Module.prototype.load = before( Module.prototype.load, function(){
// 	console.log("      load", name(this), this.loading, steal._id, this.id)
// })
// 
// Module.prototype.executed = before(Module.prototype.executed, function(){
// 	var namer= name(this)
// 	console.log("      executed", namer, steal._id, this.id)
// })
// 
// Module.prototype.complete = before(Module.prototype.complete, function(){
// 	console.log("      complete", name(this), steal._id, this.id)
// })

// ============= WINDOW LOAD ========
var loaded = {
		load: Deferred(),
		end: Deferred()
	},
	firstEnd = false;

h.addEvent(h.win, "load", function() {
	loaded.load.resolve();
});

st.one("end", function( collection ) {
	loaded.end.resolve(collection);
	firstEnd = collection;
	st.trigger("done", firstEnd)
})
st.firstComplete = loaded.end;

Deferred.when(loaded.load, loaded.end).then(function() {
	st.trigger("ready")
	st.isReady = true;
});

st.events.done = {
	add: function( cb ) {
		if ( firstEnd ) {
			cb(firstEnd);
			return false;
		} else {
			return cb;
		}
	}
};

startup = h.after(startup, function() {
	// get options from 
	var urlOptions = st.getScriptOptions();
	// A: GET OPTIONS
	// 1. get script options
	//h.extend(options, ); TODO: remove
	// 2. options from a steal object that existed before this steal
	// the steal object is copied right away
	// h.extend(options, opts);
	// 3. if url looks like steal[xyz]=bar, add those to the options
	// does this need to be supported anywhere?
	// NO - Justin
	var search = h.win.location && decodeURIComponent(h.win.location.search);
	search && search.replace(/steal\[([^\]]+)\]=([^&]+)/g, function( whoe, prop, val ) {
		urlOptions[prop] = ~val.indexOf(",") ? val.split(",") : val;
	});
	// B: DO THINGS WITH OPTIONS
	// CALCULATE CURRENT LOCATION OF THINGS ...
	config.attr(urlOptions);
	var options = config.attr();

	// mark things that have already been loaded
	h.each(options.executed || [], function( i, stel ) {
		st.executed(stel)
	})
	// immediate steals we do
	var steals = [];

	// add start files first
	if ( options.startFiles ) {
		/// this can be a string or an array
		steals.push.apply(steals, h.isString(options.startFiles) ? [options.startFiles] : options.startFiles)
		options.startFiles = steals.slice(0)
	}

	// either instrument is in this page (if we're the window opened from
	// steal.browser), or its opener has it
	// try-catching this so we dont have to build up to the iframe
	// instrumentation check
	try {
		// win.top.steal.instrument is for qunit
		// win.top.opener.steal.instrument is for funcunit
		if(!options.browser && ((h.win.top && h.win.top.st.instrument) || 
								(h.win.top && h.win.top.opener && h.win.top.opener.steal && h.win.top.opener.st.instrument))) {

			// force startFiles to load before instrument
			steals.push(h.noop, {
				id: "steal/instrument",
				waits: true
			});
		}
	} catch (e) {
		// This would throw permission denied if
		// the child window was from a different domain
	}

	// we only load things with force = true
	if ( config.attr().env == "production" && config.attr().loadProduction && config.attr().production ) {
		st({
			id: config.attr().production,
			force: true
		});
	} else {
		steals.unshift({
			id: "stealconfig.js",
			abort: false
		});

		if ( options.loadDev !== false ) {
			steals.unshift({
				id: "steal/dev/dev.js",
				ignore: true
			});
		}

		if ( options.startFile ) {
			steals.push(null,options.startFile)
		}
	}
	if ( steals.length ) {
		st.apply(h.win, steals);
	}
});