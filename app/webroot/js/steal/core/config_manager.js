/**
 * `new ConfigManager(config)` creates configuration profile for the steal context.
 * It keeps all config parameters in the instance which allows steal to clone it's 
 * context.
 *
 * config.stealConfig is tipically set up in __stealconfig.js__.  The available options are:
 * 
 *  - map - map an id to another id
 *  - paths - maps an id to a file
 *  - root - the path to the "root" folder
 *  - env - `"development"` or `"production"`
 *  - types - processor rules for various types
 *  - ext - behavior rules for extensions
 *  - urlArgs - extra queryString arguments
 *  - startFile - the file to load
 * 
 * ## map
 * 
 * Maps an id to another id with a certain scope of other ids. This can be
 * used to use different modules within the same id or map ids to another id.
 * Example:
 * 
 *     st.config({
 *       map: {
 *         "*": {
 *           "jquery/jquery.js": "jquery"
 *         },
 *         "compontent1":{
 *           "underscore" : "underscore1.2"
 *         },
 *         "component2":{
 *           "underscore" : "underscore1.1"  
 *         }
 *       }
 *     })
 * 
 * ## paths
 * 
 * Maps an id or matching ids to a url. Each mapping is specified
 * by an id or part of the id to match and what that 
 * part should be replaced with.
 * 
 *     st.config({
 *       paths: {
 * 	       // maps everything in a jquery folder like: `jquery/controller`
 *         // to http://cdn.com/jquery/controller/controller.com
 * 	       "jquery/" : "http://cdn.com/jquery/"
 * 
 *         // if path does not end with /, it matches only that id
 *         "jquery" : "https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"
 *       }
 *     }) 
 * 
 * ## root
 * ## env
 * 
 * If production, does not load "ignored" scripts and loads production script.  If development gives more warnings / errors.
 * 
 * ## types
 * 
 * The types option can specify how a type is loaded. 
 * 
 * ## ext
 * 
 * The ext option specifies the default behavior if file is loaded with the 
 * specified extension. For a given extension, a file that configures the type can be given or
 * an existing type. For example, for ejs:
 * 
 *     st.config({ext: {"ejs": "can/view/ejs/ejs.js"}})
 * 
 * This tells steal to make sure `can/view/ejs/ejs.js` is executed before any file with
 * ".ejs" is executed.
 * 
 * 
 **/



var ConfigManager = function(options){
	this.stealConfig = {};
	this.callbacks = [];
	this.attr(ConfigManager.defaults);
	this.attr(options)
}

h.extend(ConfigManager.prototype, {
	// get or set config.stealConfig attributes
	attr: function( config ) {
		if(!config){ // called as a getter, so just return
			return this.stealConfig;
		}
		if(arguments.length === 1 && typeof config === "string"){ // called as a getter, so just return
			return this.stealConfig && this.stealConfig[config];
		}
		this.stealConfig = this.stealConfig || {};
		for(var prop in config){
			var value = config[prop];
			// if it's a special function
			this[prop] ?
				// run it
				this[prop](value) :
				// otherwise set or extend
				(typeof value == "object" && this.stealConfig[prop] ?
					// extend
					h.extend( this.stealConfig[prop], value) :
					// set
					this.stealConfig[prop] = value);
				
		}

		for(var i = 0; i < this.callbacks.length; i++){
			this.callbacks[i](this.stealConfig)
		}
		
		return this;
	},
	
	// add callbacks which are called after config is changed
	on: function(cb){
		this.callbacks.push(cb)
	},

	// get the current start file
	startFile: function(startFile){
		// make sure startFile and production look right
		this.stealConfig.startFile = "" + URI(startFile).addJS()
		if (!this.stealConfig.production ) {
			this.stealConfig.production = URI(this.stealConfig.startFile).dir() + "/production.js";
		}
	},

	/**
	 *
	 * Read or define the path relative URI's should be referenced from.
	 * 
	 *     window.location //-> "http://foo.com/site/index.html"
	 *     st.URI.root("http://foo.com/app/files/")
	 *     st.root.toString() //-> "../../app/files/"
	 */
	root: function( relativeURI ) {
		if ( relativeURI !== undefined ) {
			var root = URI(relativeURI);

			// the current folder-location of the page http://foo.com/bar/card
			var cleaned = URI.page,
				// the absolute location or root
				loc = cleaned.join(relativeURI);

			// cur now points to the 'root' location, but from the page
			URI.cur = loc.pathTo(cleaned)
			this.stealConfig.root = root;
			return this;
		}
		this.stealConfig.root =  root || URI("");
	},
	//var stealConfig = configs[configContext];
	cloneContext: function(){
		return new ConfigManager( h.extend( {}, this.stealConfig ) );
	}
})
// ConfigManager's defaults
ConfigManager.defaults = {
	types: {},
	ext: {},
	env: "development",
	loadProduction: true,
	logLevel: 0,
	root: "",
	amd: false
};