module = { _orig: window.module, _define: window.define };
module['jquery'] = $;
define = function(id, deps, value) {
	module[id] = value();
};
define.amd = { jQuery: true };
// ## can/util/can.js

module['can/util/can.js'] = (function(){
	window.can = window.can || {};
	window.can.isDeferred = function( obj ) {
		var isFunction = this.isFunction;
		// Returns `true` if something looks like a deferred.
		return obj && isFunction(obj.then) && isFunction(obj.pipe);
	};
	return window.can;
})();// ## can/util/preamble.js

module['can/util/preamble.js'] = (function() {
// # CanJS v#{VERSION}
// (c) 2012 Bitovi  
// MIT license  
// [http://canjs.us/](http://canjs.us/)
})();// ## can/util/array/each.js

module['can/util/array/each.js'] = (function (can) {
	can.each = function (elements, callback, context) {
		var i = 0, key;
		if (elements) {
			if (typeof elements.length === 'number' && elements.pop) {
				if ( elements.attr ) {
					elements.attr('length');
				}
				for (key = elements.length; i < key; i++) {
					if (callback.call(context || elements[i], elements[i], i, elements) === false) {
						break;
					}
				}
			} else {
				for (key in elements) {
					if (callback.call(context || elements[key], elements[key], key, elements) === false) {
						break;
					}
				}
			}
		}
		return elements;
	};
})(module["can/util/can.js"]);// ## can/util/jquery/jquery.js

module['can/util/jquery/jquery.js'] = (function($, can) {
	// jquery.js
	// ---------
	// _jQuery node list._
	$.extend( can, jQuery, {
		trigger: function( obj, event, args ) {
			if ( obj.trigger ) {
				obj.trigger( event, args );
			} else {
				$.event.trigger( event, args, obj, true );
			}
		},
		addEvent: function(ev, cb){
			$([this]).bind(ev, cb);
			return this;
		},
		removeEvent: function(ev, cb){
			$([this]).unbind(ev, cb);
			return this;
		},
		// jquery caches fragments, we always needs a new one
		buildFragment : function(result, element){
			var ret = $.buildFragment([result],$(element));
			return ret.cacheable ? $.clone(ret.fragment) : ret.fragment;
		},
		$: jQuery,
		each: can.each
	});

	// Wrap binding functions.
	$.each(['bind','unbind','undelegate','delegate'],function(i,func){
		can[func] = function(){
			var t = this[func] ? this : $([this]);
			t[func].apply(t, arguments);
			return this;
		};
	});

	// Wrap modifier functions.
	$.each(["append","filter","addClass","remove","data","get"], function(i,name){
		can[name] = function(wrapped){
			return wrapped[name].apply(wrapped, can.makeArray(arguments).slice(1));
		};
	});

	// Memory safe destruction.
	var oldClean = $.cleanData;

	$.cleanData = function( elems ) {
		$.each( elems, function( i, elem ) {
			if ( elem ) {
				can.trigger(elem,"destroyed",[],false);
			}
		});
		oldClean(elems);
	};

	return can;
})(module["jquery"], module["can/util/can.js"], module["jquery"], module["can/util/preamble.js"], module["can/util/array/each.js"]);// ## can/util/string/string.js

