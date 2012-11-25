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
(function (undefined) {

	var requestFactory = function () {
		return h.win.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
	};

	// ## Helpers ##
	// The following are a list of helper methods used internally to steal
	var h = {
		// check that we have a document,
		win: (function () {
			return this
		}).call(null),
		// a jQuery-like $.each
		each: function (o, cb) {
			var i, len;

			// weak array detection, but we only use this internally so don't
			// pass it weird stuff
			if (typeof o.length == 'number') {
				for (i = 0, len = o.length; i < len; i++) {
					cb.call(o[i], i, o[i], o)
				}
			} else {
				for (i in o) {
					if (o.hasOwnProperty(i)) {
						cb.call(o[i], i, o[i], o)
					}

				}
			}
			return o;
		},
		// adds the item to the array only if it doesn't currently exist
		uniquePush: function (arr, item) {
			if (h.inArray(arr, item) === -1) {
				return arr.push(item)
			}
		},
		// if o is a string
		isString: function (o) {
			return typeof o == "string";
		},
		// if o is a function
		isFn: function (o) {
			return typeof o == "function";
		},
		// dummy function
		noop: function () {},
		endsInSlashRegex: /\/$/,
		// creates an element
		createElement: function (nodeName) {
			return h.doc.createElement(nodeName)
		},
		// creates a script tag
		scriptTag: function () {
			var start = h.createElement("script");
			start.type = "text/javascript";
			return start;
		},
		// minify-able verstion of getElementsByTagName
		getElementsByTagName: function (tag) {
			return h.doc.getElementsByTagName(tag);
		},
		// A function that returns the head element
		// creates and caches the lookup for faster
		// performance.
		head: function () {
			var hd = h.getElementsByTagName("head")[0];
			if (!hd) {
				hd = h.createElement("head");
				h.docEl.insertBefore(hd, h.docEl.firstChild);
			}
			// replace head so it runs fast next time.
			h.head = function () {
				return hd;
			}
			return hd;
		},
		// extends one object with another
		extend: function (d, s) {
			// only extend if we have something to extend
			s && h.each(s, function (k) {
				if (s.hasOwnProperty(k)) {
					d[k] = s[k];
				}
			});
			return d;
		},
		// makes an array of things, or a mapping of things
		map: function (args, cb) {
			var arr = [];
			h.each(args, function (i, str) {
				arr.push(cb ? (h.isString(cb) ? str[cb] : cb.call(str, str)) : str)
			});
			return arr;
		},
		// ## AOP ##
		// Aspect oriented programming helper methods are used to
		// weave in functionality into steal's API.
		// calls `before` before `f` is called.
		//     steal.complete = before(steal.complete, f)
		// `changeArgs=true` makes before return the same args
		before: function (f, before, changeArgs) {
			return changeArgs ?
			function before_changeArgs() {
				return f.apply(this, before.apply(this, arguments));
			} : function before_args() {
				before.apply(this, arguments);
				return f.apply(this, arguments);
			}
		},
		// returns a function that calls `after` 
		// after `f`
		after: function (f, after, changeRet) {
			return changeRet ?
			function after_CRet() {
				return after.apply(this, [f.apply(this, arguments)].concat(h.map(arguments)));
			} : function after_Ret() {
				var ret = f.apply(this, arguments);
				after.apply(this, arguments);
				return ret;
			}
		},
		/**
		 * Performs an XHR request
		 * @param {Object} options
		 * @param {Function} success
		 * @param {Function} error
		 */
		request: function (options, success, error) {
			var request = new requestFactory(),
				contentType = (options.contentType || "application/x-www-form-urlencoded; charset=utf-8"),
				clean = function () {
					request = check = clean = null;
				},
				check = function () {
					var status;
					if (request && request.readyState === 4) {
						status = request.status;
						if (status === 500 || status === 404 || status === 2 || request.status < 0 || (!status && request.responseText === "")) {
							error && error(request.status);
						} else {
							success(request.responseText);
						}
						clean();
					}
				};
			request.open("GET", options.src + '', !(options.async === false));
			request.setRequestHeader("Content-type", contentType);
			if (request.overrideMimeType) {
				request.overrideMimeType(contentType);
			}

			request.onreadystatechange = check;
			try {
				request.send(null);
			}
			catch (e) {
				if (clean) {
					console.error(e);
					error && error();
					clean();
				}
			}
		},
		matchesId: function (loc, id) {
			if (loc === "*") {
				return true;
			} else if (id.indexOf(loc) === 0) {
				return true;
			}
		},
		// are we in production
		stealCheck: /steal\.(production\.)?js.*/,
		// get script that loaded steal 
		getStealScriptSrc: function () {
			if (!h.doc) {
				return;
			}
			var scripts = h.getElementsByTagName("script"),
				script;

			// find the steal script and setup initial paths.
			h.each(scripts, function (i, s) {
				if (h.stealCheck.test(s.src)) {
					script = s;
				}
			});
			return script;
		},
		inArray: function (arr, val) {
			for (var i = 0; i < arr.length; i++) {
				if (arr[i] === val) {
					return i;
				}
			}
			return -1;
		},
		addEvent: function (elem, type, fn) {
			if (elem.addEventListener) {
				elem.addEventListener(type, fn, false);
			} else if (elem.attachEvent) {
				elem.attachEvent("on" + type, fn);
			} else {
				fn();
			}
		},
		useIEShim: false
	}

	h.doc = h.win.document;
	h.docEl = h.doc && h.doc.documentElement;

	h.support = {
		// does onerror work in script tags?
		error: h.doc && (function () {
			var script = h.scriptTag();
			script.onerror = h.noop;
			return h.isFn(script.onerror) || "onerror" in script;
		})(),
		// If scripts support interactive ready state.
		// This is tested later.
		interactive: false,
		// use attachEvent for event listening (IE)
		attachEvent: h.doc && h.scriptTag().attachEvent
	}

	// steal's deferred library. It is used through steal
	// to support jQuery like API for file loading.
	var Deferred = function (func) {
		if (!(this instanceof Deferred)) return new Deferred();
		// arrays for `done` and `fail` callbacks
		this.doneFuncs = [];
		this.failFuncs = [];

		this.resultArgs = null;
		this.status = "";

		// check for option function: call it with this as context and as first
		// parameter, as specified in jQuery api
		func && func.call(this, this);
	}

	Deferred.when = function () {
		var args = h.map(arguments);
		if (args.length < 2) {
			var obj = args[0];
			if (obj && (h.isFn(obj.isResolved) && h.isFn(obj.isRejected))) {
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

			h.each(args, function (j, arg) {
				arg.done(function () {
					rp[j] = (arguments.length < 2) ? arguments[0] : arguments;
					if (++done == args.length) {
						df.resolve.apply(df, rp);
					}
				}).fail(function () {
					df.reject(arguments);
				});
			});

			return df;

		}
	}
	// call resolve functions
	var resolveFunc = function (type, status) {
		return function (context) {
			var args = this.resultArgs = (arguments.length > 1) ? arguments[1] : [];
			return this.exec(context, this[type], args, status);
		}
	},

		doneFunc = function (type, status) {
			return function () {
				var self = this;
				h.each(arguments, function (i, v, args) {
					if (!v) return;
					if (v.constructor === Array) {
						args.callee.apply(self, v)
					} else {
						// immediately call the function if the deferred has been resolved
						if (self.status === status) v.apply(this, self.resultArgs || []);

						self[type].push(v);
					}
				});
				return this;
			}
		};

	h.extend(Deferred.prototype, {
		resolveWith: resolveFunc("doneFuncs", "rs"),
		rejectWith: resolveFunc("failFuncs", "rj"),
		done: doneFunc("doneFuncs", "rs"),
		fail: doneFunc("failFuncs", "rj"),
		always: function () {
			var args = h.map(arguments);
			if (args.length && args[0]) this.done(args[0]).fail(args[0]);

			return this;
		},
		then: function () {
			var args = h.map(arguments);
			// fail function(s)
			if (args.length > 1 && args[1]) this.fail(args[1]);

			// done function(s)
			if (args.length && args[0]) this.done(args[0]);

			return this;
		},
		isResolved: function () {
			return this.status === "rs";
		},
		isRejected: function () {
			return this.status === "rj";
		},
		reject: function () {
			return this.rejectWith(this, arguments);
		},
		resolve: function () {
			return this.resolveWith(this, arguments);
		},
		exec: function (context, dst, args, st) {
			if (this.status !== "") return this;

			this.status = st;

			h.each(dst, function (i, d) {
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
	var whenEach = function (arr, func, obj, func2) {

		var deferreds = h.map(arr, func)
		return Deferred.when.apply(Deferred, deferreds).then(function () {
			if (h.isFn(obj[func2])) {
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

	var URI = function (url) {
		if (this.constructor !== URI) {
			return new URI(url);
		}
		h.extend(this, URI.parse("" + url));
	};
	// the current url (relative to root, which is relative from page)
	// normalize joins from this
	//
	h.extend(URI, {
		// parses a URI into it's basic parts
		parse: function (string) {
			var uriParts = string.split("?"),
				uri = uriParts.shift(),
				queryParts = uriParts.join("").split("#"),
				protoParts = uri.split("://"),
				parts = {
					query: queryParts.shift(),
					fragment: queryParts.join("#")
				},
				pathParts;

			if (protoParts[1]) {
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
	 *     st.URI.page.protocol //-> "http"
	 */
	URI.page = URI(h.win.location && location.href);
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
	h.extend(URI.prototype, {
		dir: function () {
			var parts = this.path.split("/");
			parts.pop();
			return URI(this.domain() + parts.join("/"))
		},
		filename: function () {
			return this.path.split("/").pop();
		},
		ext: function () {
			var filename = this.filename();
			return (filename.indexOf(".") > -1) ? filename.split(".").pop() : "";
		},
		domain: function () {
			return this.protocol ? this.protocol + "://" + this.host : "";
		},
		isCrossDomain: function (uri) {
			uri = URI(uri || h.win.location.href);
			var domain = this.domain(),
				uriDomain = uri.domain()
				return (domain && uriDomain && domain != uriDomain) || this.protocol === "file" || (domain && !uriDomain);
		},
		isRelativeToDomain: function () {
			return !this.path.indexOf("/");
		},
		hash: function () {
			return this.fragment ? "#" + this.fragment : ""
		},
		search: function () {
			return this.query ? "?" + this.query : ""
		},
		// like join, but returns a string
		add: function (uri) {
			return this.join(uri) + '';
		},
		join: function (uri, min) {
			uri = URI(uri);
			if (uri.isCrossDomain(this)) {
				return uri;
			}
			if (uri.isRelativeToDomain()) {
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
			if (this.path.match(/\/$/)) {
				left.pop();
			}
			while (part == ".." && left.length) {
				// if we've emptied out, folders, just break
				// leaving any additional ../s
				if (!left.pop()) {
					break;
				}
				right.shift();

				part = right[0];
			}
			return h.extend(URI(this.domain() + left.concat(right).join("/")), {
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
		normalize: function (cur) {
			cur = cur ? cur.dir() : URI.cur.dir();
			var path = this.path,
				res = URI(path);
			//if path is rooted from steal's root (DEPRECATED)
			if (!path.indexOf("//")) {
				res = URI(path.substr(2));
			} else if (!path.indexOf("./")) { // should be relative
				res = cur.join(path.substr(2));
			}
			// only if we start with ./ or have a /foo should we join from cur
			else if (this.isRelative()) {
				res = cur.join(this.domain() + path)
			}
			res.query = this.query;
			return res;
		},
		isRelative: function () {
			return /^[\.|\/]/.test(this.path)
		},
		// a min path from 2 urls that share the same domain
		pathTo: function (uri) {
			uri = URI(uri);
			var uriParts = uri.path.split("/"),
				thisParts = this.path.split("/"),
				result = [];
			while (uriParts.length && thisParts.length && uriParts[0] == thisParts[0]) {
				uriParts.shift();
				thisParts.shift();
			}
			h.each(thisParts, function () {
				result.push("../")
			})
			return URI(result.join("") + uriParts.join("/"));
		},
		mapJoin: function (url) {
			return this.join(URI(url).insertMapping());
		},
		// helper to go from jquery to jquery/jquery.js
		addJS: function () {
			var ext = this.ext();
			if (!ext) {
				// if first character of path is a . or /, just load this file
				if (!this.isRelative()) {
					this.path += "/" + this.filename();
				}
				this.path += ".js"
			}
			return this;
		}
	});
	// This can't be added to the prototype using extend because
	// then for some reason IE < 9 won't recognize it.
	URI.prototype.toString = function () {
		return this.domain() + this.path + this.search() + this.hash();
	};
	//  =============================== MAPPING ===============================
	URI.prototype.insertMapping = function () {
		// go through mappings
		var orig = "" + this,
			key, value;
		for (key in steal.mappings) {
			value = steal.mappings[key]
			if (value.test.test(orig)) {
				return orig.replace(key, value.path);
			}
		}
		return URI(orig);
	};

	// --- END URI
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



	var ConfigManager = function (options) {
		this.stealConfig = {};
		this.callbacks = [];
		this.attr(ConfigManager.defaults);
		this.attr(options)
	}

	h.extend(ConfigManager.prototype, {
		// get or set config.stealConfig attributes
		attr: function (config) {
			if (!config) { // called as a getter, so just return
				return this.stealConfig;
			}
			if (arguments.length === 1 && typeof config === "string") { // called as a getter, so just return
				return this.stealConfig && this.stealConfig[config];
			}
			this.stealConfig = this.stealConfig || {};
			for (var prop in config) {
				var value = config[prop];
				// if it's a special function
				this[prop] ?
				// run it
				this[prop](value) :
				// otherwise set or extend
				(typeof value == "object" && this.stealConfig[prop] ?
				// extend
				h.extend(this.stealConfig[prop], value) :
				// set
				this.stealConfig[prop] = value);

			}

			for (var i = 0; i < this.callbacks.length; i++) {
				this.callbacks[i](this.stealConfig)
			}

			return this;
		},

		// add callbacks which are called after config is changed
		on: function (cb) {
			this.callbacks.push(cb)
		},

		// get the current start file
		startFile: function (startFile) {
			// make sure startFile and production look right
			this.stealConfig.startFile = "" + URI(startFile).addJS()
			if (!this.stealConfig.production) {
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
		root: function (relativeURI) {
			if (relativeURI !== undefined) {
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
			this.stealConfig.root = root || URI("");
		},
		//var stealConfig = configs[configContext];
		cloneContext: function () {
			return new ConfigManager(h.extend({}, this.stealConfig));
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

	// ### TYPES ##
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
	ConfigManager.prototype.types = function (types) {
		var configTypes = this.stealConfig.types || (this.stealConfig.types = {});
		h.each(types, function (type, cb) {
			var typs = type.split(" ");
			configTypes[typs.shift()] = {
				require: cb,
				convert: typs
			};
		});
	};
	ConfigManager.prototype.require = function (options, success, error) {
		// add the src option
		// but it is not added to functions
		if (options.idToUri) {
			var old = options.src;
			options.src = this.addSuffix(options.idToUri(options.id));
		}

		// get the type
		var type = this.attr().types[options.type],
			converters;

		// if this has converters, make it get the text first, then pass it to the type
		if (type.convert.length) {
			converters = type.convert.slice(0);
			converters.unshift("text", options.type)
		} else {
			converters = [options.type]
		}
		require(options, converters, success, error, this)
	}
	ConfigManager.prototype.addSuffix = function (str) {
		var suffix = this.attr('suffix')
		if (suffix) {
			str = (str + '').indexOf('?') > -1 ? str + "&" + suffix : str + "?" + suffix;
		}
		return str;
	}

	// Require function. It will be called recursevly until all 
	// converters are ran. After that `success` callback is ran.
	// For instance if we're loading the .less file it will first
	// run the `text` converter, then `less` converter and finally
	// the `fn` converter.


	function require(options, converters, success, error, config) {
		var t = converters[0]
		var type = config.attr('types')[converters.shift()];

		type.require(options, function require_continue_check() {
			// if we have more types to convert
			if (converters.length) {
				require(options, converters, success, error, config)
			} else { // otherwise this is the final
				success.apply(this, arguments);
			}
		}, error, config)
	};




	// =============================== TYPES ===============================
	// a clean up script that prevents memory leaks and removes the
	// script
	var cleanUp = function (elem) {
		elem.onreadystatechange = elem.onload = elem.onerror = null;

		setTimeout(function () {
			h.head().removeChild(elem);
		}, 1);
	},
		// the last inserted script, needed for IE
		lastInserted,
		// if the state is done
		stateCheck = /^loade|c|u/;


	var cssCount = 0,
		createSheet = h.doc && h.doc.createStyleSheet,
		lastSheet, lastSheetOptions;

	// Apply all the basic types
	ConfigManager.defaults.types = {
		"js": function (options, success, error) {
			// create a script tag
			var script = h.scriptTag(),
				callback = function () {
					if (!script.readyState || stateCheck.test(script.readyState)) {
						cleanUp(script);
						success();
					}
				},
				errorTimeout;
			// if we have text, just set and insert text
			if (options.text) {
				// insert
				script.text = options.text;

			} else {
				var src = options.src; //st.idToUri( options.id );
				// If we're in IE older than IE9 we need to use
				// onreadystatechange to determine when javascript file
				// is loaded. Unfortunately this makes it impossible to
				// call teh error callback, because it will return 
				// loaded or completed for the script even if it 
				// encountered the 404 error
				if (h.useIEShim) {
					script.onreadystatechange = function () {
						if (stateCheck.test(script.readyState)) {
							success();
						}
					}
				} else {
					script.onload = callback;
					// error handling doesn't work on firefox on the filesystem
					if (h.support.error && error && src.protocol !== "file") {
						script.onerror = error;
					}
				}

				// listen to loaded
				script.src = "" + src;
				//script.src = options.src = addSuffix(options.src);
				//script.async = false;
				script.onSuccess = success;
			}

			// insert the script
			lastInserted = script;
			h.head().insertBefore(script, h.head().firstChild);

			// if text, just call success right away, and clean up
			if (options.text) {
				callback();
			}
		},
		"fn": function (options, success) {
			var ret;
			if (!options.skipCallbacks) {
				ret = options.fn();
			}
			success(ret);
		},
		// request text
		"text": function (options, success, error) {
			h.request(options, function (text) {
				options.text = text;
				success(text);
			}, error)
		},
		// loads css files and works around IE's 31 sheet limit
		"css": function (options, success, error) {
			if (options.text) { // less
				var css = h.createElement("style");
				css.type = "text/css";
				if (css.styleSheet) { // IE
					css.styleSheet.cssText = options.text;
				} else {
					(function (node) {
						if (css.childNodes.length) {
							if (css.firstChild.nodeValue !== node.nodeValue) {
								css.replaceChild(node, css.firstChild);
							}
						} else {
							css.appendChild(node);
						}
					})(h.doc.createTextNode(options.text));
				}
				h.head().appendChild(css);
			} else {
				if (createSheet) {
					// IE has a 31 sheet and 31 import per sheet limit
					if (!cssCount++) {
						lastSheet = h.doc.createStyleSheet(options.src);
						lastSheetOptions = options;
					} else {
						var relative = "" + URI(URI(lastSheetOptions.src).dir()).pathTo(options.src);
						lastSheet.addImport(relative);
						if (cssCount == 30) {
							cssCount = 0;
						}
					}
					success();
					return;
				}

				options = options || {};
				var link = h.createElement("link");
				link.rel = options.rel || "stylesheet";
				link.href = options.src;
				link.type = "text/css";
				h.head().appendChild(link);
			}

			success();
		}
	};


	var moduleManager = function (steal, stealModules, interactives, config) {

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
		var Module = function (options) {
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
		Module.make = function (options) {
			// create the temporary reasource
			var module = new Module(options),
				// use `rootSrc` as the definitive ID
				id = module.options.id;

			// assuming this module should not be created again.
			if (module.unique && id) {

				// Check if we already have a module for this rootSrc
				// Also check with a .js ending because we defer 'type'
				// determination until later
				if (!modules[id] && !modules[id + ".js"]) {
					// If we haven't loaded, cache the module
					modules[id] = module;
				} else {

					// Otherwise get the cached module
					existingModule = modules[id];
					// If options were passed, copy new properties over.
					// Don't copy src, etc because those have already
					// been changed to be the right values;
					if (!h.isString(options)) {
						// extend everything other than id
						for (var prop in options) {
							if (prop !== "id") {
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
			setOptions: function (options) {
				var prevOptions = this.options;
				// if we have no options, we are the global Module that
				// contains all other modules.
				if (!options) { //global init cur ...
					this.options = {};
					this.waits = false;
				}
				//handle callback functions
				else if (h.isFn(options)) {
					var uri = URI.cur,
						self = this,
						cur = steal.cur;
					this.options = {
						fn: function () {

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
							for (var i = cur.dependencies.length; i >= 0; i--) {
								dep = cur.dependencies[i];

								if (found) {
									if (dep === null) {
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

								if (dep === self) {
									found = true;
								}
							}



							var ret = options.apply(cur, args);

							// if this returns a value, we should register it as a module ...
							if (ret) {
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
				for (opt in prevOptions) {
					if (!this.options[opt]) {
						this.options[opt] = prevOptions[opt];
					}
				}
				if (this.options.id) {
					this.options.abort = false;
				}
			},

			// Calling complete indicates that all dependencies have
			// been completed for this module
			complete: function () {
				this.completed.resolve();
			},
			// After the script has been loaded and run
			// - checks what has been stolen (in pending)
			// - wires up pendings steal's deferreds to eventually complete this
			// - this is where all of steal's complexity is
			executed: function (script) {
				var myqueue, stel, src = this.options.src,
					rootSrc = this.options.rootSrc;
				// Set this as the current file so any relative urls
				// will load from it.
				// rootSrc needs to be the translated path
				// we need id vs rootSrc ...
				if (this.options.id) {
					URI.cur = URI(this.options.id);
				}
				if (this.exports) {
					this.exports()
				}
				// set this as the current module
				steal.cur = this;

				// mark yourself as 'loaded'.
				this.run.resolve();

				// If we are IE, get the queue from interactives.
				// It in interactives because you can't use onload to know
				// which script is executing.
				if (h.support.interactive && src) { /*myqueue = interactives[src];*/
					if (interactives[src]) {
						myqueue = [];
						if (interactives.length) {
							for (var i = 0; i < interactives.length; i++) {
								if (interactives[i] !== this.orig) {
									myqueue.push(interactives[i])
								}
							}
						} else {
							if (interactives[src] !== this.orig) {
								myqueue = interactives[src];
								delete interactives[src];
							}
						}

					}
				}
				// In other browsers, the queue of items to load is
				// what is in pending
				if (!myqueue) {
					myqueue = Module.pending.slice(0);
					Module.pending = [];
				}

				// if we have nothing, mark us as complete
				if (!myqueue.length) {
					this.complete();
					return;
				}
				this.addDependencies(myqueue)
				this.loadDependencies();

			},
			// add depenedencies to the module
			addDependencies: function (myqueue) {
				var self = this,
					isProduction = steal.config().env == "production";
				this.queue = [];
				h.each(myqueue, function (i, item) {
					if (item === null) {
						self.queue.push(null);
						return;
					}

					if ((isProduction && item.ignore) || (!isProduction && !steal.isRhino && item.prodonly)) {
						return;
					}

					// make a steal object
					var stel = Module.make(item);
					if (steal.packHash[stel.options.id] && stel.options.type !== 'fn') { // if we are production, and this is a package, mark as loading, but steal package?
						steal.has("" + stel.options.id);
						stel = steal.make(steal.packHash["" + stel.options.id]);
					}
					// has to happen before 'needs' for when reversed...
					self.queue.push(stel);
				});
			},
			// loads module's dependencies
			loadDependencies: function () {

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
				h.each(this.queue, function (i, module) {
					// add it as a dependency, circular are not allowed
					self.dependencies.push(module);

					// if there's a wait and it's not the first thing
					if ((module === null || module.waits) && set.length) {
						// add the current set to `priorSet`
						priorSet = priorSet.concat(set);
						// empty the current set
						set = [];
						// we have our firs set of items
						setFirstSet = false;
						if (module === null) {
							return;
						}

					}
					if (module === null) return;

					// lets us know this module is currently wired to load
					module.isSetupToExecute = true;
					// when the priorSet is completed, execute this module
					// and when it's needs are done
					var waitsOn = priorSet.slice(0);
					// if there are needs, this can not be part of the "firstSet"
					h.each(module.options.needs || [], function (i, raw) {

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
					if (setFirstSet && (module.options.needs || []).length == 0) {
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
				h.each(firstSet, function (i, f) {
					f.execute();
				});
			},
			/**
			 * Loads this steal
			 */
			load: function (returnScript) {
				// if we are already loading / loaded
				if (this.loading || this.loaded.isResolved()) {
					return;
				}

				this.loading = true;
				this.loaded.resolve();
			},
			execute: function () {
				var self = this;
				// if a late need dependency was addded
				if (this.lateNeedDependency && !this.lateNeedDependency.completed.isResolved()) {
					// call execute again when it's finished
					this.lateNeedDependency.completed.then(function () {
						self.execute()
					})
					return;
				}

				// check types
				var raw = this.options,
					types = config.attr('types');

				// if it's a string, get it's extension and check if
				// it is a registered type, if it is ... set the type
				if (!raw.type) {
					var ext = URI(raw.id).ext();
					if (!ext && !types[ext]) {
						ext = "js";
					}
					raw.type = ext;
				}
				if (!types[raw.type] && steal.config().env == 'development') {
					throw "steal.js - type " + raw.type + " has not been loaded.";
				} else if (!types[raw.type] && steal.config().env == 'production') {
					// if we haven't defined EJS yet and we're in production, its ok, just ignore it
					return;
				}
				var converters = types[raw.type].convert;
				raw.buildType = converters.length ? converters[converters.length - 1] : raw.type;

				if (!self.loaded.isResolved()) {
					self.loaded.resolve();
				}
				if (!self.executing) {
					self.executing = true;

					config.require(self.options, function (value) {
						self.executed(value);
					}, function (error, src) {
						var abortFlag = self.options.abort,
							errorCb = self.options.error;

						// if an error callback was provided, fire it
						if (errorCb) {
							errorCb.call(self.options);
						}

						h.win.clearTimeout && h.win.clearTimeout(self.completeTimeout)

						// if abort: false, register the script as loaded, and don't throw
						if (abortFlag === false) {
							self.executed();
							return;
						}
						throw "steal.js : " + self.options.src + " not completed"
					});
				}
			},
			updateOptions: function () {
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
			rewriteIdAndUpdateOptions: function (id) {
				// if module is not a function it means it's `src` is changeable
				if (this.options.type != "fn") {
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
					if (this.isSetupToExecute) {
						this.addLateDependencies(needs);
					}
				}
			},
			addLateDependencies: function (needs) {
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
				h.each(this.options.needs || [], function (i, need) {
					if (h.inArray(needs, need) == -1) {
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
			load: h.after(Module.prototype.load, function (stel) {
				var self = this;
				if (h.doc && !self.completed && !self.completeTimeout && !steal.isRhino && (self.options.src.protocol == "file" || !h.support.error)) {
					self.completeTimeout = setTimeout(function () {
						throw "steal.js : " + self.options.src + " not completed"
					}, 5000);
				}
			}),
			complete: h.after(Module.prototype.complete, function () {
				this.completeTimeout && clearTimeout(this.completeTimeout)
			}),

			// if we're about to mark a file as executed, mark its "has" array files as
			// executed also
			executed: h.before(Module.prototype.executed, function () {
				if (this.options.has) {
					this.loadHas();
				}
			}),

			/**
			 * @hide
			 * Goes through the array of files listed in this.options.has, marks them all as loaded.
			 * This is used for files like production.css, which, once they load, need to mark the files they
			 * contain as loaded.
			 */
			loadHas: function () {
				var stel, i, current = URI.cur;

				if (this.options.buildType == 'js') {
					return;
				}

				// mark everything in has loaded
				h.each(this.options.has, function (i, has) {
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
		Module.make = h.after(Module.make, function (stel) {
			// if we have things
			if (stel.options.has) {
				// if we have loaded this already (and we are adding has's)
				if (stel.run.isResolved()) {
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

	function stealManager(kickoff, config, setStealOnWindow) {

		// a startup function that will be called when steal is ready
		var interactiveScript,
		// key is script name, value is array of pending items
		interactives = {},
			// empty startup function
			startup = function () {};

		var st = function () {

			// convert arguments into an array
			var args = h.map(arguments, function (options) {
				if (options) {
					var opts = h.isString(options) ? {
						id: options
					} : options;

					if (!opts.idToUri) {
						opts.idToUri = st.idToUri
					}
					return opts;
				} else {
					return options;
				}
			});
			if (args.length) {
				Module.pending.push.apply(Module.pending, args);
				// steal.after is called everytime steal is called
				// it kicks off loading these files
				st.after(args);
				// return steal for chaining
			}

			return st;
		};
		if (setStealOnWindow) {
			h.win.steal = st;
		}
		// clone steal context
		st.clone = function () {
			return stealManager(false, config.cloneContext())
		}

		st.config = function () {
			st.config.called = true;
			return config.attr.apply(config, arguments)
		};
		st.require = function () {
			return config.require.apply(config, arguments);
		}
		st.config.called = false;
		st._id = Math.floor(1000 * Math.random());

		/**
		 * @function st.getScriptOptions
		 *
		 * `steal.getScriptOptions` is used to determine various
		 * options passed to the steal.js file:
		 *
		 * - should we load the production version of the 
		 *   (if you use steal.production.js instead of steal.js)
		 * - parts of the query string to determine `startFile`
		 * - location of the `root url`
		 */

		st.getScriptOptions = function (script) {

			var options = {},
				parts, src, query, startFile, env;

			script = script || h.getStealScriptSrc();

			if (script) {

				// Split on question mark to get query
				parts = script.src.split("?");
				src = parts.shift();
				// // for IE7, where the script.src is always relative
				// if(!/\/\//.test(src)){
				// 	var dir = URI.page.dir();
				// 	src = URI(dir.join(src))+"";
				// }
				query = parts.join("?");

				// Split on comma to get startFile and env
				parts = query.split(",");

				if (src.indexOf("steal.production") > -1) {
					options.env = "production";
				}

				// Grab startFile
				startFile = parts[0];

				if (startFile) {
					if (startFile.indexOf(".js") == -1) {
						startFile += "/" + startFile.split("/").pop() + ".js";
					}
					options.startFile = startFile;
				}

				// Grab env
				env = parts[1];

				if (env) {
					options.env = env;
				}

				// Split on / to get rootUrl
				parts = src.split("/")
				parts.pop();
				if (parts[parts.length - 1] == "steal") {
					parts.pop();
				}
				var root = parts.join("/");
				options.root = root

			}

			return options;
		};

		/**
		 * @function st.id
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
		 * `st.id()`
		 */
		// returns the "rootSrc" id, something that looks like requireJS
		// for a given id/path, what is the "REAL" id that should be used
		// this is where substituation can happen
		st.id = function (id, currentWorkingId, type) {
			// id should be like
			var uri = URI(id);
			uri = uri.addJS().normalize(currentWorkingId ? new URI(currentWorkingId) : null)
			// check foo/bar
			if (!type) {
				type = "js"
			}
			if (type == "js") {
				// if it ends with .js remove it ...
				// if it ends
			}
			// check map config
			var map = config.attr().map || {};
			// always run past 
			h.each(map, function (loc, maps) {
				// is the current working id matching loc
				if (h.matchesId(loc, currentWorkingId)) {
					// run maps
					h.each(maps, function (part, replaceWith) {
						if (("" + uri).indexOf(part) == 0) {
							uri = URI(("" + uri).replace(part, replaceWith))
						}
					})
				}
			})

			return uri;
		}

		st.amdToId = function (id, currentWorkingId, type) {
			var uri = URI(id);
			uri = uri.normalize(currentWorkingId ? new URI(currentWorkingId) : null)
			// check foo/bar
			if (!type) {
				type = "js"
			}
			if (type == "js") {
				// if it ends with .js remove it ...
				// if it ends
			}
			// check map config
			var map = config.attr().map || {};
			// always run past 
			h.each(map, function (loc, maps) {
				// is the current working id matching loc
				if (h.matchesId(loc, currentWorkingId)) {
					// run maps
					h.each(maps, function (part, replaceWith) {
						if (("" + uri).indexOf(part) == 0) {
							uri = URI(("" + uri).replace(part, replaceWith))
						}
					})
				}
			})
			return uri;
		}

		// for a given ID, where should I find this resource
		/**
		 * @function st.idToUri
		 *
		 * `steal.idToUri( id, noJoin )` takes an id and returns a URI that
		 * is the location of the file. It uses the paths option of  [config].
		 * Passing true for `noJoin` does not join from the root URI.
		 */
		st.idToUri = function (id, noJoin) {
			// this is normalize
			var paths = config.attr().paths || {},
				path;
			// always run past 
			h.each(paths, function (part, replaceWith) {
				path = "" + id;
				// if path ends in / only check first part of id
				if ((h.endsInSlashRegex.test(part) && path.indexOf(part) == 0) ||
				// or check if its a full match only
				path === part) {
					id = URI(path.replace(part, replaceWith));
				}
			})

			return noJoin ? id : config.attr().root.join(id)
		}

		// for a given AMD id this will return an URI object
		/**
		 * @function st.amdIdToUri
		 *
		 * `steal.amdIdToUri( id, noJoin )` takes and AMD id and returns a URI that
		 * is the location of the file. It uses the paths options of [config].
		 * Passing true for `noJoin` does not join from that URI.
		 */
		st.amdIdToUri = function (id, noJoin) {
			// this is normalize
			var paths = config.attr().paths || {},
				path;
			// always run past 
			h.each(paths, function (part, replaceWith) {
				path = "" + id;
				// if path ends in / only check first part of id
				if ((h.endsInSlashRegex.test(part) && path.indexOf(part) == 0) ||
				// or check if its a full match only
				path === part) {
					id = URI(path.replace(part, replaceWith));
				}
			})
			if (/(^|\/)[^\/\.]+$/.test(id)) {
				id = URI(id + ".js")
			}
			return id //noJoin ? id : config().root.join(id)
		}

		// ## AMD ##
		var modules = {

		};


		// AMD is not available for now. If you want to use AMD features with
		// steal you can by setting the `amd` param to true:
		//
		//     steal({
		//       amd: true
		//     })
		//
		// This will expose `define` and `require` functions which can be used
		// to load AMD modules
		if (config.attr('amd') === true) {

			// convert resources to modules ...
			// a function is a module definition piece
			// you steal(moduleId1, moduleId2, function(module1, module2){});
			/**
			 * @function window.define
			 *
			 * AMD compatible `define` function. It is available only if steal's
			 * `amd` param is set to true:
			 *
			 *     <script type="text/javascript">
			 *       steal = {
			 *         amd : true
			 *       }
			 *     <script />
			 *     <script type="text/javascript" src="steal/steal.js"></script>
			 *
			 */
			h.win.define = function (moduleId, dependencies, method) {
				if (typeof moduleId == 'function') {
					modules[URI.cur + ""] = moduleId();
				} else if (!method && dependencies) {
					if (typeof dependencies == "function") {
						modules[moduleId] = dependencies();
					} else {
						modules[moduleId] = dependencies;
					}

				} else if (dependencies && method && !dependencies.length) {
					modules[moduleId] = method();
				} else {
					st.apply(null, h.map(dependencies, function (dependency) {
						dependency = typeof dependency === "string" ? {
							id: dependency
						} : dependency;
						dependency.toId = st.amdToId;

						dependency.idToUri = st.amdIdToUri;
						return dependency;
					}).concat(method))
				}

			}
			/**
			 * @function window.require
			 *
			 * AMD compatible require function. It is available only if steal's
			 * `amd` param is set to true:
			 *
			 *     <script type="text/javascript">
			 *       steal = {
			 *         amd : true
			 *       }
			 *     <script />
			 *     <script type="text/javascript" src="steal/steal.js"></script>
			 *
			 */
			h.win.require = function (dependencies, method) {
				var depends = h.map(dependencies, function (dependency) {
					dependency = typeof dependency === "string" ? {
						id: dependency
					} : dependency;
					dependency.toId = st.amdToId;

					dependency.idToUri = st.amdIdToUri;
					return dependency;
				}).concat([method]);
				st.apply(null, depends)
			}
			h.win.define.amd = {
				jQuery: true
			}

			// expose steal as AMD module
			define("steal", [], function () {
				return st;
			});

			define("require", function () {
				return require;
			})

		}

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
			makeOptions: function (options, curId) {
				// convert it to a uri
				if (!options.id) {
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
				if (configedExt && !config.attr().types[configedExt]) {
					if (!options.needs) {
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
			then: function () {
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
			bind: function (event, listener) {
				if (!events[event]) {
					events[event] = []
				}
				var special = st.events[event]
				if (special && special.add) {
					listener = special.add(listener);
				}
				listener && events[event].push(listener);
				return st;
			},
			/**
			 * `steal.one(eventName, handler(eventArgs...) )` works just like
			 * [steal.bind] but immediately unbinds after `handler` is called.
			 */
			one: function (event, listener) {
				return st.bind(event, function () {
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
			unbind: function (event, listener) {
				var evs = events[event] || [],
					i = 0;
				while (i < evs.length) {
					if (listener === evs[i]) {
						evs.splice(i, 1);
					} else {
						i++;
					}
				}
			},
			trigger: function (event, arg) {
				var arr = events[event] || [];
				// array items might be removed during each iteration (with unbind),
				// so we iterate over a copy
				h.each(h.map(arr), function (i, f) {
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
			has: function () {
				// we don't use IE's interactive script functionality while
				// production scripts are loading
				h.support.interactive = false;
				h.each(arguments, function (i, arg) {
					var stel = Module.make({
						id: arg,
						idToUri: st.idToUri
					});
					stel.loading = stel.executing = true;
				});
			},
			make: function (id) {
				var opts = (typeof id === "string" ? {
					id: id
				} : id);
				if (!opts.idToUri) {
					opts.idToUri = st.idToUri;
				}
				return Module.make(opts);
			},
			// a dummy function to add things to after the stel is created, but before executed is called
			preexecuted: function () {},
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
			executed: function (name) {
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
			type: function (type, cb) {
				var typs = type.split(" ");
				if (!cb) {
					return config.attr('types')[typs.shift()].require
				}

				var types = config.attr('types')

				types[typs.shift()] = {
					require: cb,
					convert: typs
				};

				config.attr('types', types)
			},
			request: h.request
		});
		// Determine if we're running in IE older than IE9. This 
		// will affect loading strategy for JavaScripts.
		h.useIEShim = (function () {
			if (st.isRhino) {
				return false;
			}

			var d = document.createElement('div');
			d.innerHTML = "<!--[if lt IE 9]>ie<![endif]-->";
			return !!(h.scriptTag().readyState && d.innerText === "ie");
		})()

		//  ============================== Packages ===============================
		/**
		 * @function packages
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
		st.packs = [];
		st.packHash = {};
		st.packages = function (map) {

			if (!arguments.length) {
				return st.packs;
			} else {
				if (typeof map == 'string') {
					st.packs.push.apply(st.packs, arguments);
				} else {
					st.packHash = map;
				}

				return this;
			}
		};


		var Module = moduleManager(st, modules, interactives, config);
		resources = Module.modules;

		/**
		 * Implements shim support for steal
		 *
		 * This function sets up shims for steal. It follows RequireJS' syntax:
		 *
		 *     steal.config({
		 *        shim : {
		 *          jquery: {
		 *            exports: "jQuery"
		 *          }
		 *        }
		 *      })
		 * 
		 * You can also set function to explicitely return value from the module:
		 *
		 *     steal.config({
		 *        shim : {
		 *          jquery: {
		 *            exports: function(){
		 *              return window.jQuery;
		 *            }
		 *          }
		 *        }
		 *      })
		 *
		 * This enables steal to pass you a value from library that is not wrapped
		 * with steal() call.
		 *
		 *     steal('jquery', function(j){
		 *       // j is set to jQuery
		 *     })
		 */
		st.setupShims = function (shims) {
			// Go through all shims
			for (var id in shims) {
				// Make resource from shim's id. Since steal takes care
				// of always returning same resource for same id 
				// when someone steals resource created in this function
				// they will get same object back
				var resource = Module.make({
					id: id
				});
				if (typeof shims[id] === "object") {
					// set up dependencies of the module
					var needs = shims[id].deps || []
					var exports = shims[id].exports;
					var init = shims[id].init
				} else {
					needs = shims[id];
				}(function (_resource, _needs) {
					_resource.options.needs = _needs;
				})(resource, needs);
				// create resource's exports function. We check for existance
				// of this function in `Module.prototype.executed` and if it exitst
				// it is called, which sets `value` of the module 
				resource.exports = (function (_resource, _needs, _exports, _init) {
					return function () {
						var args = [];
						h.each(_needs, function (i, id) {
							args.push(Module.make(id).value);
						});
						if (_init) {
							// if module has exports function, call it
							_resource.value = _init.apply(null, args);
						} else {
							// otherwise it's a string so we just return
							// object from the window e.g window['jQuery']
							_resource.value = h.win[_exports];
						}
					}
				})(resource, needs, exports, init)
			}
		}

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
			map: function (from, to) {
				if (h.isString(from)) {
					st.mappings[from] = {
						test: new RegExp("^(\/?" + from + ")([/.]|$)"),
						path: to
					};
					h.each(modules, function (id, module) {
						if (module.options.type != "fn") {
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
			after: function () {
				// if we don't have a current 'top' steal
				// we create one and set it up
				// to start loading its dependencies (the current pending steals)
				if (!rootSteal) {
					rootSteal = new Module();
					// keep a reference in case it disappears
					var cur = rootSteal,
						// runs when a steal is starting
						go = function () {
							// indicates that a collection of steals has started
							st.trigger("start", cur);
							cur.completed.then(function () {

								rootSteal = null;
								st.trigger("end", cur);


							});

							cur.executed();
						};
					// If we are in the browser, wait a
					// brief timeout before executing the rootModule.
					// This allows embeded script tags with steal to be part of 
					// the initial set
					if (h.win.setTimeout) {
						// we want to insert a "wait" after the current pending
						st.pushPending();
						setTimeout(function () {
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

		(function () {
			var myPending;
			st.pushPending = function () {
				myPending = Module.pending.slice(0);
				Module.pending = [];
				h.each(myPending, function (i, arg) {
					Module.make(arg);
				})
			}
			st.popPending = function () {
				Module.pending = Module.pending.length ? myPending.concat(null, Module.pending) : myPending;
			}
		})();

		// =============================== jQuery ===============================
		(function () {
			var jQueryIncremented = false,
				jQ, ready = false;

			// check if jQuery loaded after every script load ...
			Module.prototype.executed = h.before(Module.prototype.executed, function () {

				var $ = h.win.jQuery;
				if ($ && "readyWait" in $) {

					//Increment jQuery readyWait if ncecessary.
					if (!jQueryIncremented) {
						jQ = $;
						$.readyWait += 1;
						jQueryIncremented = true;
					}
				}
			});

			// once the current batch is done, fire ready if it hasn't already been done
			st.bind("end", function () {
				if (jQueryIncremented && !ready) {
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

		h.addEvent(h.win, "load", function () {
			loaded.load.resolve();
		});

		st.one("end", function (collection) {
			loaded.end.resolve(collection);
			firstEnd = collection;
			st.trigger("done", firstEnd)
		})
		st.firstComplete = loaded.end;

		Deferred.when(loaded.load, loaded.end).then(function () {
			st.trigger("ready")
			st.isReady = true;
		});

		st.events.done = {
			add: function (cb) {
				if (firstEnd) {
					cb(firstEnd);
					return false;
				} else {
					return cb;
				}
			}
		};

		startup = h.after(startup, function () {
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
			search && search.replace(/steal\[([^\]]+)\]=([^&]+)/g, function (whoe, prop, val) {
				urlOptions[prop] = ~val.indexOf(",") ? val.split(",") : val;
			});
			// B: DO THINGS WITH OPTIONS
			// CALCULATE CURRENT LOCATION OF THINGS ...
			config.attr(urlOptions);
			var options = config.attr();

			// mark things that have already been loaded
			h.each(options.executed || [], function (i, stel) {
				st.executed(stel)
			})
			// immediate steals we do
			var steals = [];

			// add start files first
			if (options.startFiles) {
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
				if (!options.browser && ((h.win.top && h.win.top.st.instrument) || (h.win.top && h.win.top.opener && h.win.top.opener.steal && h.win.top.opener.st.instrument))) {

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
			if (config.attr().env == "production" && config.attr().loadProduction && config.attr().production) {
				st({
					id: config.attr().production,
					force: true
				});
			} else {
				steals.unshift({
					id: "stealconfig.js",
					abort: false
				});

				if (options.loadDev !== false) {
					steals.unshift({
						id: "steal/dev/dev.js",
						ignore: true
					});
				}

				if (options.startFile) {
					steals.push(null, options.startFile)
				}
			}
			if (steals.length) {
				st.apply(h.win, steals);
			}
		});

		// =========== INTERACTIVE STUFF ===========
		// Logic that deals with making steal work with IE.  IE executes scripts out of order, so in order to tell which scripts are
		// dependencies of another, steal needs to check which is the currently "interactive" script.
		var getInteractiveScript = function () {
			var scripts = h.getElementsByTagName("script"),
				i = scripts.length;
			while (i--) {
				// if script's readyState is interactive it is the one we want
				if (scripts[i].readyState === "interactive") {
					return scripts[i];
				}
			}
		},
			getCachedInteractiveScript = function () {
				if (interactiveScript && interactiveScript.readyState === 'interactive') {
					return interactiveScript;
				}

				if (interactiveScript = getInteractiveScript()) {
					return interactiveScript;
				}

				// check last inserted
				if (lastInserted && lastInserted.readyState == 'interactive') {
					return lastInserted;
				}

				return null;
			};


		h.support.interactive = h.doc && !! getInteractiveScript();
		if (h.support.interactive) {
			// after steal is called, check which script is "interactive" (for IE)
			st.after = h.after(st.after, function () {
				// check if disabled by st.loading()
				if (!h.support.interactive) {
					return;
				}

				var interactive = getCachedInteractiveScript();
				// if no interactive script, this is a steal coming from inside a steal, let complete handle it
				if (!interactive || !interactive.src || /steal\.(production|production\.[a-zA-Z0-9\-\.\_]*)*js/.test(interactive.src)) {
					return;
				}
				// get the source of the script
				var src = interactive.src;
				// create an array to hold all steal calls for this script
				if (!interactives[src]) {
					interactives[src] = []
				}

				// add to the list of steals for this script tag
				if (src) {
					interactives[src].push.apply(interactives[src], Module.pending);
					Module.pending = [];
				}
			})

			// This is used for packaged scripts.  As the packaged script executes, we grab the
			// dependencies that have come so far and assign them to the loaded script
			st.preexecuted = h.before(st.preexecuted, function (stel) {
				// check if disabled by st.loading()
				if (!h.support.interactive) {
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

		// Use config.on to listen on changes in config. We primarily use this
		// to update resources' paths when stealconfig.js is loaded.
		config.on(function (configData) {
			h.each(resources, function (id, resource) {
				resource.rewriteIdAndUpdateOptions(id);
			});
			// set up shims after ids are updated
			if (configData.shim) {
				st.setupShims(configData.shim)
			}
		})

		st.File = st.URI = URI;

		// if this is a first steal context in the page
		// we need to set up the `steal` module so we would 
		// know steal was loaded.
		if (kickoff) {
			var stealModule = new Module({
				id: "steal"
			})
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