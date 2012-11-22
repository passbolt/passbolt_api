var moduleManager = function(steal, stealModules, interactives, config){

	// ============ MODULE ================
	// a map of modules by moduleID
	var modules = {},
		id = 0;
	// this is for methods on a 'steal instance'.  A file can be in one of a few states:
	// created - the steal instance is created, but we haven't started loading it yet
	//           this happens when thens are used
	// loading - (loading=true) By calling load, this will tell steal to load a file
	// loaded - (isLoaded=true) The file has been run, but its dependency files have been completed
	// complete - all of this files dependencies have loaded and completed.

	// A Module is almost anything. It is different from a module
	// as it doesn't represent some unit of functionality, rather
	// it represents a unit that can have other units "within" it
	// as dependencies.  A module can:
	//
	// - load - load the module to the client so it is available, but don't run it yet
	// - run - run the code for the module
	// - executed - the code has been run for the module, but all
	//   dependencies for that module might not have finished
	// - completed - all modules within the module have completed
	//
	// __options__
	// `options` can be a string, function, or object.
	//
	// __properties__
	//
	// - options - has a number of properties
	//    - src - a URI to this module that can be loaded from the current page
	//    - rootSrc - a URI to this module relative to the current root URI.
	//    - type - the type of module: "fn", "js", "css", etc
	//    - needs - other modules that must be loaded prior to this module
	//    - fn - a callback function to run when executed
	// - unique - false if this module should be loaded each time
	// - waits - this module should wait until all prior scripts have completed before running
	// - loaded - a deferred indicating if this module has been loaded to the client
	// - run - a deferred indicating if the the code for this module run
	// - completed - a deferred indicating if all of this modules dependencies have
	//   completed
	// - dependencies - an array of dependencies

	var Module = function( options ) {
		// an array for dependencies, this is the steal calls this module makes
		this.dependencies = [];

		// an array of implicit dependencies this steal needs
		this.needsDependencies = [];

		// id for debugging
		this.id = (++id);
		// the original options
		this.orig = options;
		// the parent steal's id
		this.curId = steal.cur && steal.cur.options.id;

		this.setOptions(options);
		// create the deferreds used to manage state
		this.loaded = Deferred();
		this.run = Deferred();
		this.completed = Deferred();
	};

	Module.pending = [];
	// `Module.make` is used to either create
	// a new module, or return an existing
	// module that matches the options.
	Module.make = function( options ) {
		// create the temporary reasource
		var module = new Module(options),
			// use `rootSrc` as the definitive ID
			id = module.options.id;

		// assuming this module should not be created again.
		if ( module.unique && id ) {

			// Check if we already have a module for this rootSrc
			// Also check with a .js ending because we defer 'type'
			// determination until later
			if (!modules[id] && !modules[id + ".js"] ) {
				// If we haven't loaded, cache the module
				modules[id] = module;
			} else {

				// Otherwise get the cached module
				existingModule = modules[id];
				// If options were passed, copy new properties over.
				// Don't copy src, etc because those have already
				// been changed to be the right values;
				if (!h.isString(options) ) {
					// extend everything other than id
					for(var prop in options){
						if(prop !== "id") {
							existingModule.options[prop] = options[prop];
						}
					}
				}
				return existingModule;
			}
		}

		return module;
	};

	// updates the paths of things ...
	// use stealModules b/c they are more fuzzy
	// a module's id stays the same, but a path might change
	// 

	h.extend(Module.prototype, {
		setOptions: function( options ) {
			var prevOptions = this.options; 
			// if we have no options, we are the global Module that
			// contains all other modules.
			if (!options ) { //global init cur ...
				this.options = {};
				this.waits = false;
			}
			//handle callback functions
			else if ( h.isFn(options) ) {
				var uri = URI.cur,
					self = this,
					cur = steal.cur;
				this.options = {
					fn: function() {

						// Set the URI if there are steals
						// within the callback.
						URI.cur = uri;

						// we should get the current "module"
						// check it's listed dependencies and see
						// if they have a value
						var args = [],
							found = false,
							dep, value;
						// iterate backwards through dependencies
						for ( var i = cur.dependencies.length; i >= 0; i-- ) {
							dep = cur.dependencies[i];

							if ( found ) {
								if ( dep === null  ) {
								//	//alert("YES")
									break;
								}
								// We need to access the stored stealModules in this order
								// - calculated id
								// - original name
								// - dependency return value otherwise
								value = stealModules[dep.options.id] || stealModules[dep.orig] || dep.value;
								args.unshift(value);
								
								// what does this do?
								
							}
							
							if ( dep === self ) {
								found = true;
							}
						}



						var ret = options.apply(cur, args);

						// if this returns a value, we should register it as a module ...
						if ( ret ) {
							// register this module ....
							cur.value = ret;
						}
						return ret;
					},
					id: uri,
					type: "fn"
				}
				// this has nothing to do with 'loading' options
				this.waits = true;
				this.unique = false;
			} else {
				// save the original options
				this.options = steal.makeOptions(h.extend({}, options), this.curId);

				this.waits = this.options.waits || false;
				this.unique = true;
			}
			// if there are other options we haven't already set, reuse the old ones
			for(opt in prevOptions){
				if(!this.options[opt]){
					this.options[opt] = prevOptions[opt];
				}
			}
			if(this.options.id){
				this.options.abort = false;
			}
		},
		
		// Calling complete indicates that all dependencies have
		// been completed for this module
		complete: function() {
			this.completed.resolve();
		},
		// After the script has been loaded and run
		// - checks what has been stolen (in pending)
		// - wires up pendings steal's deferreds to eventually complete this
		// - this is where all of steal's complexity is
		executed: function( script ) {
			var myqueue, 
				stel, 
				src = this.options.src,
				rootSrc = this.options.rootSrc;
			// Set this as the current file so any relative urls
			// will load from it.
			// rootSrc needs to be the translated path
			// we need id vs rootSrc ...
			
			if ( this.options.id ) {
				URI.cur = URI(this.options.id);
			}
			if( this.exports ){
				this.exports()
			}
			// set this as the current module
			steal.cur = this;

			// mark yourself as 'loaded'.
			this.run.resolve();

			// If we are IE, get the queue from interactives.
			// It in interactives because you can't use onload to know
			// which script is executing.
			if ( h.support.interactive && src ) {
				/*myqueue = interactives[src];*/
				if(interactives[src]){
					myqueue = [];
					if(interactives.length){
						for(var i = 0; i < interactives.length; i++){
							if(interactives[i] !== this.orig){
								myqueue.push(interactives[i])
							}
						}
					} else {
						if(interactives[src] !== this.orig){
							myqueue = interactives[src];
							delete interactives[src];
						}
					}
					
				}
			}
			// In other browsers, the queue of items to load is
			// what is in pending
			if (!myqueue ) {
				myqueue = Module.pending.slice(0);
				Module.pending = [];
			}

			// if we have nothing, mark us as complete
			if (!myqueue.length ) {
				this.complete();
				return;
			}
			this.addDependencies(myqueue)
			this.loadDependencies();

		},
		// add depenedencies to the module
		addDependencies  : function(myqueue){
			var self = this,
				isProduction = steal.config().env == "production";
			this.queue = [];
			h.each(myqueue, function( i, item ) {
				if( item === null){
					self.queue.push(null);
					return;
				}
				
				if ( (isProduction && item.ignore) || (!isProduction && !steal.isRhino && item.prodonly)) {
					return;
				}
				
				// make a steal object
				var stel = Module.make(item);
				if ( steal.packHash[stel.options.id] && stel.options.type !== 'fn' ) { // if we are production, and this is a package, mark as loading, but steal package?
					steal.has(""+stel.options.id);
					stel = steal.make(steal.packHash[""+stel.options.id]);
				}
				// has to happen before 'needs' for when reversed...
				self.queue.push(stel);
			});
		},
		// loads module's dependencies
		loadDependencies : function(){
			
			//print("-setting up "+this.options.id)
			// now we have to figure out how to wire up our pending steals
			var self = this,
				// the current
				

			// iterate through the collection and add all the 'needs'
			// before fetching...

			//print("-instances "+this.options.id)
			// The set of modules before the previous "wait" module
			priorSet = [],
				// The current set of modules after and including the
				// previous "wait" module
				set = [],
				// The first set of modules that we will execute
				// right away. This should be the first set of dependencies
				// that we can load in parallel. If something has
				// a need, the need should be in this set
				firstSet = [],
				// Should we be adding modules to the
				// firstSet
				setFirstSet = true;

			// Goes through each module and maintains
			// a list of the set of modules
			// that must be complete before the current
			// module (`priorSet`).
			h.each( this.queue, function( i, module ) {
				// add it as a dependency, circular are not allowed
				self.dependencies.push(module);

				// if there's a wait and it's not the first thing
				if ( (module === null || module.waits) && set.length ) {
					// add the current set to `priorSet`
					priorSet = priorSet.concat(set);
					// empty the current set
					set = [];
					// we have our firs set of items
					setFirstSet = false;
					if(module === null) {
						return;
					}

				}
				if ( module === null ) return;

				// lets us know this module is currently wired to load
				module.isSetupToExecute = true;
				// when the priorSet is completed, execute this module
				// and when it's needs are done
				var waitsOn = priorSet.slice(0);
				// if there are needs, this can not be part of the "firstSet"
				h.each(module.options.needs || [], function( i, raw ) {
					
					var need = Module.make({
						id: raw,
						idToUri: self.options.idToUri
					});
					// add the need to the module's dependencies
					h.uniquePush(module.needsDependencies, need);
					waitsOn.push(need);
					// add needs to first set to execute
					firstSet.push(need)
				});
				waitsOn.length && whenEach(waitsOn, "completed", module, "execute");

				// what is this used for?
				// module.waitedOn = module.waitedOn ? module.waitedOn.concat(priorSet) : priorSet.slice(0);

				// add this steal to the current set
				set.push(module);
				// if we are still on the first set, and this has no needs
				if ( setFirstSet && (module.options.needs || []).length == 0) {
					// add this to the first set of things
					firstSet.push(module)
				}
				// start loading the module if possible
				module.load();
			});

			// when every thing is complete, mark us as completed
			priorSet = priorSet.concat(set);
			whenEach(priorSet, "completed", self, "completed");

			// execute the first set of dependencies
			h.each(firstSet, function( i, f ) {
				f.execute();
			});
		},
		/**
		 * Loads this steal
		 */
		load: function( returnScript ) {
			// if we are already loading / loaded
			if ( this.loading || this.loaded.isResolved() ) {
				return;
			}

			this.loading = true;
			this.loaded.resolve();
		},
		execute: function() {
			var self = this;
			// if a late need dependency was addded
			if(this.lateNeedDependency && !this.lateNeedDependency.completed.isResolved()){
				// call execute again when it's finished
				this.lateNeedDependency.completed.then(function(){
					self.execute()
				})
				return;
			}
			
			// check types
			var raw = this.options,
			types = config.attr('types');

			// if it's a string, get it's extension and check if
			// it is a registered type, if it is ... set the type
			if (!raw.type ) {
				var ext = URI(raw.id).ext();
				if (!ext && !types[ext] ) {
					ext = "js";
				}
				raw.type = ext;
			}
			if (!types[raw.type] && steal.config().env == 'development' ) {
				throw "steal.js - type " + raw.type + " has not been loaded.";
			} else if (!types[raw.type] && steal.config().env == 'production' ) {
				// if we haven't defined EJS yet and we're in production, its ok, just ignore it
				return;
			}
			var converters = types[raw.type].convert;
			raw.buildType = converters.length ? converters[converters.length - 1] : raw.type;
			
			if (!self.loaded.isResolved() ) {
				self.loaded.resolve();
			}
			if (!self.executing ) {
				self.executing = true;

				config.require(self.options, function( value ) {
					self.executed( value );
				}, function( error, src ) {
					var abortFlag = self.options.abort,
						errorCb = self.options.error;

					// if an error callback was provided, fire it
					if ( errorCb ) {
						errorCb.call(self.options);
					}

					h.win.clearTimeout && h.win.clearTimeout(self.completeTimeout)

					// if abort: false, register the script as loaded, and don't throw
					if ( abortFlag === false ) {
						self.executed();
						return;
					}
					throw "steal.js : " + self.options.src + " not completed"
				});
			}
		},
		updateOptions: function(){
			var buildType = this.options.buildType;
			var orginalOptions = this.options;
			this.setOptions(this.orig);
			var newOptions = this.options;
			this.options = orginalOptions;
			for (opt in newOptions) {
				this.options[opt] = newOptions[opt];
			}
			this.options.buildType = buildType;
		},
		rewriteIdAndUpdateOptions : function(id){
			// if module is not a function it means it's `src` is changeable
			if ( this.options.type != "fn" ) {
				// finds module's needs 
				// TODO this is terrible
				var needs = (this.options.needs || []).slice(0),
					buildType = this.options.buildType;
				this.updateOptions();
				var newId = this.options.id;
				// this mapping is to move a config'd key

				if (id !== newId) {
					modules[newId] = this;
					// TODO: remove the old one ....
				}
				this.options.buildType = buildType;
				
				// if a module is set to load
				// check if there are new needs
				if( this.isSetupToExecute ) {
					this.addLateDependencies(needs);
				}
			}
		},
		addLateDependencies : function(needs){
			var self = this;
			// find all `needs` and set up "late dependencies"
			// this allows us to steal files that need to load
			// special converters without loading these converters
			// explicitely:
			// 
			//    steal('view.ejs', function(ejsFn){...})
			//
			// This will load files needed to convert .ejs files
			// without explicite steal
			h.each(this.options.needs||[],function(i,need){
				if(h.inArray(needs, need) == -1){
					var n = steal.make(need);
					n.execute()
					self.needsDependencies.push(n);
					self.lateNeedDependency = n;
				}
			})
		}
	});

	// =============================== ERROR HANDLING ===============================
	h.extend(Module.prototype, {
		load: h.after(Module.prototype.load, function( stel ) {
			var self = this;
			if ( h.doc && !self.completed && !self.completeTimeout && !steal.isRhino && (self.options.src.protocol == "file" || !h.support.error) ) {
				self.completeTimeout = setTimeout(function() {
					throw "steal.js : " + self.options.src + " not completed"
				}, 5000);
			}
		}),
		complete: h.after(Module.prototype.complete, function() {
			this.completeTimeout && clearTimeout(this.completeTimeout)
		}),

		// if we're about to mark a file as executed, mark its "has" array files as
		// executed also
		executed: h.before(Module.prototype.executed, function() {
			if ( this.options.has ) {
				this.loadHas();
			}
		}),

		/**
		 * @hide
		 * Goes through the array of files listed in this.options.has, marks them all as loaded.
		 * This is used for files like production.css, which, once they load, need to mark the files they
		 * contain as loaded.
		 */
		loadHas: function() {
			var stel, i, current = URI.cur;

			if ( this.options.buildType == 'js' ) {
				return;
			}

			// mark everything in has loaded
			h.each(this.options.has, function( i, has ) {
				// don't want the current file to change, since we're just marking files as loaded
				URI.cur = URI(current);
				steal.executed(has);
			});

		}
	});


	// =========== HAS ARRAY STUFF ============
	// Logic that deals with files that have collections of other files within
	// them.  This is usually a production.css file,
	// which when it loads, needs to mark several CSS and LESS files it represents
	// as being "loaded".  This is done by the production.js file having
	// steal({src: "production.css", has: ["file1.css", "file2.css"]
	//
	// after a steal is created, if its been loaded
	// already and has a "has", mark those files as loaded
	Module.make = h.after(Module.make, function( stel ) {
		// if we have things
		if ( stel.options.has ) {
			// if we have loaded this already (and we are adding has's)
			if ( stel.run.isResolved() ) {
				stel.loadHas();
			} else {
				// have to mark has as loading and executing (so we don't try to get them)
				steal.has.apply(steal, stel.options.has)
			}
		}
		return stel;
	}, true);
	Module.modules = modules;
	return Module;
}