module['can/util/string/string.js'] = (function(can) {
	// ##string.js
	// _Miscellaneous string utility functions._  
	
	// Several of the methods in this plugin use code adapated from Prototype
	// Prototype JavaScript framework, version 1.6.0.1.
	// © 2005-2007 Sam Stephenson
	var undHash     = /_|-/,
		colons      = /\=\=/,
		words       = /([A-Z]+)([A-Z][a-z])/g,
		lowUp       = /([a-z\d])([A-Z])/g,
		dash        = /([a-z\d])([A-Z])/g,
		replacer    = /\{([^\}]+)\}/g,
		quote       = /"/g,
		singleQuote = /'/g,

		// Returns the `prop` property from `obj`.
		// If `add` is true and `prop` doesn't exist in `obj`, create it as an 
		// empty object.
		getNext = function( obj, prop, add ) {
			return prop in obj ?
				obj[ prop ] : 
				( add && ( obj[ prop ] = {} ));
		},

		// Returns `true` if the object can have properties (no `null`s).
		isContainer = function( current ) {
			return (/^f|^o/).test( typeof current );
		};

		can.extend(can, {
			// Escapes strings for HTML.
			/**
			 * @function can.esc
			 * @parent can.util
			 *
			 * `can.esc(string)` escapes a string for insertion into html.
			 * 
			 *     can.esc( "<foo>&<bar>" ) //-> "&lt;foo&lt;&amp;&lt;bar&lt;"
			 */
			esc : function( content ) {
				// Convert bad values into empty strings
				var isInvalid = content === null || content === undefined || (isNaN(content) && ("" + content === 'NaN'));
				return ( "" + ( isInvalid ? '' : content ) )
					.replace(/&/g, '&amp;')
					.replace(/</g, '&lt;')
					.replace(/>/g, '&gt;')
					.replace(quote, '&#34;')
					.replace(singleQuote, "&#39;");
			},
			
			/**
			 * @function can.getObject
			 * @parent can.util
			 * Gets an object from a string.  It can also modify objects on the
			 * 'object path' by removing or adding properties.
			 * 
			 *     Foo = {Bar: {Zar: {"Ted"}}}
			 *     can.getObject("Foo.Bar.Zar") //-> "Ted"
			 * 
			 * @param {String} name the name of the object to look for
			 * @param {Array} [roots] an array of root objects to look for the 
			 *   name.  If roots is not provided, the window is used.
			 * @param {Boolean} [add] true to add missing objects to 
			 *  the path. false to remove found properties. undefined to 
			 *  not modify the root object
			 * @return {Object} The object.
			 */
			getObject : function( name, roots, add ) {
			
				// The parts of the name we are looking up  
				// `['App','Models','Recipe']`
				var	parts = name ? name.split('.') : [],
					length =  parts.length,
					current,
					r = 0,
					ret, i;

				// Make sure roots is an `array`.
				roots = can.isArray(roots) ? roots : [roots || window];
				
				if ( ! length ) {
					return roots[0];
				}

				// For each root, mark it as current.
				while ( roots[r] ) {
					current = roots[r];

					// Walk current to the 2nd to last object or until there 
					// is not a container.
					for (i =0; i < length - 1 && isContainer( current ); i++ ) {
						current = getNext( current, parts[i], add );
					}

					// If we can get a property from the 2nd to last object...
					if( isContainer(current) ) {
						
						// Get (and possibly set) the property.
						ret = getNext(current, parts[i], add); 
						
						// If there is a value, we exit.
						if ( ret !== undefined ) {
							// If `add` is `false`, delete the property
							if ( add === false ) {
								delete current[parts[i]];
							}
							return ret;
							
						}
					}
					r++;
				}
			},
			// Capitalizes a string.
			/**
			 * @function can.capitalize
			 * @parent can.util
			 * `can.capitalize(string)` capitalizes the first letter of the string passed.
			 *
			 *		can.capitalize('candy is fun!'); //-> Returns: 'Candy is fun!'
			 *
			 * @param {String} s the string.
			 * @return {String} a string with the first character capitalized.
			 */
			capitalize: function( s, cache ) {
				// Used to make newId.
				return s.charAt(0).toUpperCase() + s.slice(1);
			},
			
			// Underscores a string.
			/**
			 * @function can.underscore
			 * @parent can.util
			 * 
			 * Underscores a string.
			 * 
			 *     can.underscore("OneTwo") //-> "one_two"
			 * 
			 * @param {String} s
			 * @return {String} the underscored string
			 */
			underscore: function( s ) {
				return s
					.replace(colons, '/')
					.replace(words, '$1_$2')
					.replace(lowUp, '$1_$2')
					.replace(dash, '_')
					.toLowerCase();
			},
			// Micro-templating.
			/**
			 * @function can.sub
			 * @parent can.util
			 * 
			 * Returns a string with {param} replaced values from data.
			 * 
			 *     can.sub("foo {bar}",{bar: "far"})
			 *     //-> "foo far"
			 *     
			 * @param {String} s The string to replace
			 * @param {Object} data The data to be used to look for properties.  If it's an array, multiple
			 * objects can be used.
			 * @param {Boolean} [remove] if a match is found, remove the property from the object
			 */
			sub: function( str, data, remove ) {

				var obs = [];

				obs.push( str.replace( replacer, function( whole, inside ) {

					// Convert inside to type.
					var ob = can.getObject( inside, data, remove === undefined? remove : !remove );
					
					// If a container, push into objs (which will return objects found).
					if ( isContainer( ob ) ) {
						obs.push( ob );
						return "";
					} else {
						return "" + ob;
					}
				}));
				
				return obs.length <= 1 ? obs[0] : obs;
			},

			// These regex's are used throughout the rest of can, so let's make
			// them available.
			replacer : replacer,
			undHash : undHash
		});
	return can;
})(module["can/util/jquery/jquery.js"]);// ## can/construct/construct.js

