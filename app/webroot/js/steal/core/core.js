// steal is a resource loader for JavaScript.  It is broken into the following parts:
//
// - Helpers - basic utility methods used internally
// - AOP - aspect oriented code helpers
// - Deferred - a minimal deferred implementation
// - Uri - methods for dealing with urls
// - Api - steal's API
// - Module - an object that represents a resource that is loaded and run and has dependencies.
// - Type - a type systems used to load and run different types of resources
// - Packages -  used to define packages
// - Extensions - makes steal pre-load a type based on an extension (ex: .coffee)
// - Mapping - configures steal to load resources in a different location
// - Startup - startup code
// - jQuery - code to make jQuery's readWait work
// - Error Handling - detect scripts failing to load
// - Has option - used to specify that one resources contains multiple other resources
// - Window Load - API for knowing when the window has loaded and all scripts have loaded
// - Interactive - Code for IE
// - Options - 
(function( undefined ) {

	/*# helpers.js #*/

	/*# deferred.js #*/

	/*# uri.js #*/

	/*# config_manager.js #*/

	/*# types.js #*/

	/*# module.js #*/

	function stealManager(kickoff, config, setStealOnWindow){

		// a startup function that will be called when steal is ready
		var interactiveScript,
			// key is script name, value is array of pending items
			interactives = {},
			// empty startup function
			startup = function(){};
		
		var st = function() {
			
			// convert arguments into an array
			var args = h.map(arguments, function(options){
				if(options){
					var opts = h.isString(options) ? {
						id: options
					} : options;
					
					if( !opts.idToUri ){
						opts.idToUri =  st.idToUri
					} 
					return opts;
				} else {
					return options;
				}
			});
			if ( args.length ) {
				Module.pending.push.apply(Module.pending, args);
				// steal.after is called everytime steal is called
				// it kicks off loading these files
				st.after(args);
				// return steal for chaining
			}

			return st;
		};
		if(setStealOnWindow){
			h.win.steal = st;
		}
		// clone steal context
		st.clone = function(){
			return stealManager(false, config.cloneContext())
		}

		st.config = function(){
			st.config.called = true;
			return config.attr.apply(config, arguments)
		};
		st.require = function(){
			return config.require.apply(config, arguments);
		}
		st.config.called = false;
		st._id = Math.floor(1000 * Math.random());

		/*# config.js #*/
		
		/*# amd.js #*/

		/*# static.js #*/

		/*# packages.js #*/

		var Module = moduleManager(st, modules, interactives, config);
		resources  = Module.modules; 

		/*# shim.js #*/

		/*# startup.js #*/

		/*# interactive.js #*/

		// Use config.on to listen on changes in config. We primarily use this
		// to update resources' paths when stealconfig.js is loaded.
		config.on(function(configData){
			h.each(resources, function( id, resource ) {
				resource.rewriteIdAndUpdateOptions(id);
			});
			// set up shims after ids are updated
			if(configData.shim){
				st.setupShims(configData.shim)
			}
		})

		st.File = st.URI = URI;

		// if this is a first steal context in the page
		// we need to set up the `steal` module so we would 
		// know steal was loaded.
		if(kickoff){
			var stealModule = new Module({id:"steal"})
			stealModule.value = st;
			stealModule.loaded.resolve();
			stealModule.run.resolve();
			stealModule.executing = true;
			stealModule.completed.resolve();
			resources[stealModule.options.id] = stealModule;
		}

		startup();
		st.resources = resources;
		st.Module = Module;

		return st;
	}
	// create initial steal instance
	stealManager(true, new ConfigManager(typeof h.win.steal == "object" ? h.win.steal : {}), true)

})();
