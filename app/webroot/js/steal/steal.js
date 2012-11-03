// steal is a resource loader for JavaScript.  It is broken into the following parts:
//
// - Helpers - basic utility methods used internally
// - AOP - aspect oriented code helpers
// - Deferred - a minimal deferred implementation
// - Uri - methods for dealing with urls
// - Api - steal's API
// - Resource - an object that represents a resource that is loaded and run and has dependencies.
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
(function( win, undefined ) {

	// ## Helpers ##
	// The following are a list of helper methods used internally to steal
	var
	// check that we have a document
	doc = win.document,
		docEl = doc && doc.documentElement,
		// a jQuery-like $.each
		each = function( o, cb ) {
			var i, len;

			// weak array detection, but we only use this internally so don't
			// pass it weird stuff
			if ( typeof o.length == 'number' ) {
				for ( i = 0, len = o.length; i < len; i++ ) {
					cb.call(o[i], i, o[i], o)
				}
			} else {
				for ( i in o ) {
					cb.call(o[i], i, o[i], o)
				}
			}
			return o;
		},
		// adds the item to the array only if it doesn't currently exist
		uniquePush = function(arr, item){
			for(var i=0; i < arr.length; i++){
				if(arr[i] == item){
					return;
				}
			}
			arr.push(item)
		},
		// if o is a string
		isString = function( o ) {
			return typeof o == "string";
		},
		// if o is a function
		isFn = function( o ) {
			return typeof o == "function";
		},
		// dummy function
		noop = function() {},
		// creates an element
		createElement = function( nodeName ) {
			return doc.createElement(nodeName)
		},
		// creates a script tag
		scriptTag = function() {
			var start = createElement("script");
			start.type = "text/javascript";
			return start;
		},
		// minify-able verstion of getElementsByTagName
		getElementsByTagName = function( tag ) {
			return doc.getElementsByTagName(tag);
		},
		// A function that returns the head element
		// creates and caches the lookup for faster
		// performance.
		head = function() {
			var hd = getElementsByTagName("head")[0];
			if (!hd ) {
				hd = createElement("head");
				docEl.insertBefore(hd, docEl.firstChild);
			}
			// replace head so it runs fast next time.
			head = function() {
				return hd;
			}
			return hd;
		},
		// extends one object with another
		extend = function( d, s ) {
			// only extend if we have something to extend
			s && each(s, function( k ) {
				d[k] = s[k];
			});
			return d;
		},
		// makes an array of things, or a mapping of things
		map = function( args, cb ) {
			var arr = [];
			each(args, function( i, str ) {
				arr.push(cb ? (isString(cb) ? str[cb] : cb.call(str, str)) : str)
			});
			return arr;
		},
		// testing support for various browser behaviors
		support = {
			// does onerror work in script tags?
			error: doc && (function() {
				var script = scriptTag();
				script.onerror = noop;
				return isFn(script.onerror) || "onerror" in script
			})(),
			// If scripts support interactive ready state.
			// This is tested later.
			interactive: false,
			// use attachEvent for event listening (IE)
			attachEvent: doc && scriptTag().attachEvent
		},
		// a startup function that will be called when steal is ready
		startup = noop,
		// if oldsteal is an object
		// we use it as options to configure steal
		opts = typeof win.steal == "object" ? win.steal : {},
		// adds a suffix to the url for cache busting
		addSuffix = function( str ) {
			if ( opts.suffix ) {
				str = (str + '').indexOf('?') > -1 ? str + "&" + opts.suffix : str + "?" + opts.suffix;
			}
			return str;
		},
		endsInSlashRegex = /\/$/;
		
		
		// ## AOP ##
	// Aspect oriented programming helper methods are used to
	// weave in functionality into steal's API.
	// calls `before` before `f` is called.
	//     steal.complete = before(steal.complete, f)
	// `changeArgs=true` makes before return the same args
	function before(f, before, changeArgs) {
		return changeArgs ?
		function before_changeArgs() {
			return f.apply(this, before.apply(this, arguments));
		} : function before_args() {
			before.apply(this, arguments);
			return f.apply(this, arguments);
		}
	}
	// returns a function that calls `after` 
	// after `f`
	function after(f, after, changeRet) {
		return changeRet ?
		function after_CRet() {
			return after.apply(this, [f.apply(this, arguments)].concat(map(arguments)));
		} : function after_Ret() {
			var ret = f.apply(this, arguments);
			after.apply(this, arguments);
			return ret;
		}
	}


	// ## Deferred .63
	var Deferred = function( func ) {
		if (!(this instanceof Deferred)) return new Deferred();

		this.doneFuncs = [];
		this.failFuncs = [];
		this.resultArgs = null;
		this.status = "";

		// check for option function: call it with this as context and as first
		// parameter, as specified in jQuery api
		func && func.call(this, this);
	}

	Deferred.when = function() {
		var args = map(arguments);
		if ( args.length < 2 ) {
			var obj = args[0];
			if ( obj && (isFn(obj.isResolved) && isFn(obj.isRejected)) ) {
				return obj;
			} else {
				return Deferred().resolve(obj);
			}
		} else {

			var df = Deferred(),
				done = 0,
				// resolve params: params of each resolve, we need to track down
				// them to be able to pass them in the correct order if the master
				// needs to be resolved
				rp = [];

			each(args, function( j, arg ) {
				arg.done(function() {
					rp[j] = (arguments.length < 2) ? arguments[0] : arguments;
					if (++done == args.length ) {
						df.resolve.apply(df, rp);
					}
				}).fail(function() {
					df.reject(arguments);
				});
			});

			return df;

		}
	}

	var resolveFunc = function( type, status ) {
		return function( context ) {
			var args = this.resultArgs = (arguments.length > 1) ? arguments[1] : [];
			return this.exec(context, this[type], args, status);
		}
	},
		doneFunc = function( type, status ) {
			return function() {
				var self = this;
				each(arguments, function( i, v, args ) {
					if (!v ) return;
					if ( v.constructor === Array ) {
						args.callee.apply(self, v)
					} else {
						// immediately call the function if the deferred has been resolved
						if ( self.status === status ) v.apply(this, self.resultArgs || []);

						self[type].push(v);
					}
				});
				return this;
			}
		};

	extend(Deferred.prototype, {
		resolveWith: resolveFunc("doneFuncs", "rs"),
		rejectWith: resolveFunc("failFuncs", "rj"),
		done: doneFunc("doneFuncs", "rs"),
		fail: doneFunc("failFuncs", "rj"),
		always: function() {
			var args = map(arguments);
			if ( args.length && args[0] ) this.done(args[0]).fail(args[0]);

			return this;
		},
		then: function() {
			var args = map(arguments);
			// fail function(s)
			if ( args.length > 1 && args[1] ) this.fail(args[1]);

			// done function(s)
			if ( args.length && args[0] ) this.done(args[0]);

			return this;
		},
		isResolved: function() {
			return this.status === "rs";
		},
		isRejected: function() {
			return this.status === "rj";
		},
		reject: function() {
			return this.rejectWith(this, arguments);
		},
		resolve: function() {
			return this.resolveWith(this, arguments);
		},
		exec: function( context, dst, args, st ) {
			if ( this.status !== "" ) return this;

			this.status = st;

			each(dst, function( i, d ) {
				d.apply(context, args);
			});

			return this;
		}
	});
	// ## HELPER METHODS FOR DEFERREDS
	// Used to call a method on an object or resolve a
	// deferred on it when a group of deferreds is resolved.
	//
	//     whenEach(resources,"complete",resource,"execute")
	var whenEach = function( arr, func, obj, func2 ) {
		
		var deferreds = map(arr, func)
		return Deferred.when.apply(Deferred, deferreds).then(function() {
			if ( isFn(obj[func2]) ) {
				obj[func2]()
			} else {
				obj[func2].resolve();
			}

		});
	};

	// ## URI ##
	/**
	 * @class steal.URI
	 * A URL / URI helper for getting information from a URL.
	 * 
	 *     var uri = URI( "http://stealjs.com/index.html" )
	 *     uri.path //-> "/index.html"
	 */
	var URI = function( url ) {
		if ( this.constructor !== URI ) {
			return new URI(url);
		}
		extend(this, URI.parse("" + url));
	};
	// the current url (relative to root, which is relative from page)
	// normalize joins from this
	//
	extend(URI, {
		// parses a URI into it's basic parts
		parse: function( string ) {
			var uriParts = string.split("?"),
				uri = uriParts.shift(),
				queryParts = uriParts.join("").split("#"),
				protoParts = uri.split("://"),
				parts = {
					query: queryParts.shift(),
					fragment: queryParts.join("#")
				},
				pathParts;

			if ( protoParts[1] ) {
				parts.protocol = protoParts.shift();
				pathParts = protoParts[0].split("/");
				parts.host = pathParts.shift();
				parts.path = "/" + pathParts.join("/");
			} else {
				parts.path = protoParts[0];
			}
			return parts;
		}
	});
	/**
	 * @attribute page
	 * The location of the page as a URI.
	 * 
	 *     steal.URI.page.protocol //-> "http"
	 */
	URI.page = URI(win.location && location.href);
	/**
	 * @attribute cur
	 * 
	 * The current working directory / path.  Anything
	 * loaded relative will be loaded relative to this.
	 */
	URI.cur = URI();

	/**
	 * @prototype
	 */
	extend(URI.prototype, {
		dir: function() {
			var parts = this.path.split("/");
			parts.pop();
			return URI(this.domain() + parts.join("/"))
		},
		filename: function() {
			return this.path.split("/").pop();
		},
		ext: function() {
			var filename = this.filename();
			return~filename.indexOf(".") ? filename.split(".").pop() : "";
		},
		domain: function() {
			return this.protocol ? this.protocol + "://" + this.host : "";
		},
		isCrossDomain: function( uri ) {
			uri = URI(uri || win.location.href);
			var domain = this.domain(),
				uriDomain = uri.domain()
				return (domain && uriDomain && domain != uriDomain) || this.protocol === "file" || (domain && !uriDomain);
		},
		isRelativeToDomain: function() {
			return !this.path.indexOf("/");
		},
		hash: function() {
			return this.fragment ? "#" + this.fragment : ""
		},
		search: function() {
			return this.query ? "?" + this.query : ""
		},
		// like join, but returns a string
		add: function( uri ) {
			return this.join(uri) + '';
		},
		join: function( uri, min ) {
			uri = URI(uri);
			if ( uri.isCrossDomain(this) ) {
				return uri;
			}
			if ( uri.isRelativeToDomain() ) {
				return URI(this.domain() + uri)
			}
			// at this point we either
			// - have the same domain
			// - this has a domain but uri does not
			// - both don't have domains
			var left = this.path ? this.path.split("/") : [],
				right = uri.path.split("/"),
				part = right[0];
			//if we are joining from a folder like cookbook/, remove the last empty part
			if ( this.path.match(/\/$/) ) {
				left.pop();
			}
			while ( part == ".." && left.length ) {
				// if we've emptied out, folders, just break
				// leaving any additional ../s
				if (!left.pop() ) {
					break;
				}
				right.shift();

				part = right[0];
			}
			return extend(URI(this.domain() + left.concat(right).join("/")), {
				query: uri.query
			});
		},
		/**
		 * For a given path, a given working directory, and file location, update the
		 * path so it points to a location relative to steal's root.
		 *
		 * We want everything relative to steal's root so the same app can work in
		 * multiple pages.
		 *
		 * ./files/a.js = steals a.js
		 * ./files/a = a/a.js
		 * files/a = //files/a/a.js
		 * files/a.js = loads //files/a.js
		 */
		normalize: function( cur ) {
			cur = cur ? cur.dir() : URI.cur.dir();
			var path = this.path,
				res = URI(path);
			//if path is rooted from steal's root (DEPRECATED)
			if (!path.indexOf("//") ) {
				res = URI(path.substr(2));
			} else if (!path.indexOf("./") ) { // should be relative
				res = cur.join(path.substr(2));
			}
			// only if we start with ./ or have a /foo should we join from cur
			else if ( this.isRelative() ) {
				res = cur.join(this.domain() + path)
			}
			res.query = this.query;
			return res;
		},
		isRelative: function() {
			return /^[\.|\/]/.test(this.path)
		},
		// a min path from 2 urls that share the same domain
		pathTo: function( uri ) {
			uri = URI(uri);
			var uriParts = uri.path.split("/"),
				thisParts = this.path.split("/"),
				result = [];
			while ( uriParts.length && thisParts.length && uriParts[0] == thisParts[0] ) {
				uriParts.shift();
				thisParts.shift();
			}
			each(thisParts, function() {
				result.push("../")
			})
			return URI(result.join("") + uriParts.join("/"));
		},
		mapJoin: function( url ) {
			return this.join(URI(url).insertMapping());
		},
		// helper to go from jquery to jquery/jquery.js
		addJS: function() {
			var ext = this.ext();
			if (!ext ) {
				// if first character of path is a . or /, just load this file
				if (!this.isRelative() ) {
					this.path += "/" + this.filename();
				}
				this.path += ".js"
			}
			return this;
		}
	});
		
	// create the steal function now to use as a namespace.


	function steal() {
		// convert arguments into an array
		var args = map(arguments);
		if ( args.length ) {
			pending.push.apply(pending, args);
			// steal.after is called everytime steal is called
			// it kicks off loading these files
			steal.after(args);
			// return steal for chaining
		}
		return steal;
	};
	steal._id = Math.floor(1000 * Math.random());
	// ## CONFIG ##
	
	// stores the current config settings
	var stealConfig = {
		types: {},
		ext: {},
		env: "development",
		loadProduction: true,
		logLevel: 0
	},
	
		matchesId = function( loc, id ) {
			if ( loc === "*" ) {
				return true;
			} else if ( id.indexOf(loc) === 0 ) {
				return true;
			}
		};
	/**
	 * `steal.config(config)` configures steal. Typically it it used
	 * in __stealconfig.js__.  The available options are:
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
	 *     steal.config({
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
	 *     steal.config({
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
	 *     steal.config({ext: {"ejs": "can/view/ejs/ejs.js"}})
	 * 
	 * This tells steal to make sure `can/view/ejs/ejs.js` is executed before any file with
	 * ".ejs" is executed.
	 * 
	 * ## startFile
	 */
	steal.config = function( config ) {
		if(!config){ // called as a getter, so just return
			return stealConfig;
		}
		if(arguments.length === 1 && typeof config === "string"){ // called as a getter, so just return
			return stealConfig[config];
		}
		for(var prop in config){
			var value = config[prop]
			// if it's a special function
			steal.config[prop] ?
				// run it
				steal.config[prop](value) :
				// otherwise set or extend
				(typeof value == "object" && stealConfig[prop] ?
					// extend
					extend( stealConfig[prop], value) :
					// set
					stealConfig[prop] = value);
				
		}
		// redo all resources
		each(resources, function( id, resource ) {
			if ( resource.options.type != "fn" ) {
				// TODO this is terrible
				var buildType = resource.options.buildType;
				resource.setOptions(resource.orig);
				var newId = resource.options.id;
				// this mapping is to move a config'd key
				if ( id !== newId ) {
					resources[newId] = resource;
					// TODO: remove the old one ....
				}
				resource.options.buildType = buildType;
			}
		})
		return stealConfig;
	};
	steal.config.startFile = function(startFile){
		// make sure startFile and production look right
		stealConfig.startFile = "" + URI(startFile).addJS()
		if (!stealConfig.production ) {
			stealConfig.production = URI(stealConfig.startFile).dir() + "/production.js";
		}
		
	}
	/**
	 * Read or define the path relative URI's should be referenced from.
	 * 
	 *     window.location //-> "http://foo.com/site/index.html"
	 *     steal.URI.root("http://foo.com/app/files/")
	 *     steal.root.toString() //-> "../../app/files/"
	 */
	steal.config.root = function( relativeURI ) {
		if ( relativeURI !== undefined ) {
			var root = URI(relativeURI);

			// the current folder-location of the page http://foo.com/bar/card
			var cleaned = URI.page,
				// the absolute location or root
				loc = cleaned.join(relativeURI);

			// cur now points to the 'root' location, but from the page
			URI.cur = loc.pathTo(cleaned)
			stealConfig.root = root;
			return;
		}
		stealConfig.root =  root || URI("");
	}
	steal.config.root("");
	/**
	 * @function steal.id
	 * 
	 * Given a resource id passed to `steal( resourceID, currentWorkingId )`, this function converts it to the 
	 * final, unique id. This function can be overwritten 
	 * to change how unique ids are defined, for example, to be more AMD-like.
	 * 
	 * The following are the default rules.
	 * 
	 * Given an ID:
	 * 
	 *  1. Check the id has an extension like _.js_ or _.customext_. If it doesn't:
	 *      1. Check if the id is relative, meaning it starts with _../_ or _./_. If it is not, add 
	 *         "/" plus everything after the last "/". So `foo/bar` becomes `foo/bar/bar`
	 *      2. Add .js to the id.
	 *  2. Check if the id is relative, meaning it starts with _../_ or _./_. If it is relative,
	 *     set the id to the id joined from the currentWorkingId.
	 *  3. Check the 
	 * 
	 * 
	 * `steal.id()`
	 */
	// returns the "rootSrc" id, something that looks like requireJS
	// for a given id/path, what is the "REAL" id that should be used
	// this is where substituation can happen
	steal.id = function( id, currentWorkingId, type ) {
		// id should be like
		var uri = URI(id);
		uri = uri.addJS().normalize(currentWorkingId ? new URI(currentWorkingId) : null)
		// check foo/bar
		if (!type ) {
			type = "js"
		}
		if ( type == "js" ) {
			// if it ends with .js remove it ...
			// if it ends
		}
		// check map config
		var map = stealConfig.map || {};
		// always run past 
		each(map, function( loc, maps ) {
			// is the current working id matching loc
			if ( matchesId(loc, currentWorkingId) ) {
				// run maps
				each(maps, function( part, replaceWith ) {
					if (("" + uri).indexOf(part) == 0 ) {
						uri = URI(("" + uri).replace(part, replaceWith))
					}
				})
			}
		})
		
		return uri;
	}
	
	steal.amdToId = function(id, currentWorkingId, type){
		var uri = URI(id);
		uri = uri.normalize(currentWorkingId ? new URI(currentWorkingId) : null)
		// check foo/bar
		if (!type ) {
			type = "js"
		}
		if ( type == "js" ) {
			// if it ends with .js remove it ...
			// if it ends
		}
		// check map config
		var map = stealConfig.map || {};
		// always run past 
		each(map, function( loc, maps ) {
			// is the current working id matching loc
			if ( matchesId(loc, currentWorkingId) ) {
				// run maps
				each(maps, function( part, replaceWith ) {
					if (("" + uri).indexOf(part) == 0 ) {
						uri = URI(("" + uri).replace(part, replaceWith))
					}
				})
			}
		})
		return uri;
	}


	steal.config.shim = function(shims){

		for(var id in shims){
			var resource = Resource.make(id);
			if(typeof shims[id] === "object"){
				var needs = shims[id].deps || []
				if(typeof shims[id].exports === "string"){
					var exports = (function(_exports){
						return function(){
							return win[_exports];
						}
					})(shims[id].exports)
				} else {
					exports = shims[id].exports;
				}
			} else {
				needs = shims[id];
			}
			(function(_resource, _needs){
				_resource.options.needs = _needs;
			})(resource, needs)

			if(exports){
				resource.exports = (function(_resource, _exports, _needs){
					return function(){
						var args = _needs.map(function(id){
							return Resource.make(id).value;
						})
						_resource.value = _exports.apply(null, args)
						return _resource.value
					}
				})(resource, exports, needs)
			}
		}

	}

	// for a given ID, where should I find this resource
	/**
	 * `steal.idToUri( id, noJoin )` takes an id and returns a URI that
	 * is the location of the file. It uses the paths option of  [steal.config].
	 * Passing true for `noJoin` does not join from the root URI.
	 */
	steal.idToUri = function( id, noJoin ) {
		// this is normalize
		var paths = stealConfig.paths || {},
			path;
		// always run past 
		each(paths, function( part, replaceWith ) {
			path = ""+id;
			// if path ends in / only check first part of id
			if((endsInSlashRegex.test(part) && path.indexOf(part) == 0) ||
				// or check if its a full match only
				path === part){
				id = URI(path.replace(part, replaceWith));
			}
		})

		return noJoin ? id : stealConfig.root.join(id)
	}
	steal.amdIdToUri = function( id, noJoin ){
		// this is normalize
		var paths = stealConfig.paths || {},
			path;
		// always run past 
		each(paths, function( part, replaceWith ) {
			path = ""+id;
			// if path ends in / only check first part of id
			if((endsInSlashRegex.test(part) && path.indexOf(part) == 0) ||
				// or check if its a full match only
				path === part){
				id = URI(path.replace(part, replaceWith));
			}
		})
		if( /(^|\/)[^\/\.]+$/.test(id) ){
			id= URI(id+".js")
		}
		return id //noJoin ? id : stealConfig.root.join(id)
	}



	// This can't be added to the prototype using extend because
	// then for some reason IE < 9 won't recognize it.
	URI.prototype.toString = function() {
		return this.domain() + this.path + this.search() + this.hash();
	};

	// temp add steal.File for backward compat
	steal.File = steal.URI = URI;
	// --- END URI
	var pending = [],
		s = steal,
		id = 0;


	/**
	 * @add steal
	 */
	// =============================== STATIC API ===============================
	var page;

	extend(steal, {
		each: each,
		extend: extend,
		Deferred: Deferred,
		// Currently used a few places
		isRhino: win.load && win.readUrl && win.readFile,
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
			options.id = options.toId ? options.toId(options.id, curId) : steal.id(options.id, curId);
			// set the ext
			options.ext = options.id.ext();
			
			// Check if it's a configured needs
			var configedExt = stealConfig.ext[options.ext];
			// if we have something, but it's not a type
			if ( configedExt && ! stealConfig.types[configedExt] ) {
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
		 * files passed to the arguments.
		 */
		then: function() {
			var args = map(arguments);
			args.unshift(null)
			return steal.apply(win, args);
		},
		/**
		 * `steal.bind( event, handler(eventData...) )` listens to 
		 * events on steal. Typically these are used by various build processes
		 * to know when steal starts and finish loading resources and their
		 * dependencies. Listen to an event like:
		 * 
		 *     steal.bind('end', function(rootResource){
		 *       rootResource.dependencies // the first stolen resources.
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
			var special = steal.events[event]
			if ( special && special.add ) {
				listener = special.add(listener);
			}
			listener && events[event].push(listener);
			return steal;
		},
		/**
		 * `steal.one(eventName, handler(eventArgs...) )` works just like
		 * [steal.bind] but immediately unbinds after `handler` is called.
		 */
		one: function( event, listener ) {
			return steal.bind(event, function() {
				listener.apply(this, arguments);
				steal.unbind(event, arguments.callee);
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
			each(map(arr), function( i, f ) {
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
			support.interactive = false;
			each(arguments, function( i, arg ) {
				var stel = Resource.make(arg);
				stel.loading = stel.executing = true;
			});
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
			var resource = Resource.make(name);
			resource.loading = resource.executing = true;
			//convert(stel, "complete");
			steal.preexecuted(resource);
			resource.executed()
			return steal;
		},
		type: function( type, cb ) {
			var typs = type.split(" ");

			if (!cb ) {
				return types[typs.shift()].require
			}

			types[typs.shift()] = {
				require: cb,
				convert: typs
			};
		}
	});


	// ============ RESOURCE ================
	// a map of resources by resourceID
	var resources = {};
	// this is for methods on a 'steal instance'.  A file can be in one of a few states:
	// created - the steal instance is created, but we haven't started loading it yet
	//           this happens when thens are used
	// loading - (loading=true) By calling load, this will tell steal to load a file
	// loaded - (isLoaded=true) The file has been run, but its dependency files have been completed
	// complete - all of this files dependencies have loaded and completed.

	// A Resource is almost anything. It is different from a module
	// as it doesn't represent some unit of functionality, rather
	// it represents a unit that can have other units "within" it
	// as dependencies.  A resource can:
	//
	// - load - load the resource to the client so it is available, but don't run it yet
	// - run - run the code for the resource
	// - executed - the code has been run for the resource, but all
	//   dependencies for that resource might not have finished
	// - completed - all resources within the resource have completed
	//
	// __options__
	// `options` can be a string, function, or object.
	//
	// __properties__
	//
	// - options - has a number of properties
	//    - src - a URI to this resource that can be loaded from the current page
	//    - rootSrc - a URI to this resource relative to the current root URI.
	//    - type - the type of resource: "fn", "js", "css", etc
	//    - needs - other resources that must be loaded prior to this resource
	//    - fn - a callback function to run when executed
	// - unique - false if this resource should be loaded each time
	// - waits - this resource should wait until all prior scripts have completed before running
	// - loaded - a deferred indicating if this resource has been loaded to the client
	// - run - a deferred indicating if the the code for this resource run
	// - completed - a deferred indicating if all of this resources dependencies have
	//   completed
	// - dependencies - an array of dependencies
	var Resource = function( options ) {
		// an array for dependencies, this is the steal calls this resource makes
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
	// `Resource.make` is used to either create
	// a new resource, or return an existing
	// resource that matches the options.
	Resource.make = function( options ) {
		// create the temporary reasource
		var resource = new Resource(options),
			// use `rootSrc` as the definitive ID
			id = resource.options.id;

		// assuming this resource should not be created again.
		if ( resource.unique && id ) {

			// Check if we already have a resource for this rootSrc
			// Also check with a .js ending because we defer 'type'
			// determination until later
			if (!resources[id] && !resources[id + ".js"] ) {
				// If we haven't loaded, cache the resource
				resources[id] = resource;
			} else {

				// Otherwise get the cached resource
				existingResource = resources[id];
				// If options were passed, copy new properties over.
				// Don't copy src, etc because those have already
				// been changed to be the right values;
				if (!isString(options) ) {
					// extend everything other than id
					for(var prop in options){
						if(prop !== "id") {
							existingResource.options[prop] = options[prop];
						}
					}
				}
				return existingResource;
			}
		}

		return resource;
	};

	// updates the paths of things ...
	// use modules b/c they are more fuzzy
	// a module's id stays the same, but a path might change
	// 
	Resource.update = function() {
		for ( var rootSrc in resources ) {
			if (!resources[resources].loaded.isResolved() ) {

			}
		}
	};

	extend(Resource.prototype, {
		setOptions: function( options ) {
			var prevOptions = this.options; 
			// if we have no options, we are the global Resource that
			// contains all other resources.
			if (!options ) { //global init cur ...
				this.options = {};
				this.waits = false;
			}
			//handle callback functions
			else if ( isFn(options) ) {
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
								// We need to access the stored modules in this order
								// - calculated id
								// - original name
								// - dependency return value otherwise
								value = modules[dep.options.id] || modules[dep.orig] || dep.value;
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
				this.options = steal.makeOptions(extend({}, isString(options) ? {
					id: options
				} : options), this.curId);

				this.waits = this.options.waits || false;
				this.unique = true;
			}
			// if there are other options we haven't already set, reuse the old ones
			for(opt in prevOptions){
				if(!this.options[opt]){
					this.options[opt] = prevOptions[opt];
				}
			}
		},
		
		// Calling complete indicates that all dependencies have
		// been completed for this resource
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
			// set this as the current resource
			steal.cur = this;

			// mark yourself as 'loaded'.
			this.run.resolve();

			// If we are IE, get the queue from interactives.
			// It in interactives because you can't use onload to know
			// which script is executing.
			if ( support.interactive && src ) {
				myqueue = interactives[src];
			}
			// In other browsers, the queue of items to load is
			// what is in pending
			if (!myqueue ) {
				myqueue = pending.slice(0);
				pending = [];
			}

			// if we have nothing, mark us as complete
			if (!myqueue.length ) {
				this.complete();
				return;
			}
			//print("-setting up "+this.options.id)
			// now we have to figure out how to wire up our pending steals
			var self = this,
				// the current
				isProduction = stealConfig.env == "production",

				stealInstances = [];

			// iterate through the collection and add all the 'needs'
			// before fetching...
			each(myqueue, function( i, item ) {
				if( item === null){
					stealInstances.push(null);
					return
				}
				
				if ( (isProduction && item.ignore) || (!isProduction && !steal.isRhino && item.prodonly)) {
					return;
				}
				
				// make a steal object
				var stel = Resource.make(item);
				if ( packHash[stel.options.id] && stel.options.type !== 'fn' ) { // if we are production, and this is a package, mark as loading, but steal package?
					steal.has(""+stel.options.id);
					stel = Resource.make(packHash[""+stel.options.id]);
				}
				// has to happen before 'needs' for when reversed...
				stealInstances.push(stel);
			});
			//print("-instances "+this.options.id)
			// The set of resources before the previous "wait" resource
			var priorSet = [],
				// The current set of resources after and including the
				// previous "wait" resource
				set = [],
				// The first set of resources that we will execute
				// right away. This should be the first set of dependencies
				// that we can load in parallel. If something has
				// a need, the need should be in this set
				firstSet = [],
				// Should we be adding resources to the
				// firstSet
				setFirstSet = true;

			// Goes through each resource and maintains
			// a list of the set of resources
			// that must be complete before the current
			// resource (`priorSet`).
			each( stealInstances, function( i, resource ) {
				// add it as a dependency, circular are not allowed
				self.dependencies.push(resource);

				// if there's a wait and it's not the first thing
				if ( (resource === null || resource.waits) && set.length ) {
					// add the current set to `priorSet`
					priorSet = priorSet.concat(set);
					// empty the current set
					set = [];
					// we have our firs set of items
					setFirstSet = false;
					if(resource === null) {
						return;
					}

				}
				if ( resource === null ) return;

				// when the priorSet is completed, execute this resource
				// and when it's needs are done
				var waitsOn = priorSet.slice(0);
				// if there are needs, this can not be part of the "firstSet"
				each(resource.options.needs || [], function( i, raw ) {
					
					var need = Resource.make(raw);
					// add the need to the resource's dependencies
					uniquePush(resource.needsDependencies, need);
					waitsOn.push(need);
					// add needs to first set to execute
					firstSet.push(need)
				});
				waitsOn.length && whenEach(waitsOn, "completed", resource, "execute");

				// what is this used for?
				resource.waitedOn = resource.waitedOn ? resource.waitedOn.concat(priorSet) : priorSet.slice(0);

				// add this steal to the current set
				set.push(resource);
				// if we are still on the first set, and this has no needs
				if ( setFirstSet && (resource.options.needs || []).length == 0) {
					// add this to the first set of things
					firstSet.push(resource)
				}
				// start loading the resource if possible
				resource.load();
			});

			// when every thing is complete, mark us as completed
			priorSet = priorSet.concat(set);
			whenEach(priorSet, "completed", self, "completed");

			// execute the first set of dependencies
			each(firstSet, function( i, f ) {
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
			if (!self.loaded.isResolved() ) {
				self.loaded.resolve();
			}
			if (!self.executing ) {
				self.executing = true;

				steal.require(self.options, function( value ) {
					
					self.executed( value );
				}, function( error, src ) {
					var abortFlag = self.options.abort,
						errorCb = self.options.error;

					// if an error callback was provided, fire it
					if ( errorCb ) {
						errorCb.call(self.options);
					}

					win.clearTimeout && win.clearTimeout(self.completeTimeout)

					// if abort: false, register the script as loaded, and don't throw
					if ( abortFlag === false ) {
						self.executed();
						return;
					}
					throw "steal.js : " + self.options.src + " not completed"
				});
			}
		}

	});


	var events = {};



	// ### TYPES ##
	var types = stealConfig.types;
	/**
	 * Registers a type.  You define the type of the file, the basic type it
	 * converts to, and a conversion function where you convert the original file
	 * to JS or CSS.  This is modeled after the
	 * [http://api.jquery.com/extending-ajax/#Converters AJAX converters] in jQuery.
	 *
	 * Types are designed to make it simple to switch between steal's development
	 * and production modes.  In development mode, the types are converted
	 * in the browser to allow devs to see changes as they work.  When the app is
	 * built, these converter functions are run by the build process,
	 * and the processed text is inserted into the production script, optimized for
	 * performance.
	 *
	 * Here's an example converting files of type .foo to JavaScript.  Foo is a
	 * fake language that saves global variables defined like.  A .foo file might
	 * look like this:
	 *
	 *     REQUIRED FOO
	 *
	 * To define this type, you'd call steal.type like this:
	 *
	 *     steal.type("foo js", function(options, original, success, error){
	 *       var parts = options.text.split(" ")
	 *       options.text = parts[0]+"='"+parts[1]+"'";
	 *       success();
	 *     });
	 *
	 * The method we provide is called with the text of .foo files in options.text.
	 * We parse the file, create JavaScript and put it in options.text.  Couldn't
	 * be simpler.
	 *
	 * Here's an example,
	 * converting [http://jashkenas.github.com/coffee-script/ coffeescript]
	 * to JavaScript:
	 *
	 *     steal.type("coffee js", function(options, original, success, error){
	 *       options.text = CoffeeScript.compile(options.text);
	 *       success();
	 *     });
	 *
	 * In this example, any time steal encounters a file with extension .coffee,
	 * it will call the given converter method.  CoffeeScript.compile takes the
	 * text of the file, converts it from coffeescript to javascript, and saves
	 * the JavaScript text in options.text.
	 *
	 * Similarly, languages on top of CSS, like [http://lesscss.org/ LESS], can
	 * be converted to CSS:
	 *
	 *     steal.type("less css", function(options, original, success, error){
	 *       new (less.Parser)({
	 *         optimization: less.optimization,
	 *         paths: []
	 *       }).parse(options.text, function (e, root) {
	 *         options.text = root.toCSS();
	 *         success();
	 *       });
	 *     });
	 *
	 * This simple type system could be used to convert any file type to be used
	 * in your JavaScript app.  For example, [http://fdik.org/yml/ yml] could be
	 * used for configuration.  jQueryMX uses steal.type to support JS templates,
	 * such as EJS, TMPL, and others.
	 *
	 * @param {String} type A string that defines the new type being defined and
	 * the type being converted to, separated by a space, like "coffee js".
	 *
	 * There can be more than two steps used in conversion, such as "ejs view js".
	 * This will define a method that converts .ejs files to .view files.  There
	 * should be another converter for "view js" that makes this final conversion
	 * to JS.
	 *
	 * @param {Function} cb( options, original, success, error ) a callback
	 * function that converts the new file type to a basic type.  This method
	 * needs to do two things: 1) save the text of the converted file in
	 * options.text and 2) call success() when the conversion is done (it can work
	 * asynchronously).
	 *
	 * - __options__ - the steal options for this file, including path information
	 * - __original__ - the original argument passed to steal, which might be a
	 *   path or a function
	 * - __success__ - a method to call when the file is converted and processed
	 *   successfully
	 * - __error__ - a method called if the conversion fails or the file doesn't
	 *   exist
	 */
	steal.config.types = function(types){
		each(types, steal.type)
	};



	// adds a type (js by default) and buildType (css, js)
	// this should happen right before loading
	// however, what if urls are different
	// because one file has JS and another does not?
	// we could check if it matches something with .js because foo.less.js SHOULD
	// be rare
	Resource.prototype.execute = before(Resource.prototype.execute, function() {
		var raw = this.options;

		// if it's a string, get it's extension and check if
		// it is a registered type, if it is ... set the type
		if (!raw.type ) {
			var ext = URI(raw.id).ext();
			if (!ext && !types[ext] ) {
				ext = "js";
			}
			raw.type = ext;
		}
		if (!types[raw.type] && stealConfig.env == 'development' ) {
			throw "steal.js - type " + raw.type + " has not been loaded.";
		} else if (!types[raw.type] && stealConfig.env == 'production' ) {
			// if we haven't defined EJS yet and we're in production, its ok, just ignore it
			return;
		}
		var converters = types[raw.type].convert;
		raw.buildType = converters.length ? converters[converters.length - 1] : raw.type;
	});

	steal.
	/**
	 * Called for every file that is loaded.  It sets up a string of methods called
	 * for each type in the conversion chain and calls each type one by one.
	 *
	 * For example, if the file is a coffeescript file, here's what happens:
	 *
	 *   - The "text" type converter is called first.  This will perform an AJAX
	 *   request for the file and save it in options.text.
	 *   - Then the coffee type converter is called (the user provided method).
	 *   This converts the text from coffeescript to JavaScript.
	 *   - Finally the "js" type converter is called, which inserts the JavaScript
	 *   in the page as a script tag that is executed.
	 *
	 * @param {Object} options the steal options for this file, including path information
	 * @param {Function} success a method to call when the file is converted and processed successfully
	 * @param {Function} error a method called if the conversion fails or the file doesn't exist
	 */
	require = function( options, success, error ) {
		// add the src option
		options.src = options.idToUri ? options.idToUri(options.id) : steal.idToUri(options.id);

		// get the type
		var type = types[options.type],
			converters;

		// if this has converters, make it get the text first, then pass it to the type
		if ( type.convert.length ) {
			converters = type.convert.slice(0);
			converters.unshift("text", options.type)
		} else {
			converters = [options.type]
		}
		require(options, converters, success, error)
	};

	function require(options, converters, success, error) {

		var type = types[converters.shift()];

		type.require(options, function require_continue_check() {
			// if we have more types to convert
			if ( converters.length ) {
				require(options, converters, success, error)
			} else { // otherwise this is the final
				success.apply(this, arguments);
			}
		}, error)
	};


	// =============================== TYPES ===============================
	// a clean up script that prevents memory leaks and removes the
	// script
	var cleanUp = function( elem ) {
		elem.onreadystatechange = elem.onload = elem.onerror = null;

		setTimeout(function() {
			head().removeChild(elem);
		}, 1);
	},
		// the last inserted script, needed for IE
		lastInserted,
		// if the state is done
		stateCheck = /^loade|c|u/;


	var cssCount = 0,
		createSheet = doc && doc.createStyleSheet,
		lastSheet, lastSheetOptions;

	// Apply all the basic types
	steal.config({
		types:{
			"js": function( options, success, error ) {
				// create a script tag
				var script = scriptTag(),
					callback = function() {
						if (!script.readyState || stateCheck.test(script.readyState) ) {
							cleanUp(script);
							success();
						}
					};
	
				// if we have text, just set and insert text
				if ( options.text ) {
					// insert
					script.text = options.text;
	
				} else {
	
					// listen to loaded
					script.onload = script.onreadystatechange = callback;
	
					var src = options.src; //steal.idToUri( options.id );
					// error handling doesn't work on firefox on the filesystem
					if ( support.error && error && src.protocol !== "file" ) {
						script.onerror = error;
					}
					script.src = "" + src;
					//script.src = options.src = addSuffix(options.src);
					//script.async = false;
					script.onSuccess = success;
				}
	
				// insert the script
				lastInserted = script;
				head().insertBefore(script, head().firstChild);
	
				// if text, just call success right away, and clean up
				if ( options.text ) {
					callback();
				}
			},
			"fn": function( options, success ) {
				var ret;
				if (!options.skipCallbacks ) {
					ret = options.fn();
				}
				success(ret);
			},
			"text": function( options, success, error ) {
				steal.request(options, function( text ) {
					options.text = text;
					success(text);
				}, error)
			},
			"css": function( options, success, error ) {
				if ( options.text ) { // less
					var css = createElement("style");
					css.type = "text/css";
					if ( css.styleSheet ) { // IE
						css.styleSheet.cssText = options.text;
					} else {
						(function( node ) {
							if ( css.childNodes.length ) {
								if ( css.firstChild.nodeValue !== node.nodeValue ) {
									css.replaceChild(node, css.firstChild);
								}
							} else {
								css.appendChild(node);
							}
						})(doc.createTextNode(options.text));
					}
					head().appendChild(css);
				} else {
					if ( createSheet ) {
						// IE has a 31 sheet and 31 import per sheet limit
						if (!cssCount++ ) {
							lastSheet = doc.createStyleSheet(addSuffix(options.src));
							lastSheetOptions = options;
						} else {
							var relative = "" + URI(URI(lastSheetOptions.src).dir()).pathTo(options.src);
							lastSheet.addImport(addSuffix(relative));
							if ( cssCount == 30 ) {
								cssCount = 0;
							}
						}
						success();
						return;
					}
	
					options = options || {};
					var link = createElement("link");
					link.rel = options.rel || "stylesheet";
					link.href = addSuffix(options.src);
					link.type = "text/css";
					head().appendChild(link);
				}
	
				success();
			}
		}
	});
	


	// =============================== HELPERS ===============================
	var factory = function() {
		return win.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
	};


	steal.
	/**
	 * Performs an XHR request
	 * @param {Object} options
	 * @param {Function} success
	 * @param {Function} error
	 */
	request = function( options, success, error ) {
		var request = new factory(),
			contentType = (options.contentType || "application/x-www-form-urlencoded; charset=utf-8"),
			clean = function() {
				request = check = clean = null;
			},
			check = function() {
				var status;
				if ( request && request.readyState === 4 ) {
					status = request.status;
					if ( status === 500 || status === 404 || status === 2 || request.status < 0 || (!status && request.responseText === "") ) {
						error && error(request.status);
					} else {
						success(request.responseText);
					}
					clean();
				}
			};
		request.open("GET", options.src + '', !(options.async === false));
		request.setRequestHeader("Content-type", contentType);
		if ( request.overrideMimeType ) {
			request.overrideMimeType(contentType);
		}

		request.onreadystatechange = check;
		try {
			request.send(null);
		}
		catch (e) {
			if ( clean ) {
				console.error(e);
				error && error();
				clean();
			}
		}

	};


	//  ============================== Packages ===============================
	/**
	 * @function steal.packages
	 * `steal.packages( packageIds... )` defines modules for deferred downloading.
	 * 
	 * This is used by the build system to build collections of modules that will be downloaded
	 * after initial page load.
	 * 
	 * For example, an application that wants to progressively load the contents and
	 * dependencies of _login/login.js_, _filemanager/filemanager.js_, and _contacts/contacts.js_,
	 * while immediately loading the current users's data might look like:
	 * 
	 *     steal.packages('login','filemanager','contacts')
	 *     steal('models/user', function(User){
	 * 	   
	 *       // get the current User
	 *       User.findOne({id: "current"}, 
	 * 
	 *         // success - they logged in
	 *         function(user){
	 *           if(window.location.hash == "#filemanager"){
	 *             steal('filemanager')  
	 *           }
	 *         }, 
	 *         // error - they are logged out
	 *         function(){
	 *           steal('login', function(){
	 *             new Login(document.body);
	 *             // preload filemanager
	 * 
	 *           })  
	 *         })
	 *     })
	 * 
	 *
	 * 		steal.packages('tasks','dashboard','fileman');
	 *
	 */
	var packs = [],
		packHash = {};
	steal.packages = function( map ) {

		if (!arguments.length ) {
			return packs;
		} else {
			if ( typeof map == 'string' ) {
				packs.push.apply(packs, arguments);
			} else {
				packHash = map;
			}

			return this;
		}
	};

	

	//  =============================== MAPPING ===============================
	URI.prototype.insertMapping = function() {
		// go through mappings
		var orig = "" + this,
			key, value;
		for ( key in steal.mappings ) {
			value = steal.mappings[key]
			if ( value.test.test(orig) ) {
				return orig.replace(key, value.path);
			}
		}
		return URI(orig);
	};

	// =============================== STARTUP ===============================
	var rootSteal = false;

	// essentially ... we need to know when we are on our first steal
	// then we need to know when the collection of those steals ends ...
	// and, it helps if we use a 'collection' steal because of it's natural
	// use for going through the pending queue
	//
	extend(steal, {
		// modifies src
/*makeOptions : after(steal.makeOptions,function(raw){
			raw.src = URI.root().join(raw.rootSrc = URI( raw.rootSrc ).insertMapping());
		}),*/

		//root mappings to other locations
		mappings: {},

		/**
		 * Maps a 'rooted' folder to another location.
		 * @param {String|Object} from the location you want to map from.  For example:
		 *   'foo/bar'
		 * @param {String} [to] where you want to map this folder too.  Ex: 'http://foo.cdn/bar'
		 * @return {steal}
		 */
		map: function( from, to ) {
			if ( isString(from) ) {
				steal.mappings[from] = {
					test: new RegExp("^(\/?" + from + ")([/.]|$)"),
					path: to
				};
				each(resources, function( id, resource ) {
					if ( resource.options.type != "fn" ) {
						// TODO terrible
						var buildType = resource.options.buildType;
						resource.setOptions(resource.orig);
						resource.options.buildType = buildType;
					}
				})
			} else { // its an object
				each(from, steal.map);
			}
			return this;
		},
		// called after steals are added to the pending queue
		after: function() {
			// if we don't have a current 'top' steal
			// we create one and set it up
			// to start loading its dependencies (the current pending steals)
			if (!rootSteal ) {
				rootSteal = new Resource();
				// keep a reference in case it disappears
				var cur = rootSteal,
					// runs when a steal is starting
					go = function() {
						// indicates that a collection of steals has started
						steal.trigger("start", cur);
						cur.completed.then(function() {

							rootSteal = null;
							steal.trigger("end", cur);


						});

						cur.executed();
					};
				// If we are in the browser, wait a
				// brief timeout before executing the rootResource.
				// This allows embeded script tags with steal to be part of 
				// the initial set
				if ( win.setTimeout ) {
					// we want to insert a "wait" after the current pending
					steal.pushPending();
					setTimeout(function() {
						steal.popPending();
						go();
					}, 0)
				} else {
					// if we are in rhino, start loading dependencies right away
					go()
				}
			}
		},
		_before: before,
		_after: after
	});

	(function(){
		var myPending;
		steal.pushPending = function(){
			myPending = pending.slice(0);
			pending = [];
			each(myPending, function(i, arg){
				Resource.make(arg);
			})
		}
		steal.popPending = function(){
			pending = pending.length ? myPending.concat(null,pending) : myPending;
		}
	})();

	// =============================== jQuery ===============================
	(function() {
		var jQueryIncremented = false,
			jQ, ready = false;

		// check if jQuery loaded after every script load ...
		Resource.prototype.executed = before(Resource.prototype.executed, function() {

			var $ = win.jQuery;
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
		steal.bind("end", function() {
			if ( jQueryIncremented && !ready ) {
				jQ.ready(true);
				ready = true;
			}
		})


	})();

	// =============================== ERROR HANDLING ===============================
	extend(Resource.prototype, {
		load: after(Resource.prototype.load, function( stel ) {
			var self = this;
			if ( doc && !self.completed && !self.completeTimeout && !steal.isRhino && (self.options.src.protocol == "file" || !support.error) ) {
				self.completeTimeout = setTimeout(function() {
					throw "steal.js : " + self.options.src + " not completed"
				}, 5000);
			}
		}),
		complete: after(Resource.prototype.complete, function() {
			this.completeTimeout && clearTimeout(this.completeTimeout)
		}),


		// if we're about to mark a file as executed, mark its "has" array files as
		// executed also
		executed: before(Resource.prototype.executed, function() {
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
			each(this.options.has, function( i, has ) {
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
	Resource.make = after(Resource.make, function( stel ) {
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




	// =========== DEBUG =========


    /*var name = function(stel){
		if(stel.options && stel.options.type == "fn"){
			return stel.orig.name? stel.orig.name : stel.options.id+":fn";//(""+stel.orig).substr(0,10)
		}
		return stel.options ? stel.options.id + "": "CONTAINER"
	}


	//Resource.prototype.load = before( Resource.prototype.load, function(){
	//	console.log("      load", name(this), this.loading, steal._id, this.id)
	//})

	Resource.prototype.executed = before(Resource.prototype.executed, function(){
		var namer= name(this)
		console.log("      executed", namer, steal._id, this.id)
	})
	
	Resource.prototype.complete = before(Resource.prototype.complete, function(){
		console.log("      complete", name(this), steal._id, this.id)
	})*/



	// ============= WINDOW LOAD ========
	var addEvent = function( elem, type, fn ) {
		if ( elem.addEventListener ) {
			elem.addEventListener(type, fn, false);
		} else if ( elem.attachEvent ) {
			elem.attachEvent("on" + type, fn);
		} else {
			fn();
		}
	},
		loaded = {
			load: Deferred(),
			end: Deferred()
		},
		firstEnd = false;

	addEvent(win, "load", function() {
		loaded.load.resolve();
	});

	steal.one("end", function( collection ) {
		loaded.end.resolve(collection);
		firstEnd = collection;
		steal.trigger("done", firstEnd)
	})
	steal.firstComplete = loaded.end;

	Deferred.when(loaded.load, loaded.end).then(function() {
		steal.trigger("ready")
		steal.isReady = true;
	});

	steal.events.done = {
		add: function( cb ) {
			if ( firstEnd ) {
				cb(firstEnd);
				return false;
			} else {
				return cb;
			}
		}
	};



	// =========== INTERACTIVE STUFF ===========
	// Logic that deals with making steal work with IE.  IE executes scripts out of order, so in order to tell which scripts are
	// dependencies of another, steal needs to check which is the currently "interactive" script.
	var interactiveScript,
	// key is script name, value is array of pending items
	interactives = {},
		getInteractiveScript = function() {
			var scripts = getElementsByTagName("script"),
				i = scripts.length;
			while ( i-- ) {
				if ( scripts[i].readyState === "interactive" ) {
					return scripts[i];
				}
			}
		},
		getCachedInteractiveScript = function() {
			if ( interactiveScript && interactiveScript.readyState === 'interactive' ) {
				return interactiveScript;
			}

			if ( interactiveScript = getInteractiveScript() ) {
				return interactiveScript;
			}

			// check last inserted
			if ( lastInserted && lastInserted.readyState == 'interactive' ) {
				return lastInserted;
			}

			return null;
		};


	support.interactive = doc && !! getInteractiveScript();

	if ( support.interactive ) {

		// after steal is called, check which script is "interactive" (for IE)
		steal.after = after(steal.after, function() {

			// check if disabled by steal.loading()
			if (!support.interactive ) {
				return;
			}

			var interactive = getCachedInteractiveScript();
			// if no interactive script, this is a steal coming from inside a steal, let complete handle it
			if (!interactive || !interactive.src || /steal\.(production|production\.[a-zA-Z0-9\-\.\_]*)*js/.test(interactive.src) ) {
				return;
			}
			// get the source of the script
			var src = interactive.src;
			// create an array to hold all steal calls for this script
			if (!interactives[src] ) {
				interactives[src] = []
			}
			// add to the list of steals for this script tag
			if ( src ) {
				interactives[src].push.apply(interactives[src], pending);
				pending = [];
			}
		})

		// This is used for packaged scripts.  As the packaged script executes, we grab the
		// dependencies that have come so far and assign them to the loaded script
		steal.preexecuted = before(steal.preexecuted, function( stel ) {
			// check if disabled by steal.loading()
			if (!support.interactive ) {
				return;
			}

			// get the src name
			var src = stel.options.src,
				// and the src of the current interactive script
				interactiveSrc = getCachedInteractiveScript().src;

			interactives[src] = interactives[interactiveSrc];
			interactives[interactiveSrc] = null;

		})
	}

	
	// ## Config ##
	var stealCheck = /steal\.(production\.)?js.*/,
		getStealScriptSrc = function() {
			if (!doc ) {
				return;
			}
			var scripts = getElementsByTagName("script"),
				script;

			// find the steal script and setup initial paths.
			each(scripts, function( i, s ) {
				if ( stealCheck.test(s.src) ) {
					script = s;
				}
			});
			return script;
		};

	steal.getScriptOptions = function( script ) {

		var options = {},
			parts, src, query, startFile, env;

		script = script || getStealScriptSrc();

		if ( script ) {

			// Split on question mark to get query
			parts = script.src.split("?");
			src = parts.shift();
			query = parts.join("?");

			// Split on comma to get startFile and env
			parts = query.split(",");

			if ( src.indexOf("steal.production") > -1 ) {
				options.env = "production";
			}

			// Grab startFile
			startFile = parts[0];

			if ( startFile ) {
				if ( startFile.indexOf(".js") == -1 ) {
					startFile += "/" + startFile.split("/").pop() + ".js";
				}
				options.startFile = startFile;
			}

			// Grab env
			env = parts[1];

			if ( env ) {
				options.env = env;
			}

			// Split on / to get rootUrl
			parts = src.split("/")
			parts.pop();
			if ( parts[parts.length - 1] == "steal" ) {
				parts.pop();
			}
			options.root = parts.join("/")

		}

		return options;
	};

	startup = after(startup, function() {
		// get options from 
		var options = {};

		// A: GET OPTIONS
		// 1. get script options
		extend(options, steal.getScriptOptions());

		// 2. options from a steal object that existed before this steal
		extend(options, opts);

		// 3. if url looks like steal[xyz]=bar, add those to the options
		// does this ened to be supported anywhere?
		var search = win.location && decodeURIComponent(win.location.search);
		search && search.replace(/steal\[([^\]]+)\]=([^&]+)/g, function( whoe, prop, val ) {
			options[prop] = ~val.indexOf(",") ? val.split(",") : val;
		});

		// B: DO THINGS WITH OPTIONS
		// CALCULATE CURRENT LOCATION OF THINGS ...
		steal.config(options);
		

		// mark things that have already been loaded
		each(options.executed || [], function( i, stel ) {
			steal.executed(stel)
		})
		// immediate steals we do
		var steals = [];

		// add start files first
		if ( options.startFiles ) {
			/// this can be a string or an array
			steals.push.apply(steals, isString(options.startFiles) ? [options.startFiles] : options.startFiles)
			options.startFiles = steals.slice(0)
		}

		// either instrument is in this page (if we're the window opened from
		// steal.browser), or its opener has it
		// try-catching this so we dont have to build up to the iframe
		// instrumentation check
		try {
			// win.top.steal.instrument is for qunit
			// win.top.opener.steal.instrument is for funcunit
			if(!options.browser && ((win.top && win.top.steal.instrument) || 
									(win.top && win.top.opener && win.top.opener.steal && win.top.opener.steal.instrument))) {

				// force startFiles to load before instrument
				steals.push(noop, {
					id: "steal/instrument",
					waits: true
				});
			}
		} catch (e) {
			// This would throw permission denied if
			// the child window was from a different domain
		}

		// we only load things with force = true
		if ( stealConfig.env == "production" && stealConfig.loadProduction && stealConfig.production ) {
			steal({
				id: stealConfig.production,
				force: true
			});
		} else {
			steals.unshift("stealconfig.js")

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
			steal.apply(win, steals);
		}
	});


	// ## AMD ##
	var modules = {

	};

	// convert resources to modules ...
	// a function is a module definition piece
	// you steal(moduleId1, moduleId2, function(module1, module2){});
	// 
	win.define = function( moduleId, dependencies, method ) {
		if(typeof moduleId == 'function'){
			modules[URI.cur+""] = moduleId();
		} else if(!method && dependencies){
			if(typeof dependencies == "function"){
				modules[moduleId] = dependencies();
			} else {
				modules[moduleId] = dependencies;
			}
			
		} else if (dependencies && method && !dependencies.length ) {
			modules[moduleId] = method();
		} else {
			steal.apply(null, map(dependencies, function(dependency){
				dependency = typeof dependency === "string" ? {
					id: dependency
				} : dependency;
				dependency.toId = steal.amdToId;
				
				dependency.idToUri = steal.amdIdToUri;
				return dependency;
			}).concat(method) )
		}
		
	}
	win.require = function(dependencies, method){
		var depends = map(dependencies, function(dependency){
				dependency = typeof dependency === "string" ? {
					id: dependency
				} : dependency;
				dependency.toId = steal.amdToId;
				
				dependency.idToUri = steal.amdIdToUri;
				return dependency;
			}).concat([method]);
		console.log("stealing",depends.slice(0))
		steal.apply(null, depends )
	}
	win.define.amd = {
		jQuery: true
	}


	//steal.when = when;
	// make steal public
	win.steal = steal;


	// make steal loaded
	define("steal", [], function() {
		return steal;
	});

	define("require",function(){
		return require;
	})

	var stealResource = new Resource("steal")
	stealResource.value = steal;
	stealResource.loaded.resolve();
	stealResource.run.resolve();
	stealResource.executing = true;
	stealResource.completed.resolve();

	resources[stealResource.options.id] = stealResource;

	startup();
	//win.steals = steals;
	win.steal.resources = resources;
	win.Resource = Resource;

})(this);