module['can/construct/construct.js'] = (function(can) {

	// ## construct.js
	// `can.Construct`  
	// _This is a modified version of
	// [John Resig's class](http://ejohn.org/blog/simple-javascript-inheritance/).  
	// It provides class level inheritance and callbacks._
	
	// A private flag used to initialize a new class instance without
	// initializing it's bindings.
	var initializing = 0;

	/** 
	 * @add can.Construct 
	 */
	can.Construct = function() {
		if (arguments.length) {
			return can.Construct.extend.apply(can.Construct, arguments);
		}
	};

	/**
	 * @static
	 */
	can.extend(can.Construct, {
		/**
		 * @function newInstance
		 * Creates a new instance of the constructor function.  This method is useful for creating new instances
		 * with arbitrary parameters.  Typically you want to simply use the __new__ operator instead.
		 * 
		 * ## Example
		 * 
		 * The following creates a `Person` Construct and then creates a new instance of person, but
		 * by using `apply` on newInstance to pass arbitrary parameters.
		 * 
		 *     var Person = can.Construct({
		 *       init : function(first, middle, last) {
		 *         this.first = first;
		 *         this.middle = middle;
		 *         this.last = last;
		 *       }
		 *     });
		 * 
		 *     var args = ["Justin","Barry","Meyer"],
		 *         justin = new Person.newInstance.apply(null, args);
		 * 
		 * @param {Object} [args] arguments that get passed to [can.Construct::setup] and [can.Construct::init]. Note
		 * that if [can.Construct::setup] returns an array, those arguments will be passed to [can.Construct::init]
		 * instead.
		 * @return {class} instance of the class
		 */
		newInstance: function() {
			// Get a raw instance object (`init` is not called).
			var inst = this.instance(),
				arg = arguments,
				args;
				
			// Call `setup` if there is a `setup`
			if ( inst.setup ) {
				args = inst.setup.apply(inst, arguments);
			}

			// Call `init` if there is an `init`  
			// If `setup` returned `args`, use those as the arguments
			if ( inst.init ) {
				inst.init.apply(inst, args || arguments);
			}

			return inst;
		},
		// Overwrites an object with methods. Used in the `super` plugin.
		// `newProps` - New properties to add.  
		// `oldProps` - Where the old properties might be (used with `super`).  
		// `addTo` - What we are adding to.
		_inherit: function( newProps, oldProps, addTo ) {
			can.extend(addTo || newProps, newProps || {})
		},
		// used for overwriting a single property.
		// this should be used for patching other objects
		// the super plugin overwrites this
		_overwrite : function(what, oldProps, propName, val){
			what[propName] = val;
		},
		// Set `defaults` as the merger of the parent `defaults` and this 
		// object's `defaults`. If you overwrite this method, make sure to
		// include option merging logic.
		/**
		 * Setup is called immediately after a constructor function is created and 
		 * set to inherit from its base constructor.  It is called with a base constructor and
		 * the params used to extend the base constructor. It is useful for setting up additional inheritance work.
		 * 
		 * ## Example
		 * 
		 * The following creates a `Base` class that when extended, adds a reference to the base
		 * class.
		 * 
		 * 
		 *     Base = can.Construct({
		 *       setup : function(base, fullName, staticProps, protoProps){
		 * 	       this.base = base;
		 *         // call base functionality
		 *         can.Construct.setup.apply(this, arguments)
		 *       }
		 *     },{});
		 * 
		 *     Base.base //-> can.Construct
		 *     
		 *     Inherting = Base({});
		 * 
		 *     Inheriting.base //-> Base
		 * 
		 * ## Base Functionality
		 * 
		 * Setup deeply extends the static `defaults` property of the base constructor with 
		 * properties of the inheriting constructor.  For example:
		 * 
		 *     MyBase = can.Construct({
		 *       defaults : {
		 *         foo: 'bar'
		 *       }
		 *     },{})
		 * 
		 *     Inheriting = MyBase({
		 *       defaults : {
		 *         newProp : 'newVal'
		 *       }
		 *     },{}
		 *     
		 *     Inheriting.defaults // -> {foo: 'bar', 'newProp': 'newVal'}
		 * 
		 * @param {Object} base the base constructor that is being inherited from
		 * @param {String} [fullName] the name of the new constructor
		 * @param {Object} [staticProps] the static properties of the new constructor
		 * @param {Object} [protoProps] the prototype properties of the new constructor
		 */
		setup: function( base, fullName ) {
			this.defaults = can.extend(true,{}, base.defaults, this.defaults);
		},
		// Create's a new `class` instance without initializing by setting the
		// `initializing` flag.
		instance: function() {

			// Prevents running `init`.
			initializing = 1;

			var inst = new this();

			// Allow running `init`.
			initializing = 0;

			return inst;
		},
		// Extends classes.
		/**
		 * @hide
		 * Extends a class with new static and prototype functions.  There are a variety of ways
		 * to use extend:
		 * 
		 *     // with className, static and prototype functions
		 *     can.Construct('Task',{ STATIC },{ PROTOTYPE })
		 *     // with just classname and prototype functions
		 *     can.Construct('Task',{ PROTOTYPE })
		 *     // with just a className
		 *     can.Construct('Task')
		 * 
		 * You no longer have to use <code>.extend</code>.  Instead, you can pass those options directly to
		 * can.Construct (and any inheriting classes):
		 * 
		 *     // with className, static and prototype functions
		 *     can.Construct('Task',{ STATIC },{ PROTOTYPE })
		 *     // with just classname and prototype functions
		 *     can.Construct('Task',{ PROTOTYPE })
		 *     // with just a className
		 *     can.Construct('Task')
		 * 
		 * @param {String} [fullName]  the classes name (used for classes w/ introspection)
		 * @param {Object} [klass]  the new classes static/class functions
		 * @param {Object} [proto]  the new classes prototype functions
		 * 
		 * @return {can.Construct} returns the new class
		 */
		extend: function( fullName, klass, proto ) {
			// Figure out what was passed and normalize it.
			if ( typeof fullName != 'string' ) {
				proto = klass;
				klass = fullName;
				fullName = null;
			}

			if ( ! proto ) {
				proto = klass;
				klass = null;
			}
			proto = proto || {};

			var _super_class = this,
				_super = this.prototype,
				name, shortName, namespace, prototype;

			// Instantiate a base class (but only create the instance,
			// don't run the init constructor).
			prototype = this.instance();
			
			// Copy the properties over onto the new prototype.
			can.Construct._inherit(proto, _super, prototype);

			// The dummy class constructor.
			function Constructor() {
				// All construction is actually done in the init method.
				if ( ! initializing ) {
					return this.constructor !== Constructor && arguments.length ?
						// We are being called without `new` or we are extending.
						arguments.callee.extend.apply(arguments.callee, arguments) :
						// We are being called with `new`.
						this.constructor.newInstance.apply(this.constructor, arguments);
				}
			}

			// Copy old stuff onto class (can probably be merged w/ inherit)
			for ( name in _super_class ) {
				if ( _super_class.hasOwnProperty(name) ) {
					Constructor[name] = _super_class[name];
				}
			}

			// Copy new static properties on class.
			can.Construct._inherit(klass, _super_class, Constructor);

			// Setup namespaces.
			if ( fullName ) {

				var parts = fullName.split('.'),
					shortName = parts.pop(),
					current = can.getObject(parts.join('.'), window, true),
					namespace = current,
					_fullName = can.underscore(fullName.replace(/\./g, "_")),
					_shortName = can.underscore(shortName);

				
				
				current[shortName] = Constructor;
			}

			// Set things that shouldn't be overwritten.
			can.extend(Constructor, {
				constructor: Constructor,
				prototype: prototype,
				/**
				 * @attribute namespace 
				 * The namespace keyword is used to declare a scope. This enables you to organize
				 * code and provides a way to create globally unique types.
				 * 
				 *     can.Construct("MyOrg.MyConstructor",{},{})
				 *     MyOrg.MyConstructor.namespace //-> MyOrg
				 * 
				 */
				namespace: namespace,
				/**
				 * @attribute shortName
				 * If you pass a name when creating a Construct, the `shortName` property will be set to the
				 * actual name without the namespace:
				 * 
				 *     can.Construct("MyOrg.MyConstructor",{},{})
				 *     MyOrg.MyConstructor.shortName //-> 'MyConstructor'
				 *     MyOrg.MyConstructor.fullName //->  'MyOrg.MyConstructor'
				 * 
				 */
				shortName: shortName,
				_shortName : _shortName,
				/**
				 * @attribute fullName 
				 * If you pass a name when creating a Construct, the `fullName` property will be set to
				 * the actual name including the full namespace:
				 * 
				 *     can.Construct("MyOrg.MyConstructor",{},{})
				 *     MyOrg.MyConstructor.shortName //-> 'MyConstructor'
				 *     MyOrg.MyConstructor.fullName //->  'MyOrg.MyConstructor'
				 * 
				 */
				fullName: fullName,
				_fullName: _fullName
			});

			// Make sure our prototype looks nice.
			Constructor.prototype.constructor = Constructor;

			
			// Call the class `setup` and `init`
			var t = [_super_class].concat(can.makeArray(arguments)),
				args = Constructor.setup.apply(Constructor, t );
			
			if ( Constructor.init ) {
				Constructor.init.apply(Constructor, args || t );
			}

			/**
			 * @prototype
			 */
			return Constructor;
			/** 
			 * @function setup
			 * 
			 * If a prototype `setup` method is provided, it is called when a new 
			 * instance is created.  It is passed the same arguments that
			 * were passed to the Constructor constructor 
			 * function (`new Constructor( arguments ... )`).  If `setup` returns an
			 * array, those arguments are passed to [can.Construct::init] instead
			 * of the original arguments.
			 * 
			 * Typically, you should only provide [can.Construct::init] methods to 
			 * handle initilization code. Use `setup` for:
			 * 
			 *   - initialization code that you want to run before inheriting constructor's 
			 *     init method is called.
			 *   - initialization code that should run without inheriting constructors having to 
			 *     call base methods (ex: `MyBase.prototype.init.call(this, arg1)`).
			 *   - passing modified/normalized arguments to `init`.
			 * 
			 * ## Examples
			 * 
			 * The following is similar to code in [can.Control]'s setup method that
			 * converts the first argument to a jQuery collection and extends the 
			 * second argument with the constructor's [can.Construct.defaults defaults]:
			 * 
			 *     can.Construct("can.Control",{
			 *       setup: function( htmlElement, rawOptions ) {
			 *         // set this.element
			 *         this.element = $(htmlElement);
			 * 
			 *         // set this.options
			 *         this.options = can.extend( {}, 
			 * 	                               this.constructor.defaults, 
			 * 	                               rawOptions );
			 * 
			 *         // pass the wrapped element and extended options
			 *         return [this.element, this.options] 
			 *       }
			 *     })
			 * 
			 * ## Base Functionality
			 * 
			 * Setup is not defined on can.Construct itself, so calling super in inherting classes
			 * will break.  Don't do the following:
			 * 
			 *     Thing = can.Construct({
			 *       setup : function(){
			 *         this._super(); // breaks!
			 *       }
			 *     })
			 * 
			 * @return {Array|undefined} If an array is return, [can.Construct.prototype.init] is 
			 * called with those arguments; otherwise, the original arguments are used.
			 */
			//  
			/** 
			 * @function init
			 * 
			 * If a prototype `init` method is provided, it gets called after [can.Construct::setup] when a new instance
			 * is created. The `init` method is where your constructor code should go. Typically,
			 * you will find it saving the arguments passed to the constructor function for later use. 
			 * 
			 * ## Examples
			 * 
			 * The following creates a Person constructor with a first and last name property:
			 * 
			 *     var Person = can.Construct({
			 *       init : function(first, last){
			 *         this.first = first;
			 *         this.last = last;
			 *       }
			 *     })
			 * 
			 *     var justin = new Person("Justin","Meyer");
			 *     justin.first //-> "Justin"
			 *     justin.last  //-> "Meyer"
			 * 
			 * The following extends person to create a Programmer constructor
			 * 
			 *     var Programmer = Person({
			 *       init : function(first, last, lang){
			 *         // call base functionality
			 *         Person.prototype.init.call(this, first, last);
			 * 
			 *         // save the lang
			 *         this.lang = lang
			 *       },
			 *       greet : function(){
			 *         return "I am " + this.first + " " + this.last + ". " +
			 *                "I write " + this.lang + ".";
			 *       }
			 *     })
			 * 
			 *     var brian = new Programmer("Brian","Moschel","ECMAScript")
			 *     brian.greet() //-> "I am Brian Moschel.\
			 *                   //    I write ECMAScript."
			 * 
			 * ## Notes
			 * 
			 * [can.Construct::setup] is able to modify the arguments passed to init.
			 * 
			 * It doesn't matter what init returns because the `new` keyword always
			 * returns the new object.
			 */
			//  
			/**
			 * @attribute constructor
			 * 
			 * A reference to the constructor function that created the instance. It allows you to access
			 * the constructor function's static properties from an instance.
			 * 
			 * ## Example
			 * 
			 * Incrementing a static counter, that counts how many instances have been created:
			 * 
			 *     Counter = can.Construct({
			 * 	     count : 0
			 *     },{
			 *       init : function(){
			 *         this.constructor.count++;
			 *       }
			 *     })
			 * 
			 *     new Counter();
			 *     Counter.count //-> 1; 
			 * 
			 */
		}

	});
	return can.Construct;
})(module["can/util/string/string.js"]);// ## can/control/control.js

