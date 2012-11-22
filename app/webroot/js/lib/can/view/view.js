steal("can/util", function( can ) {
	// ## view.js
	// `can.view`  
	// _Templating abstraction._

	var isFunction = can.isFunction,
		makeArray = can.makeArray,
		// Used for hookup `id`s.
		hookupId = 1,
	/**
	 * @add can.view
	 */
	$view = can.view = function(view, data, helpers, callback){
		// Get the result.
		var result = $view.render(view, data, helpers, callback);
		if(isFunction(result))  {
			return result;
		}
		if(can.isDeferred(result)){
			return result.pipe(function(result){
				return $view.frag(result);
			});
		}
		
		// Convert it into a dom frag.
		return $view.frag(result);
	};

	can.extend( $view, {
		// creates a frag and hooks it up all at once
		frag: function(result, parentNode ){
			return $view.hookup( $view.fragment(result), parentNode );
		},

		// simply creates a frag
		// this is used internally to create a frag
		// insert it
		// then hook it up
		fragment: function(result){
			var frag = can.buildFragment(result,document.body);
			// If we have an empty frag...
			if(!frag.childNodes.length) { 
				frag.appendChild(document.createTextNode(''));
			}
			return frag;
		},

		// Convert a path like string into something that's ok for an `element` ID.
		toId : function( src ) {
			return can.map(src.toString().split(/\/|\./g), function( part ) {
				// Dont include empty strings in toId functions
				if ( part ) {
					return part;
				}
			}).join("_");
		},
		
		hookup: function(fragment, parentNode ){
			var hookupEls = [],
				id, 
				func;
			
			// Get all `childNodes`.
			can.each(fragment.childNodes ? can.makeArray(fragment.childNodes) : fragment, function(node){
				if(node.nodeType === 1){
					hookupEls.push(node);
					hookupEls.push.apply(hookupEls, can.makeArray( node.getElementsByTagName('*')));
				}
			});


			// Filter by `data-view-id` attribute.
			can.each( hookupEls, function( el ) {
				if ( el.getAttribute && (id = el.getAttribute('data-view-id')) && (func = $view.hookups[id]) ) {
					func(el, parentNode, id);
					delete $view.hookups[id];
					el.removeAttribute('data-view-id');
				}
			});

			return fragment;
		},

		/**
		 * @function ejs
		 *
		 * `can.view.ejs(id, template)` registers an EJS template string for a given id programatically.
		 *
		 *      can.view.ejs('myViewEJS', '<h2><%= message %></h2>');
		 *      var text = can.view.render('myViewEJS', {
		 *          message : 'Hello there!'
		 *      });
		 *      text // -> <h2>Hello there!</h2>
		 *
		 * You can also retrieve nameless mustache renderers:
		 *
		 *      var renderer = can.view.mustache('<div><%= message %></div>');
		 *      renderer({
		 *          message : 'EJS'
		 *      }); // -> <div>EJS</div>
		 *
		 * @param {String} id The template id or templates string to get a nameless renderer function
		 * @param {String} [template] The EJS template string when registered with an id
		 */
		//
		/**
		 * @function mustache
		 *
		 * `can.view.mustache(id, template)` registers an EJS template string for a given id programatically.
		 *
		 *      can.view.mustache('myViewMustache', '<h2>{{message}}</h2>');
		 *      var text = can.view.render('myViewMustache', {
		 *          message : 'Hello there!'
		 *      });
		 *      text // -> <h2>Hello there!</h2>
		 *
		 * You can also retrieve nameless mustache renderers:
		 *
		 *      var renderer = can.view.mustache('<div>{{message}}</div>');
		 *      renderer({
		 *          message : 'Mustache'
		 *      }); // -> <div>Mustache</div>
		 *
		 * @param {String} id The template id or templates string to get a nameless renderer function
		 * @param {String} [template] The Mustache template string when registered with an id
		 */
		//
		/**
		 * @attribute hookups
		 * @hide
		 * A list of pending 'hookups'
		 */
		hookups: {},

		/**
		 * @function hook
		 * Registers a hookup function that can be called back after the html is 
		 * put on the page.  Typically this is handled by the template engine.  Currently
		 * only EJS supports this functionality.
		 * 
		 *     var id = can.View.hookup(function(el){
		 *            //do something with el
		 *         }),
		 *         html = "<div data-view-id='"+id+"'>"
		 *     $('.foo').html(html);
		 * 
		 * 
		 * @param {Function} cb a callback function to be called with the element
		 * @param {Number} the hookup number
		 */
		hook: function( cb ) {
			$view.hookups[++hookupId] = cb;
			return " data-view-id='"+hookupId+"'";
		},

		/**
		 * @attribute cached
		 * @hide
		 * Cached are put in this object
		 */
		cached: {},

		cachedRenderers: {},

		/**
		 * @attribute cache
		 * By default, views are cached on the client.  If you'd like the
		 * the views to reload from the server, you can set the `cache` attribute to `false`.
		 *
		 * 		//- Forces loads from server
		 * 		can.view.cache = false; 
		 *
		 */
		cache: true,

		/**
		 * @function register
		 * Registers a template engine to be used with 
		 * view helpers and compression.  
		 * 
		 * ## Example
		 * 
		 * @codestart
		 * can.View.register({
		 * 	suffix : "tmpl",
		 *  plugin : "jquery/view/tmpl",
		 * 	renderer: function( id, text ) {
		 * 		return function(data){
		 * 			return jQuery.render( text, data );
		 * 		}
		 * 	},
		 * 	script: function( id, text ) {
		 * 		var tmpl = can.tmpl(text).toString();
		 * 		return "function(data){return ("+
		 * 		  	tmpl+
		 * 			").call(jQuery, jQuery, data); }";
		 * 	}
		 * })
		 * @codeend
		 * Here's what each property does:
		 * 
		 *    * plugin - the location of the plugin
		 *    * suffix - files that use this suffix will be processed by this template engine
		 *    * renderer - returns a function that will render the template provided by text
		 *    * script - returns a string form of the processed template function.
		 * 
		 * @param {Object} info a object of method and properties 
		 * 
		 * that enable template integration:
		 * <ul>
		 *   <li>plugin - the location of the plugin.  EX: 'jquery/view/ejs'</li>
		 *   <li>suffix - the view extension.  EX: 'ejs'</li>
		 *   <li>script(id, src) - a function that returns a string that when evaluated returns a function that can be 
		 *    used as the render (i.e. have func.call(data, data, helpers) called on it).</li>
		 *   <li>renderer(id, text) - a function that takes the id of the template and the text of the template and
		 *    returns a render function.</li>
		 * </ul>
		 */
		register: function( info ) {
			this.types["." + info.suffix] = info;
		},

		types: {},

		/**
		 * @attribute ext
		 * The default suffix to use if none is provided in the view's url.  
		 * This is set to `.ejs` by default.
		 *
		 * 		// Changes view ext to 'txt'
		 * 		can.view.ext = 'txt';
		 *
		 */
		ext: ".ejs",

		/**
		 * Returns the text that 
		 * @hide 
		 * @param {Object} type
		 * @param {Object} id
		 * @param {Object} src
		 */
		registerScript: function() {},

		/**
		 * @hide
		 * Called by a production script to pre-load a renderer function
		 * into the view cache.
		 * @param {String} id
		 * @param {Function} renderer
		 */
		preload: function( ) {},

		/**
		 * @function render
		 * `can.view.render(view, [data], [helpers], callback)` returns the rendered markup produced by the corresponding template
		 * engine as String. If you pass a deferred object in as data, render returns
		 * a deferred resolving to the rendered markup.
		 * 
		 * `can.view.render` is commonly used for sub-templates.
		 * 
		 * ## Example
		 * 
		 * _welcome.ejs_ looks like:
		 * 
		 *     <h1>Hello <%= hello %></h1>
		 * 
		 * Render it to a string like:
		 * 
		 *     can.view.render("welcome.ejs",{hello: "world"})
		 *       //-> <h1>Hello world</h1>
		 * 
		 * ## Use as a Subtemplate
		 * 
		 * If you have a template like:
		 * 
		 *     <ul>
		 *       <% list(items, function(item){ %>
		 *         <%== can.view.render("item.ejs",item) %>
		 *       <% }) %>
		 *     </ul>
		 *
		 * ## Using renderer functions
		 *
		 * If you only pass the view path, `can.view will return a renderer function that can be called with
		 * the data to render:
		 *
		 *     var renderer = can.view.render("welcome.ejs");
		 *     // Do some more things
		 *     renderer({hello: "world"}) // -> Document Fragment
		 * 
		 * @param {String|Object} view the path of the view template or a view object
		 * @param {Object} [data] the object passed to a template
		 * @param {Object} [helpers] additional helper methods to be passed to the view template
		 * @param {Function} [callback] function executed after template has been processed
		 * @param {String|Object|Function} returns a string of processed text or a deferred
		 * that resolves to the processed text or a renderer function when no data are passed.
		 * 
		 */
		render: function( view, data, helpers, callback ) {
			// If helpers is a `function`, it is actually a callback.
			if ( isFunction( helpers )) {
				callback = helpers;
				helpers = undefined;
			}

			// See if we got passed any deferreds.
			var deferreds = getDeferreds(data);

			if ( deferreds.length ) { // Does data contain any deferreds?
				// The deferred that resolves into the rendered content...
				var deferred = new can.Deferred();
	
				// Add the view request to the list of deferreds.
				deferreds.push(get(view, true))
	
				// Wait for the view and all deferreds to finish...
				can.when.apply(can, deferreds).then(function( resolved ) {
					// Get all the resolved deferreds.
					var objs = makeArray(arguments),
						// Renderer is the last index of the data.
						renderer = objs.pop(),
						// The result of the template rendering with data.
						result; 
	
					// Make data look like the resolved deferreds.
					if ( can.isDeferred(data) ) {
						data = usefulPart(resolved);
					}
					else {
						// Go through each prop in data again and
						// replace the defferreds with what they resolved to.
						for ( var prop in data ) {
							if ( can.isDeferred(data[prop]) ) {
								data[prop] = usefulPart(objs.shift());
							}
						}
					}

					// Get the rendered result.
					result = renderer(data, helpers);
	
					// Resolve with the rendered view.
					deferred.resolve(result); 

					// If there's a `callback`, call it back with the result.
					callback && callback(result);
				});
				// Return the deferred...
				return deferred;
			}
			else {
				// No deferreds! Render this bad boy.
				var response, 
					// If there's a `callback` function
					async = isFunction( callback ),
					// Get the `view` type
					deferred = get(view, async);
	
				// If we are `async`...
				if ( async ) {
					// Return the deferred
					response = deferred;
					// And fire callback with the rendered result.
					deferred.then(function( renderer ) {
						callback(data ? renderer(data, helpers) : renderer);
					})
				} else {
					// if the deferred is resolved, call the cached renderer instead
					// this is because it's possible, with recursive deferreds to
					// need to render a view while its deferred is _resolving_.  A _resolving_ deferred
					// is a deferred that was just resolved and is calling back it's success callbacks.
					// If a new success handler is called while resoliving, it does not get fired by
					// jQuery's deferred system.  So instead of adding a new callback
					// we use the cached renderer.
					// We also add __view_id on the deferred so we can look up it's cached renderer.
					// In the future, we might simply store either a deferred or the cached result.
					if(deferred.state() === "resolved" && deferred.__view_id  ){
						var currentRenderer = $view.cachedRenderers[ deferred.__view_id ];
						return data ? currentRenderer(data, helpers) : currentRenderer;
					} else {
						// Otherwise, the deferred is complete, so
						// set response to the result of the rendering.
						deferred.then(function( renderer ) {
							response = data ? renderer(data, helpers) : renderer;
						});
					}
					
				}
	
				return response;
			}
		},

		registerView: function( id, text, type, def ) {
			// Get the renderer function.
			var func = (type || $view.types[$view.ext]).renderer(id, text);
			def = def || new can.Deferred();
			
			// Cache if we are caching.
			if ( $view.cache ) {
				$view.cached[id] = def;
				def.__view_id = id;
				$view.cachedRenderers[id] = func;
			}

			// Return the objects for the response's `dataTypes`
			// (in this case view).
			return def.resolve(func);
		}
	});

	// Makes sure there's a template, if not, have `steal` provide a warning.
	var	checkText = function( text, url ) {
			if ( ! text.length ) {
				//!steal-remove-start
				steal.dev.log("There is no template or an empty template at " + url);
				//!steal-remove-end
				throw "can.view: No template or empty template:" + url;
			}
		},
		// `Returns a `view` renderer deferred.  
		// `url` - The url to the template.  
		// `async` - If the ajax request should be asynchronous.  
		// Returns a deferred.
		get = function( url, async ) {
			var suffix = url.match(/\.[\w\d]+$/),
			type, 
			// If we are reading a script element for the content of the template,
			// `el` will be set to that script element.
			el, 
			// A unique identifier for the view (used for caching).
			// This is typically derived from the element id or
			// the url for the template.
			id, 
			// The ajax request used to retrieve the template content.
			jqXHR;

			//If the url has a #, we assume we want to use an inline template
			//from a script element and not current page's HTML
			if( url.match(/^#/) ) {
				url = url.substr(1);
			}
			// If we have an inline template, derive the suffix from the `text/???` part.
			// This only supports `<script>` tags.
			if ( el = document.getElementById(url) ) {
				suffix = "."+el.type.match(/\/(x\-)?(.+)/)[2];
			}
	
			// If there is no suffix, add one.
			if (!suffix && !$view.cached[url] ) {
				url += ( suffix = $view.ext );
			}

			if ( can.isArray( suffix )) {
				suffix = suffix[0]
			}
	
			// Convert to a unique and valid id.
			id = $view.toId(url);
	
			// If an absolute path, use `steal` to get it.
			// You should only be using `//` if you are using `steal`.
			if ( url.match(/^\/\//) ) {
				var sub = url.substr(2);
				url = ! window.steal ? 
					sub :
					steal.config().root.mapJoin(sub);
			}
	
			// Set the template engine type.
			type = $view.types[suffix];
	
			// If it is cached, 
			if ( $view.cached[id] ) {
				// Return the cached deferred renderer.
				return $view.cached[id];
			
			// Otherwise if we are getting this from a `<script>` element.
			} else if ( el ) {
				// Resolve immediately with the element's `innerHTML`.
				return $view.registerView(id, el.innerHTML, type);
			} else {
				// Make an ajax request for text.
				var d = new can.Deferred();
				can.ajax({
					async: async,
					url: url,
					dataType: "text",
					error: function(jqXHR) {
						checkText("", url);
						d.reject(jqXHR);
					},
					success: function( text ) {
						// Make sure we got some text back.
						checkText(text, url);
						$view.registerView(id, text, type, d)
					}
				});
				return d;
			}
		},
		// Gets an `array` of deferreds from an `object`.
		// This only goes one level deep.
		getDeferreds = function( data ) {
			var deferreds = [];

			// pull out deferreds
			if ( can.isDeferred(data) ) {
				return [data]
			} else {
				for ( var prop in data ) {
					if ( can.isDeferred(data[prop]) ) {
						deferreds.push(data[prop]);
					}
				}
			}
			return deferreds;
		},
		// Gets the useful part of a resolved deferred.
		// This is for `model`s and `can.ajax` that resolve to an `array`.
		usefulPart = function( resolved ) {
			return can.isArray(resolved) && resolved[1] === 'success' ? resolved[0] : resolved
		};
	
	
	if ( window.steal ) {
		steal.type("view js", function( options, success, error ) {
			var type = $view.types["." + options.type],
				id = $view.toId(options.id);
			/**
			 * should return something like steal("dependencies",function(EJS){
			 * 	 return can.view.preload("ID", options.text)
			 * })
			 */
			options.text = "steal('" + (type.plugin || "can/view/" + options.type) + "',function(can){return " + "can.view.preload('" + id + "'," + options.text + ");\n})";
			success();
		})
	}

	//!steal-pluginify-remove-start
	can.extend($view, {
		register: function( info ) {
			this.types["." + info.suffix] = info;

			if ( window.steal ) {
				steal.type(info.suffix + " view js", function( options, success, error ) {
					var type = $view.types["." + options.type],
						id = $view.toId(options.id+'');

					options.text = type.script(id, options.text)
					success();
				})
			}
			$view[info.suffix] = function(id, text){
				if(!text) {
					// Return a nameless renderer
					return info.renderer(null, id);
				}

				$view.preload(id, info.renderer(id, text));
				return can.view(id);
			}
		},
		registerScript: function( type, id, src ) {
			return "can.view.preload('" + id + "'," + $view.types["." + type].script(id, src) + ");";
		},
		preload: function( id, renderer ) {
			$view.cached[id] = new can.Deferred().resolve(function( data, helpers ) {
				return renderer.call(data, data, helpers);
			});
			return function(){
				return $view.frag(renderer.apply(this,arguments))
			};
		}

	});
	//!steal-pluginify-remove-end

	return can;
});
