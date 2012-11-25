steal('can/util','can/observe', 'can/util/string/deparam', function(can) {

	// ## route.js  
	// `can.route`  
	// _Helps manage browser history (and client state) by synchronizing the 
	// `window.location.hash` with a `can.Observe`._  
	//   
    // Helper methods used for matching routes.
	var 
		// `RegExp` used to match route variables of the type ':name'.
        // Any word character or a period is matched.
        matcher = /\:([\w\.]+)/g,
        // Regular expression for identifying &amp;key=value lists.
        paramsMatcher = /^(?:&[^=]+=[^&]*)+/,
        // Converts a JS Object into a list of parameters that can be 
        // inserted into an html element tag.
		makeProps = function( props ) {
			var tags = [];
			can.each(props, function(val, name){
				tags.push( ( name === 'className' ? 'class'  : name )+ '="' + 
						(name === "href" ? val : can.esc(val) ) + '"');
			});
			return tags.join(" ");
		},
		// Checks if a route matches the data provided. If any route variable
        // is not present in the data, the route does not match. If all route
        // variables are present in the data, the number of matches is returned 
        // to allow discerning between general and more specific routes. 
		matchesData = function(route, data) {
			var count = 0, i = 0, defaults = {};
			// look at default values, if they match ...
			for( var name in route.defaults ) {
				if(route.defaults[name] === data[name]){
					// mark as matched
					defaults[name] = 1;
					count++;
				}
			}
			for (; i < route.names.length; i++ ) {
				if (!data.hasOwnProperty(route.names[i]) ) {
					return -1;
				}
				if(!defaults[route.names[i]]){
					count++;
				}
				
			}
			
			return count;
		},
		onready = !0,
		location = window.location,
		wrapQuote = function(str) {
			return (str+'').replace(/([.?*+\^$\[\]\\(){}|\-])/g, "\\$1");
		},
		each = can.each,
		extend = can.extend;


	can.route = function( url, defaults ) {
        defaults = defaults || {};
        // Extract the variable names and replace with `RegExp` that will match
		// an atual URL with values.
		var names = [],
			test = url.replace(matcher, function( whole, name, i ) {
				names.push(name);
				var next = "\\"+( url.substr(i+whole.length,1) || can.route._querySeparator );
				// a name without a default value HAS to have a value
				// a name that has a default value can be empty
				// The `\\` is for string-escaping giving single `\` for `RegExp` escaping.
				return "([^" +next+"]"+(defaults[name] ? "*" : "+")+")";
			});

		// Add route in a form that can be easily figured out.
		can.route.routes[url] = {
            // A regular expression that will match the route when variable values 
            // are present; i.e. for `:page/:type` the `RegExp` is `/([\w\.]*)/([\w\.]*)/` which
            // will match for any value of `:page` and `:type` (word chars or period).
			test: new RegExp("^" + test+"($|"+wrapQuote(can.route._querySeparator)+")"),
            // The original URL, same as the index for this entry in routes.
			route: url,
            // An `array` of all the variable names in this route.
			names: names,
            // Default values provided for the variables.
			defaults: defaults,
            // The number of parts in the URL separated by `/`.
			length: url.split('/').length
		};
		return can.route;
	};

	extend(can.route, {

		_querySeparator: '&',
		_paramsMatcher: paramsMatcher,

		/**
		 * @function can.route.param
		 * @parent can.route
		 * Parameterizes the raw JS object representation provided in data.
		 *
		 *     can.route.param( { type: "video", id: 5 } ) 
		 *          // -> "type=video&id=5"
		 *
		 * If a route matching the provided data is found, that URL is built
		 * from the data. Any remaining data is added at the end of the
		 * URL as &amp; separated key/value parameters.
		 *
		 *     can.route(":type/:id")
		 *     
		 *     can.route.param( { type: "video", id: 5 } ) // -> "video/5"
		 *     can.route.param( { type: "video", id: 5, isNew: false } ) 
		 *          // -> "video/5&isNew=false"
		 * 
		 * @param {Object} data Data object containing key/value properies to be parameterized
		 * @return {String} The route URL and &amp; separated parameters.
		 */
		param: function( data , _setRoute ) {
			// Check if the provided data keys match the names in any routes;
			// Get the one with the most matches.
			var route,
				// Need to have at least 1 match.
				matches = 0,
				matchCount,
				routeName = data.route,
				propCount = 0;
				
			delete data.route;
			
			each(data, function(){
				propCount++;
			});
			// Otherwise find route.
			each(can.route.routes, function(temp, name){
				// best route is the first with all defaults matching
				
				
				matchCount = matchesData(temp, data);
				if ( matchCount > matches ) {
					route = temp;
					matches = matchCount;
				}
				if(matchCount >= propCount){
					return false;
				}
			});
			// If we have a route name in our `can.route` data, and it's
			// just as good as what currently matches, use that
			if (can.route.routes[routeName] && matchesData(can.route.routes[routeName], data ) === matches) {
				route = can.route.routes[routeName];
			}
			// If this is match...
			if ( route ) {
				var cpy = extend({}, data),
                    // Create the url by replacing the var names with the provided data.
                    // If the default value is found an empty string is inserted.
					res = route.route.replace(matcher, function( whole, name ) {
                        delete cpy[name];
                        return data[name] === route.defaults[name] ? "" : encodeURIComponent( data[name] );
                    }),
                    after;
				// Remove matching default values
				each(route.defaults, function(val,name){
					if(cpy[name] === val) {
						delete cpy[name];
					}
				});
				
				// The remaining elements of data are added as 
				// `&amp;` separated parameters to the url.
				after = can.param(cpy);
				// if we are paraming for setting the hash
				// we also want to make sure the route value is updated
				if(_setRoute){
					can.route.attr('route',route.route);
				}
				return res + (after ? can.route._querySeparator + after : "");
			}
            // If no route was found, there is no hash URL, only paramters.
			return can.isEmptyObject(data) ? "" : can.route._querySeparator + can.param(data);
		},
		/**
		 * @function can.route.deparam
		 * @parent can.route
		 * 
		 * Creates a data object based on the query string passed into it. This is 
		 * useful to create an object based on the `location.hash`.
		 *
		 *     can.route.deparam("id=5&type=videos") 
		 *          // -> { id: 5, type: "videos" }
		 *
		 * 
		 * It's important to make sure the hash or exclamantion point is not passed
		 * to `can.route.deparam` otherwise it will be included in the first property's
		 * name.
		 *
		 *     can.route.attr("id", 5) // location.hash -> #!id=5
		 *     can.route.attr("type", "videos") 
		 *          // location.hash -> #!id=5&type=videos
		 *     can.route.deparam(location.hash) 
		 *          // -> { #!id: 5, type: "videos" }
		 *
		 * `can.route.deparam` will try and find a matching route and, if it does,
		 * will deconstruct the URL and parse our the key/value parameters into the data object.
		 *
		 *     can.route(":type/:id")
		 *
		 *     can.route.deparam("videos/5");
		 *          // -> { id: 5, route: ":type/:id", type: "videos" }
		 *
		 * @param {String} url Query string to be turned into an object.
		 * @return {Object} Data object containing properties and values from the string
		 */
		deparam: function( url ) {
			// See if the url matches any routes by testing it against the `route.test` `RegExp`.
            // By comparing the URL length the most specialized route that matches is used.
			var route = {
				length: -1
			};
			each(can.route.routes, function(temp, name){
				if ( temp.test.test(url) && temp.length > route.length ) {
					route = temp;
				}
			});
            // If a route was matched.
			if ( route.length > -1 ) { 

				var // Since `RegExp` backreferences are used in `route.test` (parens)
                    // the parts will contain the full matched string and each variable (back-referenced) value.
                    parts = url.match(route.test),
                    // Start will contain the full matched string; parts contain the variable values.
					start = parts.shift(),
                    // The remainder will be the `&amp;key=value` list at the end of the URL.
					remainder = url.substr(start.length - (parts[parts.length-1] === can.route._querySeparator ? 1 : 0) ),
                    // If there is a remainder and it contains a `&amp;key=value` list deparam it.
                    obj = (remainder && can.route._paramsMatcher.test(remainder)) ? can.deparam( remainder.slice(1) ) : {};

                // Add the default values for this route.
				obj = extend(true, {}, route.defaults, obj);
                // Overwrite each of the default values in `obj` with those in 
				// parts if that part is not empty.
				each(parts,function(part,  i){
					if ( part && part !== can.route._querySeparator) {
						obj[route.names[i]] = decodeURIComponent( part );
					}
				});
				obj.route = route.route;
				return obj;
			}
            // If no route was matched, it is parsed as a `&amp;key=value` list.
			if ( url.charAt(0) !== can.route._querySeparator ) {
				url = can.route._querySeparator + url;
			}
			return can.route._paramsMatcher.test(url) ? can.deparam( url.slice(1) ) : {};
		},
		/**
		 * @hide
		 * A can.Observe that represents the state of the history.
		 */
		data: new can.Observe({}),
        /**
         * @attribute
         * @type Object
		 * @hide
		 * 
         * A list of routes recognized by the router indixed by the url used to add it.
         * Each route is an object with these members:
         * 
		 *  - test - A regular expression that will match the route when variable values 
         *    are present; i.e. for :page/:type the `RegExp` is /([\w\.]*)/([\w\.]*)/ which
         *    will match for any value of :page and :type (word chars or period).
		 * 
         *  - route - The original URL, same as the index for this entry in routes.
         * 
		 *  - names - An array of all the variable names in this route
         * 
		 *  - defaults - Default values provided for the variables or an empty object.
         * 
		 *  - length - The number of parts in the URL separated by '/'.
         */
		routes: {},
		/**
		 * @function can.route.ready
		 * @parent can.route
		 * Indicates that all routes have been added and sets can.route.data
		 * based upon the routes and the current hash.
		 * 
		 * By default, ready is fired on jQuery's ready event.  Sometimes
		 * you might want it to happen sooner or earlier.  To do this, call:
		 * 
		 *     can.route.ready(false); //prevents firing by the ready event
		 *     can.route.ready(true); // fire the first route change
		 * 
		 * @param {Boolean} [val] Whether or not to fire the ready event.
		 * @return {can.route} `can.route` object.
		 */
		ready: function(val) {
			if( val === false ) {
				onready = val;
			}
			if( val === true || onready === true ) {
				can.route._setup();
				setState();
			}
			return can.route;
		},
		/**
		 * @function can.route.url
		 * @parent can.route
		 * 
		 * Similar to [can.route.link], but instead of creating an anchor tag, `can.route.url` creates 
		 * only the URL based on the route options passed into it.
		 *
		 *     can.route.url( { type: "videos", id: 5 } ) 
		 *          // -> "#!type=videos&id=5"
		 *
		 * If a route matching the provided data is found the URL is built from the data. Any remaining
		 * data is added at the end of the URL as & separated key/value parameters.
		 *
		 *     can.route(":type/:id")
		 *
		 *     can.route.url( { type: "videos", id: 5 } ) // -> "#!videos/5"
		 *     can.route.url( { type: "video", id: 5, isNew: false } ) 
		 *          // -> "#!video/5&isNew=false"
		 *
		 *
		 * @param {Object} options The route options (variables)
		 * @param {Boolean} merge true if the options should be merged with the current options
		 * @return {String} The route URL & separated parameters
		 */
		url: function( options, merge ) {
			if (merge) {
				options = extend({}, curParams, options)
			}
			return "#!" + can.route.param(options);
		},
		/**
		 * @function can.route.link
		 * @parent can.route
		 * 
		 * Creates and returns an anchor tag with an href of the route 
		 * attributes passed into it, as well as any properies desired
		 * for the tag.
		 *
		 *     can.route.link( "My videos", { type: "videos" }, {}, false )
		 *          // -> <a href="#!type=videos">My videos</a>
		 * 
		 * Other attributes besides href can be added to the anchor tag
		 * by passing in a data object with the attributes desired.
		 *
		 *     can.route.link( "My videos", { type: "videos" }, 
		 *       { className: "new" }, false ) 
		 *          // -> <a href="#!type=videos" class="new">My Videos</a>
		 *
		 * It is possible to utilize the current route options when making anchor
		 * tags in order to make your code more reusable. If merge is set to true,
		 * the route options passed into `can.route.link` will be passed into the
		 * current ones.
		 *
		 *     location.hash = "#!type=videos" 
		 *     can.route.link( "The zoo", { id: 5 }, true )
		 *          // -> <a href="#!type=videos&id=5">The zoo</true>
		 *
		 *     location.hash = "#!type=pictures" 
		 *     can.route.link( "The zoo", { id: 5 }, true )
		 *          // -> <a href="#!type=pictures&id=5">The zoo</true>
		 *
		 *
		 * @param {Object} name The text of the link.
		 * @param {Object} options The route options (variables)
		 * @param {Object} props Properties of the &lt;a&gt; other than href.
		 * @param {Boolean} merge true if the options should be merged with the current options
		 * @return {string} String containing the formatted &lt;a&gt; HTML element
		 */
		link: function( name, options, props, merge ) {
			return "<a " + makeProps(
			extend({
				href: can.route.url(options, merge)
			}, props)) + ">" + name + "</a>";
		},
		/**
		 * @function can.route.current
		 * @parent can.route
		 * 
		 * Checks the page's current URL to see if the route represents the options passed 
		 * into the function.
		 *
		 * Returns true if the options respresent the current URL.
		 * 
		 *     can.route.attr('id', 5) // location.hash -> "#!id=5"
		 *     can.route.current({ id: 5 }) // -> true
		 *     can.route.current({ id: 5, type: 'videos' }) // -> false
		 *     
		 *     can.route.attr('type', 'videos') 
		 *            // location.hash -> #!id=5&type=videos
		 *     can.route.current({ id: 5, type: 'videos' }) // -> true
		 * 
		 * 
		 * @param {Object} options Data object containing properties and values that might represent the route.
         * @return {Boolean} Whether or not the options match the current URL.
		 */
		current: function( options ) {
			return location.hash == "#!" + can.route.param(options)
		},
		_setup: function() {
			// If the hash changes, update the `can.route.data`.
			can.bind.call(window,'hashchange', setState);
		},
		_getHash: function() {
			return location.href.split(/#!?/)[1] || "";
		},
		_setHash: function(serialized) {
			var path = (can.route.param(serialized, true));
			location.hash = "#!" + path;
			return path;
		}
	});
	
	
    // The functions in the following list applied to `can.route` (e.g. `can.route.attr('...')`) will
    // instead act on the `can.route.data` observe.
	each(['bind','unbind','delegate','undelegate','attr','removeAttr'], function(name){
		can.route[name] = function(){
			return can.route.data[name].apply(can.route.data, arguments)
		}
	})

	var // A ~~throttled~~ debounced function called multiple times will only fire once the
        // timer runs down. Each call resets the timer.
		timer,
        // Intermediate storage for `can.route.data`.
        curParams,
        // Deparameterizes the portion of the hash of interest and assign the
        // values to the `can.route.data` removing existing values no longer in the hash.
        // setState is called typically by hashchange which fires asynchronously
        // So it's possible that someone started changing the data before the 
        // hashchange event fired.  For this reason, it will not set the route data
        // if the data is changing or the hash already matches the hash that was set.
        setState = can.route.setState = function() {
			var hash = can.route._getHash();
			curParams = can.route.deparam( hash );
			
			// if the hash data is currently changing, or
			// the hash is what we set it to anyway, do NOT change the hash
			if(!changingData || hash !== lastHash){
				can.route.attr(curParams, true);
			}
		},
		// The last hash caused by a data change
		lastHash,
		// Are data changes pending that haven't yet updated the hash
		changingData;

	// If the `can.route.data` changes, update the hash.
    // Using `.serialize()` retrieves the raw data contained in the `observable`.
    // This function is ~~throttled~~ debounced so it only updates once even if multiple values changed.
    // This might be able to use batchNum and avoid this.
	can.route.bind("change", function(ev, attr) {
		// indicate that data is changing
		changingData = 1;
		clearTimeout( timer );
		timer = setTimeout(function() {
			// indicate that the hash is set to look like the data
			changingData = 0;
			var serialized = can.route.data.serialize();

			lastHash = can.route._setHash(serialized);
		}, 1);
	});
	// `onready` event...
	can.bind.call(document,"ready",can.route.ready);

	// extend route to have a similar property 
	// that is often checked in mustache to determine
	// an object's observability
	can.route.constructor.canMakeObserve = can.Observe.canMakeObserve;

	return can.route;
});