module['can/control/control.js'] = (function( can ) {
	// ## control.js
	// `can.Control`  
	// _Controller_
	
	// Binds an element, returns a function that unbinds.
	var bind = function( el, ev, callback ) {

			can.bind.call( el, ev, callback );

			return function() {
				can.unbind.call(el, ev, callback);
			};
		},
		isFunction = can.isFunction,
		extend = can.extend,
		each = can.each,
		slice = [].slice,
		paramReplacer = /\{([^\}]+)\}/g,
		special = can.getObject("$.event.special") || {},

		// Binds an element, returns a function that unbinds.
		delegate = function( el, selector, ev, callback ) {
			can.delegate.call(el, selector, ev, callback);
			return function() {
				can.undelegate.call(el, selector, ev, callback);
			};
		},
		
		// Calls bind or unbind depending if there is a selector.
		binder = function( el, ev, callback, selector ) {
			return selector ?
				delegate( el, can.trim( selector ), ev, callback ) : 
				bind( el, ev, callback );
		},
		
		basicProcessor;
	
	/**
	 * @add can.Control
	 */
	var Control = can.Control = can.Construct(
	/** 
	 * @Static
	 */
	{
		// Setup pre-processes which methods are event listeners.
		/**
		 * @hide
		 * 
		 * Setup pre-process which methods are event listeners.
		 * 
		 */
		setup: function() {

			// Allow contollers to inherit "defaults" from super-classes as it 
			// done in `can.Construct`
			can.Construct.setup.apply( this, arguments );

			// If you didn't provide a name, or are `control`, don't do anything.
			if ( can.Control ) {

				// Cache the underscored names.
				var control = this,
					funcName;

				// Calculate and cache actions.
				control.actions = {};
				for ( funcName in control.prototype ) {
					if ( control._isAction(funcName) ) {
						control.actions[funcName] = control._action(funcName);
					}
				}
			}
		},

		// Moves `this` to the first argument, wraps it with `jQuery` if it's an element
		_shifter : function( context, name ) {

			var method = typeof name == "string" ? context[name] : name;

			if ( ! isFunction( method )) {
				method = context[ method ];
			}
			
			return function() {
				context.called = name;
				return method.apply(context, [this.nodeName ? can.$(this) : this].concat( slice.call(arguments, 0)));
			};
		},

		// Return `true` if is an action.
		/**
		 * @hide
		 * @param {String} methodName a prototype function
		 * @return {Boolean} truthy if an action or not
		 */
		_isAction: function( methodName ) {
			
			var val = this.prototype[methodName],
				type = typeof val;
			// if not the constructor
			return (methodName !== 'constructor') &&
				// and is a function or links to a function
				( type == "function" || (type == "string" &&  isFunction(this.prototype[val] ) ) ) &&
				// and is in special, a processor, or has a funny character
				!! ( special[methodName] || processors[methodName] || /[^\w]/.test(methodName) );
		},
		// Takes a method name and the options passed to a control
		// and tries to return the data necessary to pass to a processor
		// (something that binds things).
		/**
		 * @hide
		 * Takes a method name and the options passed to a control
		 * and tries to return the data necessary to pass to a processor
		 * (something that binds things).
		 * 
		 * For performance reasons, this called twice.  First, it is called when 
		 * the Control class is created.  If the methodName is templated
		 * like: "{window} foo", it returns null.  If it is not templated
		 * it returns event binding data.
		 * 
		 * The resulting data is added to this.actions.
		 * 
		 * When a control instance is created, _action is called again, but only
		 * on templated actions.  
		 * 
		 * @param {Object} methodName the method that will be bound
		 * @param {Object} [options] first param merged with class default options
		 * @return {Object} null or the processor and pre-split parts.  
		 * The processor is what does the binding/subscribing.
		 */
		_action: function( methodName, options ) {
			
			// If we don't have options (a `control` instance), we'll run this 
			// later.  
			paramReplacer.lastIndex = 0;
			if ( options || ! paramReplacer.test( methodName )) {
				// If we have options, run sub to replace templates `{}` with a
				// value from the options or the window
				var convertedName = options ? can.sub(methodName, [options, window]) : methodName,
					
					// If a `{}` template resolves to an object, `convertedName` will be
					// an array
					arr = can.isArray(convertedName),

					// Get the name
					name = arr ? convertedName[1] : convertedName,

					// Grab the event off the end
					parts = name.split(/\s+/g),
					event = parts.pop();

				return {
					processor: processors[event] || basicProcessor,
					parts: [name, parts.join(" "), event],
					delegate : arr ? convertedName[0] : undefined
				};
			}
		},
		// An object of `{eventName : function}` pairs that Control uses to 
		// hook up events auto-magically.
		/**
		 * @attribute processors
		 * An object of `{eventName : function}` pairs that Control uses to hook up events
		 * auto-magically.  A processor function looks like:
		 * 
		 *     can.Control.processors.
		 *       myprocessor = function( el, event, selector, cb, control ) {
		 *          //el - the control's element
		 *          //event - the event (myprocessor)
		 *          //selector - the left of the selector
		 *          //cb - the function to call
		 *          //control - the binding control
		 *       };
		 * 
		 * This would bind anything like: "foo~3242 myprocessor".
		 * 
		 * The processor must return a function that when called, 
		 * unbinds the event handler.
		 * 
		 * Control already has processors for the following events:
		 * 
		 *   - change 
		 *   - click 
		 *   - contextmenu 
		 *   - dblclick 
		 *   - focusin
		 *   - focusout
		 *   - keydown 
		 *   - keyup 
		 *   - keypress 
		 *   - mousedown 
		 *   - mouseenter
		 *   - mouseleave
		 *   - mousemove 
		 *   - mouseout 
		 *   - mouseover 
		 *   - mouseup 
		 *   - reset 
		 *   - resize 
		 *   - scroll 
		 *   - select 
		 *   - submit  
		 * 
		 * Listen to events on the document or window 
		 * with templated event handlers:
		 * 
		 *     Sized = can.Control({
		 *       "{window} resize": function(){
		 *         this.element.width( this.element.parent().width() / 2 );
		 *       }
		 *     });
		 *     
		 *     new Sized( $( '#foo' ) );
		 */
		processors: {},
		// A object of name-value pairs that act as default values for a 
		// control instance
		/**
		 * @attribute defaults
		 * A object of name-value pairs that act as default values for a control's 
		 * [can.Control::options this.options].
		 * 
		 *     Message = can.Control({
		 *       defaults: {
		 *         message: "Hello World"
		 *       }
		 *     }, {
		 *       init: function(){
		 *         this.element.text( this.options.message );
		 *       }
		 *     });
		 *     
		 *     new Message( "#el1" ); //writes "Hello World"
		 *     new Message( "#el12", { message: "hi" } ); //writes hi
		 *     
		 * In [can.Control::setup] the options passed to the control
		 * are merged with defaults.  This is not a deep merge.
		 */
		defaults: {}
	},
	/** 
	 * @Prototype
	 */
	{
		// Sets `this.element`, saves the control in `data, binds event
		// handlers.
		/**
		 * Setup is where most of control's magic happens.  It does the following:
		 * 
		 * ### Sets this.element
		 * 
		 * The first parameter passed to new Control( el, options ) is expected to be 
		 * an element.  This gets converted to a Wrapped NodeList element and set as
		 * [can.Control.prototype.element this.element].
		 * 
		 * ### Adds the control's name to the element's className.
		 * 
		 * Control adds it's plugin name to the element's className for easier 
		 * debugging.  For example, if your Control is named "Foo.Bar", it adds
		 * "foo_bar" to the className.
		 * 
		 * ### Saves the control in $.data
		 * 
		 * A reference to the control instance is saved in $.data.  You can find 
		 * instances of "Foo.Bar" like: 
		 * 
		 *     $( '#el' ).data( 'controls' )[ 'foo_bar' ]
		 *
		 * ### Merges Options
		 * Merges the default options with optional user-supplied ones.
		 * Additionally, default values are exposed in the static [can.Control.static.defaults defaults] 
		 * so that users can change them.
		 * 
		 * ### Binds event handlers
		 * 
		 * Setup does the event binding described in [can.control.listening Listening To Events].
		 * 
		 * @param {HTMLElement} element the element this instance operates on.
		 * @param {Object} [options] option values for the control.  These get added to
		 * this.options and merged with [can.Control.static.defaults defaults].
		 * @return {Array} return an array if you wan to change what init is called with. By
		 * default it is called with the element and options passed to the control.
		 */
		setup: function( element, options ) {

			var cls = this.constructor,
				pluginname = cls.pluginName || cls._fullName,
				arr;

			// Want the raw element here.
			this.element = can.$(element)

			if ( pluginname && pluginname !== 'can_control') {
				// Set element and `className` on element.
				this.element.addClass(pluginname);
			}
			
			(arr = can.data(this.element,"controls")) || can.data(this.element,"controls",arr = []);
			arr.push(this);
			
			// Option merging.
			/**
			 * @attribute options
			 * 
			 * Options are used to configure an control.  They are
			 * the 2nd argument
			 * passed to a control (or the first argument passed to the 
			 * [can.Control.plugin control's jQuery plugin]).
			 * 
			 * For example:
			 * 
			 *     can.Control('Hello')
			 *     
			 *     var h1 = new Hello( $( '#content1' ), { message: 'World' } );
			 *     equal( h1.options.message , "World" );
			 *     
			 *     var h2 = $( '#content2' ).hello({ message: 'There' })
			 *                              .control();
			 *     equal( h2.options.message , "There" );
			 * 
			 * Options are merged with [can.Control.static.defaults defaults] in
			 * [can.Control.prototype.setup setup].
			 * 
			 * For example:
			 * 
			 *     Tabs = can.Control({
			 *        defaults: {
			 *          activeClass: "ui-active-state"
			 *        }
			 *     }, {
			 *        init: function(){
			 *          this.element.addClass( this.options.activeClass );
			 *        }
			 *     });
			 *     
			 *     new Tabs( $( "#tabs1" ) ); // adds 'ui-active-state'
			 *     new Tabs( $( "#tabs2" ), { activeClass : 'active' } ); // adds 'active'
			 *     
			 * Options are typically updated by calling 
			 * [can.Control.prototype.update update];
			 *
			 */
			this.options = extend({}, cls.defaults, options);

			// Bind all event handlers.
			this.on();

			// Get's passed into `init`.
			/**
			 * @attribute element
			 * 
			 * The control instance's HTMLElement (or window) wrapped by the 
			 * util library for ease of use. It is set by the first
			 * parameter to `new can.Construct( element, options )` 
			 * in [can.Control::setup].  Control listens on `this.element`
			 * for events.
			 * 
			 * ### Quick Example
			 * 
			 * The following `HelloWorld` control sets the control`s text to "Hello World":
			 * 
			 *     HelloWorld = can.Control({
			 *       init: function(){
			 * 	       this.element.text( 'Hello World' );
			 *       }
			 *     });
			 *     
			 *     // create the controller on the element
			 *     new HelloWorld( document.getElementById( '#helloworld' ) );
			 * 
			 * ## Wrapped NodeList
			 * 
			 * `this.element` is a wrapped NodeList of one HTMLELement (or window).  This
			 * is for convience in libraries like jQuery where all methods operate only on a
			 * NodeList.  To get the raw HTMLElement, write:
			 * 
			 *     this.element[0] //-> HTMLElement
			 * 
			 * The following details the NodeList used by each library with 
			 * an example of updating it's text:
			 * 
			 * __jQuery__ `jQuery( HTMLElement )`
			 * 
			 *     this.element.text("Hello World")
			 * 
			 * __Zepto__ `Zepto( HTMLElement )`
			 * 
			 *     this.element.text("Hello World")
			 * 
			 * __Dojo__ `new dojo.NodeList( HTMLElement )`
			 * 
			 *     // TODO
			 * 
			 * __Mootools__ `$$( HTMLElement )`
			 * 
			 *    this.element.empty().appendText("Hello World")
			 * 
			 * __YUI__ 
			 * 
			 *    // TODO
			 * 
			 * 
			 * ## Changing `this.element`
			 * 
			 * Sometimes you don't want what's passed to `new can.Control`
			 * to be this.element.  You can change this by overwriting
			 * setup or by unbinding, setting this.element, and rebinding.
			 * 
			 * ### Overwriting Setup
			 * 
			 * The following Combobox overwrites setup to wrap a
			 * select element with a div.  That div is used 
			 * as `this.element`. Notice how `destroy` sets back the
			 * original element.
			 * 
			 *     Combobox = can.Control({
			 *       setup: function( el, options ) {
			 *          this.oldElement = $( el );
			 *          var newEl = $( '<div/>' );
			 *          this.oldElement.wrap( newEl );
			 *          can.Controll.prototype.setup.call( this, newEl, options );
			 *       },
			 *       init: function() {
			 *          this.element //-> the div
			 *       },
			 *       ".option click": function() {
			 *         // event handler bound on the div
			 *       },
			 *       destroy: function() {
			 *          var div = this.element; //save reference
			 *          can.Control.prototype.destroy.call( this );
			 *          div.replaceWith( this.oldElement );
			 *       }
			 *     });
			 * 
			 * ### unbining, setting, and rebinding.
			 * 
			 * You could also change this.element by calling
			 * [can.Control::off], setting this.element, and 
			 * then calling [can.Control::on] like:
			 * 
			 *     move: function( newElement ) {
			 *        this.off();
			 *        this.element = $( newElement );
			 *        this.on();
			 *     }
			 */
			return [this.element, this.options];
		},
		/**
		 * `this.on( [element, selector, eventName, handler] )` is used to rebind 
		 * all event handlers when [can.Control::options this.options] has changed.  It
		 * can also be used to bind or delegate from other elements or objects.
		 * 
		 * ## Rebinding
		 * 
		 * By using templated event handlers, a control can listen to objects outside
		 * `this.element`.  This is extremely common in MVC programming.  For example,
		 * the following control might listen to a task model's `completed` property and
		 * toggle a strike className like:
		 * 
		 *     TaskStriker = can.Control({
		 *       "{task} completed": function(){
		 * 	       this.update();
		 *       },
		 *       update: function(){
		 *         if ( this.options.task.completed ) {
		 * 	         this.element.addClass( 'strike' );
		 * 	       } else {
		 *           this.element.removeClass( 'strike' );
		 *         }
		 *       }
		 *     });
		 * 
		 *     var taskstriker = new TaskStriker({ 
		 *       task: new Task({ completed: 'true' }) 
		 *     });
		 * 
		 * To update the taskstriker's task, add a task method that updates
		 * this.options and calls rebind like:
		 * 
		 *     TaskStriker = can.Control({
		 *       "{task} completed": function(){
		 * 	       this.update();
		 *       },
		 *       update: function() {
		 *         if ( this.options.task.completed ) {
		 * 	         this.element.addClass( 'strike' );
		 * 	       } else {
		 *           this.element.removeClass( 'strike' );
		 *         }
		 *       },
		 *       task: function( newTask ) {
		 *         this.options.task = newTask;
		 *         this.on();
		 *         this.update();
		 *       }
		 *     });
		 * 
		 *     var taskstriker = new TaskStriker({ 
		 *       task: new Task({ completed: true }) 
		 *     });
		 *     taskstriker.task( new TaskStriker({ 
		 *       task: new Task({ completed: false }) 
		 *     }));
		 * 
		 * ## Adding new events
		 * 
		 * If events need to be bound to outside of the control and templated event handlers
		 * are not sufficent, you can call this.on to bind or delegate programatically:
		 * 
		 *     init: function() {
		 *        // calls somethingClicked( el, ev )
		 *        this.on( 'click', 'somethingClicked' ); 
		 *     
		 *        // calls function when the window is clicked
		 *        this.on( window, 'click', function( ev ) {
		 *          //do something
		 *        });
		 *     },
		 *     somethingClicked: function( el, ev ) {
		 *       
		 *     }
		 * 
		 * @param {HTMLElement|jQuery.fn|Object} [el=this.element]
		 * The element to be bound.  If an eventName is provided,
		 * the control's element is used instead.
		 * @param {String} [selector] A css selector for event delegation.
		 * @param {String} [eventName] The event to listen for.
		 * @param {Function|String} [func] A callback function or the String name of a control function.  If a control
		 * function name is given, the control function is called back with the bound element and event as the first
		 * and second parameter.  Otherwise the function is called back like a normal bind.
		 * @return {Integer} The id of the binding in this._bindings
		 */
		on: function( el, selector, eventName, func ) {
			if ( ! el ) {

				// Adds bindings.
				this.off();

				// Go through the cached list of actions and use the processor 
				// to bind
				var cls = this.constructor,
					bindings = this._bindings,
					actions = cls.actions,
					element = this.element,
					destroyCB = can.Control._shifter(this,"destroy"),
					funcName, ready;
					
				for ( funcName in actions ) {
					if ( actions.hasOwnProperty( funcName )) {
						ready = actions[funcName] || cls._action(funcName, this.options);
						bindings.push(
							ready.processor(ready.delegate || element, 
							                ready.parts[2], 
											ready.parts[1], 
											funcName, 
											this));
					}
				}
	
	
				// Setup to be destroyed...  
				// don't bind because we don't want to remove it.
				can.bind.call(element,"destroyed", destroyCB);
				bindings.push(function( el ) {
					can.unbind.call(el,"destroyed", destroyCB);
				});
				return bindings.length;
			}

			if ( typeof el == 'string' ) {
				func = eventName;
				eventName = selector;
				selector = el;
				el = this.element;
			}

			if(func === undefined) {
				func = eventName;
				eventName = selector;
				selector = null;
			}

			if ( typeof func == 'string' ) {
				func = can.Control._shifter(this,func);
			}

			this._bindings.push( binder( el, eventName, func, selector ));

			return this._bindings.length;
		},
		// Unbinds all event handlers on the controller.
		/**
		 * @hide
		 * Unbinds all event handlers on the controller. You should never
		 * be calling this unless in use with [can.Control::on].
		 */
		off : function(){
			var el = this.element[0]
			each(this._bindings || [], function( value ) {
				value(el);
			});
			// Adds bindings.
			this._bindings = [];
		},
		// Prepares a `control` for garbage collection
		/**
		 * @function destroy
		 * `destroy` prepares a control for garbage collection and is a place to
		 * reset any changes the control has made.  
		 * 
		 * ## Allowing Garbage Collection
		 * 
		 * Destroy is called whenever a control's element is removed from the page using 
		 * the library's standard HTML modifier methods.  This means that you
		 * don't have to call destroy yourself and it 
		 * will be called automatically when appropriate.  
		 * 
		 * The following `Clicker` widget listens on the window for clicks and updates
		 * its element's innerHTML.  If we remove the element, the window's event handler
		 * is removed auto-magically:
		 *  
		 * 
		 *      Clickr = can.Control({
		 *       "{window} click": function() {
		 * 	       this.element.html( this.count ? 
		 * 	                          this.count++ : this.count = 0 );
		 *       }  
		 *     });
		 *     
		 *     // create a clicker on an element
		 *     new Clicker( "#clickme" );
		 * 
		 *     // remove the element
		 *     $( '#clickme' ).remove();
		 * 
		 * 
		 * The methods you can use that will destroy controls automatically by library:
		 * 
		 * __jQuery and Zepto__
		 * 
		 *   - $.fn.remove
		 *   - $.fn.html
		 *   - $.fn.replaceWith
		 *   - $.fn.empty
		 * 
		 * __Dojo__
		 * 
		 *   - dojo.destroy
		 *   - dojo.empty
		 *   - dojo.place (with the replace option)
		 * 
		 * __Mootools__
		 * 
		 *   - Element.prototype.destroy
		 * 
		 * __YUI__
		 * 
		 *   - TODO!
		 * 
		 * 
		 * ## Teardown in Destroy
		 * 
		 * Sometimes, you want to reset a controlled element back to its
		 * original state when the control is destroyed.  Overwriting destroy
		 * lets you write teardown code of this manner.  __When overwriting
		 * destroy, make sure you call Control's base functionality__.
		 * 
		 * The following example changes an element's text when the control is
		 * created and sets it back when the control is removed:
		 * 
		 *     Changer = can.Control({
		 *       init: function() {
		 *         this.oldText = this.element.text();
		 *         this.element.text( "Changed!!!" );
		 *       },
		 *       destroy: function() {
		 *         this.element.text( this.oldText );
		 *         can.Control.prototype.destroy.call( this );
		 *       }
		 *     });
		 *     
		 *     // create a changer which changes #myel's text
		 *     var changer = new Changer( '#myel' );
		 * 
		 *     // destroy changer which will reset it
		 *     changer.destroy();
		 * 
		 * ## Base Functionality
		 * 
		 * Control prepares the control for garbage collection by:
		 * 
		 *   - unbinding all event handlers
		 *   - clearing references to this.element and this.options
		 *   - clearing the element's reference to the control
		 *   - removing it's [can.Control.pluginName] from the element's className
		 * 
		 */
		destroy: function() {
			var Class = this.constructor,
				pluginName = Class.pluginName || Class._fullName,
				controls;
			
			// Unbind bindings.
			this.off();
			
			if(pluginName && pluginName !== 'can_control'){
				// Remove the `className`.
				this.element.removeClass(pluginName);
			}
			
			// Remove from `data`.
			controls = can.data(this.element,"controls");
			controls.splice(can.inArray(this, controls),1);
			
			can.trigger( this, "destroyed"); // In case we want to know if the `control` is removed.
			
			this.element = null;
		}
	});

	var processors = can.Control.processors,
	// Processors do the binding.
	// They return a function that unbinds when called.  
	//
	// The basic processor that binds events.
	basicProcessor = function( el, event, selector, methodName, control ) {
		return binder( el, event, can.Control._shifter(control, methodName), selector);
	};




	// Set common events to be processed as a `basicProcessor`
	each(["change", "click", "contextmenu", "dblclick", "keydown", "keyup",
		"keypress", "mousedown", "mousemove", "mouseout", "mouseover",
		"mouseup", "reset", "resize", "scroll", "select", "submit", "focusin",
		"focusout", "mouseenter", "mouseleave",
		// #104 - Add touch events as default processors
		// TOOD feature detect?
		"touchstart", "touchmove", "touchcancel", "touchend", "touchleave"
	], function( v ) {
		processors[v] = basicProcessor;
	});

	return Control;
})(module["can/util/jquery/jquery.js"], module["can/construct/construct.js"]);

window.define = module._define;

window.module = module._orig;