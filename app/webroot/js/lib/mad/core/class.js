steal(
	'jquery/class',
	'jquery/lang/string'
).then(function () {

	/*
	 * @class mad.core.Class
	 * @parent mad.core
	 * This class is our layer placed upon the javascriptMVC class Class. It adds the following features to the
	 * existing library.
	 * 
	 * <ul><li>
	 * <b>Decorate</b> an instance with a subset of functions.
	 * </li><li>
	 * <b>Augment</b> a class with the properties of another.
	 * </li></ul>
	 */

	/* @prototype */

	/* 
	 * @function decorate
	 * @todo Accept jmvc object as parameter
	 * 
	 * Decorate an instance with the properties of another object.
	 * 
	 * <h2>Example</h2>
	 * @codestart
	$.Class('MyClass',{
		'funcToDecorate': function() {
				return 'I am the function to decorate';
		},
	});

	var MyDecorator = {
		'funcToDecorate': function() {
				return this._super()+' which has been decorated, oh yeah!';
			},
		'moreFunc': function() {
				return 'I am an additional function';
		}
	};
	
  var myInstance = new MyClass();
	myInstance.funcToDecorate();     
	// will return 'I am the function to decorate'
	myInstance.decorate('MyDecorator');
	myInstance.funcToDecorate();     
	// will return 'I am the function to decorate which has been decorated, oh yeah!'
	myInstance.moreFunc();     
	// will return 'I am an additional function'
	 * @codeend
	 * 
	 * @param {String} decoratorName The name of the object to use as decorator
	 * @return {Object} Returns the decorated instance
	 */
	$.Class.prototype.decorate = function (decoratorName) {
		var Decorator = $.String.getObject(decoratorName),
			lvl = null;

		// Not yet decorated
		if (typeof this._decorator == 'undefined') {
			this._decorator = [];
		}
		// Current decorator level
		lvl = this._decorator.length;
		// Add a new decoration layer
		this._decorator[lvl] = {
			'name': decoratorName,
			f: {}
		};

		// decorate the instance with the functions of the Decorator Class
		for (var i in Decorator) {
			if(typeof this[i] != 'undefined') {

				// store the old function
				this._decorator[lvl].f[i] = this[i];

				// add the decorate function
				this[i] = (function (instance, fn, lvl) {
					return function () {
						var tmp = this._super,
							returnValue;
						this._super = instance._decorator[lvl].f[fn];
						returnValue = Decorator[fn].apply(instance, arguments);
						this._super = tmp;
						return returnValue;
					}
				})(this, i, lvl);

			} else {
				this[i] = (function (instance, fn, lvl) {
					return function () {
						var returnValue = Decorator[fn].apply(instance, arguments);
						return returnValue;
					}
				})(this, i, lvl);
			}
		}

		return this;
	}

	/**
	 * Get the Class of the instance
	 * @return {$.Class}
	 */
	$.Class.prototype.getClass = function () {
		return this.constructor;
	}

	/* 
	 * @function augment
	 * 
	 * Augment a class with the properties (prototype & static) of another.
	 * <br/>
	 * Allow a kind of multiple inheritance. Each init function of the Class used to augment
	 * will be called once after other (LIFO) when an instance is created.
	 * 
	 * <h2>Example</h2>
	 * @codestart
	$.Class('MyClass',{
		'myVar':0,
		'init':function(){
				this.myVar++;
		},
	});

	$.Class('MyAugmentator',{
		'init':function(){
				this.myVar++;
		},
		'moreFunc': function() {
				return 'I am an additional function';
		}
	});
	
	MyClass.augment(MyAugmentator);
	var myInstance = new MyClass();
	// myInstance.myVar == 2
	myInstance.moreFunc();
	// will return 'I am an additional function'
	 * @codeend
	 * 
	 * @param  {String} objectName The name of the object to use as augmentator
	 * @return  {void}
	 */
	$.Class.augment = function (objectName) {
		var Augmentator = $.String.getObject(objectName);

		// Add static properties to the class to augment
		var blackListedStaticProperties = ['namespace', 'shortName', 'fullName', 'defaults'];
		for(var i in Augmentator) {

			if(typeof $.Class[i] == 'undefined' && $.inArray(i, blackListedStaticProperties) == -1) {
				this[i] = Augmentator[i];
			}
		}

		// Add prototype properties to the class to augment
		var blackListedPrototypeProperties = ['Class'];
		for(var i in Augmentator.prototype) {
			if(typeof $.Class.prototype[i] == 'undefined' && $.inArray(i, blackListedPrototypeProperties) == -1) {

				// augment a constructor, try something closed to multiple inheritance
				if(i == 'init') {
					if(typeof this.prototype.__inits == 'undefined') {
						// List of constructors
						this.prototype.__inits = [];
						this.prototype.__inits.push(this.prototype[i]);
						// replace the init function, with a function which will execute all the init function
						this.prototype[i] = (function (clazz, fn) {
							return function (el, options, test) {
								//                                    var returnValue = null;
								for(var i in this.__inits) {
									var initResult = this.__inits[i].apply(this, arguments);
								}
							}
						})(this, 'init');
					}
					// Add the init function of the Augmentator to the list of init function to execute when creating an instance of the class
					this.prototype.__inits.push(Augmentator.prototype[i]);
				}
				// add the function to the brol
				else {
					this.prototype[i] = Augmentator.prototype[i];
				}
			}
		}
	}
});