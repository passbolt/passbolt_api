/**
@description Check if an object is a Deferred.
@function can.isDeferred
@parent can.util
@signature `can.isDeferred(subject)`
@param {*} subject The object to check.
@return {Boolean} Whether __subject__ is a Deferred.

@body
`can.isDeferred` returns if an object is an instance of [can.Deferred].

## Example
Convert any value to a Deferred:

    function convertDeferred(dfd) {
        return can.isDeferred(dfd) ? dfd : can.Deferred(dfd);
    }
*/
//
/**
@description Trim whitespace off a string.
@function can.trim
@parent can.util
@signature `can.trim(str)`
@param {String} str The string to trim.
@return {String} The trimmed string.

@body
`can.trim(str)` removes leading and trailing whitespace from a string.  It will also
remove all newlines, spaces including non-breaking, and tabs.  If these occur in the middle
of the string, then they will be persisted.

    can.trim(" foo ") // "foo"
*/
//
/**
@description Convert an array-like object to an Array.
@function can.makeArray
@parent can.util
@signature `can.makeArray(arrLike)`
@param {Object} arrLike An array-like object.
@return {Array} The converted object.

@body
`can.makeArray(arrLike)` converts an array-like object into a array.

    can.makeArray({0 : "zero", 1: "one", length: 2}); // ["zero","one"]
*/
//
/**
@description Check if an object is an array.
@function can.isArray
@parent can.util
@signature `can.isArray(obj)`
@param {*} obj The object to check.
@return {Boolean} Whether __obj__ is an Array.

@body
`can.isArray(object)` returns if the object is an explicitly an Array. If `can.isArray` 
is passed an array-like object, it will return `false`.

    can.isArray([]);    // true
    can.isArray([1, 2, 3]);    // true
    can.isArray(can.makeArray({0: "foo", 1: "bar"})); // true
    (function() { return can.isArray(arguments); })(); // false
    can.isArray(document.querySelectorAll("p")); // false
    can.isArray(true); // false
*/
//
/**
@description Iterate through an array or object.
@function can.each
@parent can.util
@signature `can.each(collection, callback)`
@param {Object} collection The object to iterate through.
@param {Function} callback A function to call for each item in __collection__.
__callback__ will receive the item's value first and its key second.

@body
`can.each(collection, callback)` iterates through an array or object like
like [http://api.jquery.com/jQuery.each/ jQuery.each].

    can.each([{prop: "val1"}, {prop: "val2"}],
        function( value, index ) {
            // function called with
            // index=0 value={prop: "val1"}
            // index=1 value={prop: "val2"}
        }
    );
*/
//
/**
@description Merge objects together.
@function can.extend
@parent can.util
@signature `can.extend(target, ...obj)`
@param {Object} target The object to merge properties into.
@param {Object} obj Objects containing properties to merge.
@return {Object} __target__, post-merge.

@body
`can.extend(target, objectN)` merges the contents of two or more objects together into the first object
similarly to [http://api.jquery.com/jQuery.extend/ jQuery.extend].

    var first = {},
    second = {a: "b"},
    third = {c: "d"};

    can.extend(first, second, third); //-> first

    first  //-> {a: "b", c: "d"}
    second //-> {a: "b"}
    third  //-> {c: "d"}
*/
//
/**
@description Serialize an object into a query string.
@function can.param
@parent can.util
@signature `can.param(obj)`
@param {Object} obj An array or object to serialize.
@return {String} The serialized string.

@body
Parameterizes an object into a query string
like [http://api.jquery.com/jQuery.param/ jQuery.param].

    can.param({a: "b", c: "d"}) //-> "a=b&c=d"
*/
//
/**
@description Takes a string of name value pairs and returns a Object literal that represents those params.
@function can.deparam
@parent can.util
@signature `can.deparam(params)`
@param {String} params A string like <code>"foo=bar&person[age]=3"</code>
@return {Object} A JavaScript Object that represents the params:

    {
      foo: "bar",
      person: {
        age: "3"
      }
    }

@body

can.depararm will take any string and convert it into an Object literal that represents the string passed into it.
*/
//
/**
@description Check if an object has no properties.
@function can.isEmptyObject
@parent can.util
@signature `can.isEmptyObject(obj)`
@param {Object} obj The object to check.
@param {Boolean} Whether the object is empty.

@body
`can.isEmptyObject(obj)` returns if an object has no properties similar to
[http://api.jquery.com/jQuery.isEmptyObject/ jQuery.isEmptyObject].

    can.isEmptyObject({})      //-> true
    can.isEmptyObject({a:"b"}) //-> false
*/
//
/**
@description Bind a function to its context.
@function can.proxy
@parent can.util
@signature `can.proxy(fn, context)`
@param {Function} fn The function to bind to a context.
@param {Object} context The context to bind the function to.
@return {Function} A function that calls __fn__ in the context of __context__.

@body
`can.proxy(fn, context)` accepts a function and returns a
new one that will always have the context from which it was
called.  This works similar to [http://api.jquery.com/jQuery.proxy/ jQuery.proxy].

    var func = can.proxy(function(one){
        return this.a + one
    }, {a: "b"});

    func("two") //-> "btwo"
*/
//
/**
@description Check if an Object is a function.
@function can.isFunction
@parent can.util
@signature `can.isFunction(obj)`
@param {Object} obj The object to check.
@return {Boolean} Whether __obj__ is a function.

@body
`can.isFunction(object)` returns if an object is a function similar to
[http://api.jquery.com/jQuery.isFunction/ jQuery.isFunction].

    can.isFunction({})           //-> false
    can.isFunction(function(){}) //-> true
*/
//
/**
@description Listen for events on an object.
@function can.bind
@parent can.util
@signature `can.bind.call(target, eventName, handler)`
@param {Object} target The object that emits events.
@param {String} eventName The name of the event to listen for.
@param {Function} handler The function to execute when the event occurs.
@return {Object} The __target__.

@body
`can.bind(eventName, handler)` binds a callback handler
on an object for a given event.  It works on:

- HTML elements and the window
- Objects
- Objects with bind / unbind methods

The idea is that `can.bind` can be used on anything that produces events
and it will figure out the appropriate way to
bind to it.  Typically, `can.bind` is only used internally to
CanJS; however, if you are making libraries or extensions, use
`can.bind` to listen to events independent of the underlying library.


__Binding to an object__

    var obj = {};
    can.bind.call(obj,"something", function(ev, arg1, arg){
        arg1 // 1
        arg2 // 2
    })
    can.trigger(obj,"something",[1,2])

__Binding to an HTMLElement__

    var el = document.getElementById('foo')
    can.bind.call(el, "click", function(ev){
        this // el
    });
*/
//
/**
@description Listen for events on an object.
@function can.on
@parent can.util
@signature `can.on.call(target, eventName, handler)`
@param {Object} target The object that emits events.
@param {String} eventName The name of the event to listen for.
@param {Function} handler The function to execute when the event occurs.
@return {Object} The __target__.

@body
`can.on(eventName, handler)` is an alias for [can.bind `can.bind(eventName, handler)`]
and binds a callback handler on an object for a given event.  It works on:

- HTML elements and the window
- Objects
- Objects with bind / unbind methods

The idea is that `can.on` can be used on anything that produces events
and it will figure out the appropriate way to bind to it. 


__Binding to an object__

    var obj = {};
    can.on.call(obj,"something", function(ev, arg1, arg){
        arg1 // 1
        arg2 // 2
    })
    can.trigger(obj,"something",[1,2])

__Binding to an HTMLElement__

    var el = document.getElementById('foo')
    can.on.call(el, "click", function(ev){
        this // el
    });
*/
//
/**
@description Stop listening for events on an object.
@function can.unbind
@parent can.util
@signature `can.unbind.call(target, eventName, handler)`
@param {Object} target The object that emits events.
@param {String} eventName The name of the event to listen for.
@param {Function} handler The function to unbind.
@return {Object} The __target__.

@body
`can.unbind(eventName, handler)` unbinds a callback handler
from an object for a given event.  It works on:

- HTML elements and the window
- Objects
- Objects with bind / unbind methods

The idea is that `can.unbind` can be used on anything that produces events
and it will figure out the appropriate way to
unbind to it.  Typically, `can.unbind` is only used internally to
CanJS; however, if you are making libraries or extensions, use
`can.bind` to listen to events independent of the underlying library.

__Binding/unbinding to an object__

    var obj = {},
    handler = function(ev, arg1, arg) {
        arg1 // 1
        arg2 // 2
    };
    can.bind.call(obj,"something", handler)
    can.trigger(obj,"something",[1,2])
    can.unbind.call(obj,"something", handler)

__Binding/unbinding to an HTMLElement__


    var el = document.getElementById('foo'),
    handler = function(ev){
        this // el
    };
    can.bind.call(el, "click", handler)
    can.unbind.call(el, "click", handler)
*/
//
/**
@description Stop listening for events on an object.
@function can.off
@parent can.util
@signature `can.off.call(target, eventName, handler)`
@param {Object} target The object that emits events.
@param {String} eventName The name of the event to listen for.
@param {Function} handler The function to unbind.
@return {Object} The __target__.

@body
`can.off(eventName, handler)` is an alias for [can.unbind `can.unbind(eventName, handler)`]
and unbinds a callback handler from an object for a given event.  It works on:

- HTML elements and the window
- Objects
- Objects with bind / unbind methods

The idea is that `can.unbind` can be used on anything that produces events
and it will figure out the appropriate way to
unbind to it.  Typically, `can.off` is only used internally to
CanJS; however, if you are making libraries or extensions, use
`can.on` to listen to events independent of the underlying library.

__Binding/unbinding to an object__

    var obj = {},
    handler = function(ev, arg1, arg) {
        arg1 // 1
        arg2 // 2
    };2
    can.on.call(obj,"something", handler)
    can.trigger(obj,"something",[1,2])
    can.off.call(obj,"something", handler)

__Binding/unbinding to an HTMLElement__


    var el = document.getElementById('foo'),
    handler = function(ev){
        this // el
    };
    can.on.call(el, "click", handler)
    can.off.call(el, "click", handler)
*/
//
/**
@description Listen for events from the children of an element.
@function can.delegate
@parent can.util
@signature `can.delegate.call(element, selector, eventName, handler)`
@param {HTMLElement} element The HTML element to bind to.
@param {String} selector A selector for delegating downward.
@param {String} eventName The name of the event to listen for.
@param {Function} handler The function to execute when the event occurs.
@return {Object} The __element__.

@body
`can.delegate(selector, eventName, handler)` binds a delegate handler
on an object for a given event.  It works on:

- HTML elements and the window

The idea is that delegate can be used on anything that produces delegate events
and it will figure out the appropriate way to
bind to it.  Typically, `can.delegate` is only used internally to
CanJS; however, if you are making libraries or extensions, use
`can.delegate` to listen to events independent of the underlying library.

__Delegate binding to an HTMLElement__

	// Assuming an HTML body like the following:
	// <div id="parent">
    //     <div class="child">Hello</div>
    // </div>

    var el = document.getElementById('parent');
    can.delegate.call(el, ".child", "click", function(ev) {
        return this; //-> el
    });
*/
//
/**
@description Stop listening for events from the children of an element.
@function can.undelegate
@parent can.util
@signature `can.undelegate.call(element, selector, eventName, handler)`
@param {HTMLElement} element The HTML element to unbind from.
@param {String} selector A selector for delegating downward.
@param {String} eventName The name of the event to listen for.
@param {Function} handler The function that was bound.
@return {Object} The __element__.

`can.undelegate(selector, eventName, handler)` unbinds a delegate handler
on an object for a given event.  It works on:

- HTML elements and the window

The idea is that undelegate can be used on anything that produces delegate events
and it will figure out the appropriate way to
bind to it.  Typically, `can.undelegate` is only used internally to
CanJS; however, if you are making libraries or extensions, use
`can.undelegate` to stop listening to events independent of the underlying library.

__Delegate/undelegate binding to an HTMLElement__

	// Assuming an HTML body like the following:
	// <div id="parent">
    //     <div class="child">Hello</div>
    // </div>

    var el = document.getElementById('parent');
    var handler = function(ev) {
        return this; //-> el
    };
    can.delegate.call(el, ".child", "click", handler);
    can.undelegate.call(el, ".child", "click", handler);
*/
//
/**
@description Trigger an event on an object.
@function can.trigger
@parent can.util
@signature `can.trigger(target, eventName[, args])`
@param {Object} target The object to trigger the event on.
@param {String} eventName The event to trigger.
@param {Array.<*>} [args] The event data.

@body

`can.trigger(target, eventName)` triggers an artificial event on an object and fires all
associated callback handlers [can.bind that were bound to it.] This is exceptionally handly
for simulating events. Such as the following:

    var button = document.createElement('button');

    can.bind.call(button, 'click', function() {
        console.log('I have been clicked');
    })

    can.trigger(button, 'click');
*/
//
/**
@description Make an AJAX request.
@function can.ajax
@parent can.util
@signature `can.ajax(settings)`
@param {Object} settings Configuration options for the AJAX request.
The list of configuration options is the same as for [jQuery.ajax](http://api.jquery.com/jQuery.ajax/#jQuery-ajax-settings).
@return {can.Deferred} A [can.Deferred](http://canjs.com/docs/can.Deferred.html) that resolves to the data.

@body
`can.ajax( settings )` is used to make an asynchronous HTTP (AJAX) request
similar to [http://api.jquery.com/jQuery.ajax/jQuery.ajax]. The example below
makes use of [can.frag].

	can.ajax({
		url: 'http://canjs.com/docs/can.ajax.html',
		success: function(document) {
			var frag = can.frag(document);
			return frag.querySelector(".heading h1").innerText; //-> can.ajax
		}
	});
*/
//
/**
@description Make a library's nodelist.
@function can.$
@parent can.util
@signature `can.$(element)`
@param {String|Element|NodeList} element The selector, HTML element, or nodelist
to pass to the underlying library.
@return {NodeList} The nodelist as constructed by the underlying library.

@body
`can.$(element)` returns the the underlying
library's NodeList.  It can be passed
a css selector, a HTMLElement or an array of HTMLElements.

The following lists how the NodeList is created by each library:

- __jQuery__ `jQuery( HTMLElement )`
- __Zepto__ `Zepto( HTMLElement )`
- __Dojo__ `new dojo.NodeList( HTMLElement )`
- __Mootools__ `$$( HTMLElement )`
- __YUI__ `Y.all(selector)` or `Y.NodeList`
*/
//
/**
@description Make a document fragment.
@function can.buildFragment
@parent can.util
@signature `can.buildFragment(html, node)`
@param {String} html A string of HTML.
@param {DOM Node} node A node used to access a document to make the fragment with.
@return {DocumentFragment} A document fragment made from __html__.

@body
`can.buildFragment(html, node)` returns a document fragment for the HTML passed.
*/
//
/**
@description Append content to elements.
@function can.append
@parent can.util
@signature `can.append(nodeList, html)`
@param {NodeList} nodeList A nodelist of the elements to append content to.
@param {String} html The HTML to append to the end of the elements in __nodeList__.

@body
`can.append( wrappedNodeList, html )` inserts content to the end of each wrapped node list item(s) passed.
This is a wrapper API for the underlying library being used. If you're using jQuery, this is a wrapper API 
for [.append](http://api.jquery.com/append/).

    // Before
    <div id="demo" />

    can.append(can.$('#demo'), 'Demos are fun!');

    // After
    <div id="demo">Demos are fun!</div>
*/
//
/**
@description Remove elements from the DOM.
@function can.remove
@parent can.util
@signature `can.remode(nodeList)`
@param {NodeList} nodeList A nodelist of elements to remove.

@body
`can.remove( wrappedNodeList )` removes the set of matched element(s) from the DOM.

    <div id="wrap"/>
    can.remove(can.$('#wrap')) //-> removes 'wrap'
*/
//
/**
@description Associate data with or retrieve data from DOM nodes.
@function can.data
@parent can.util
@signature `can.data(nodeList, key, value)`
@param {NodeList} nodeList The list of nodes to add this data to.
@param {String} key The key to store this data under.
@param {*} value The data to store.

@signature `can.data(nodeList, key)`
@param {NodeList} nodeList The list of nodes data was stored under.
@param {String} key The key to retrieve.
@return {*} The data stored under __key__.

@body
`can.data` enables the associatation of arbitrary data with DOM nodes and JavaScript objects.

### Setting Data

can.data( can.$('#elm'), key, value )

- __wrappedNodeList__ node list to associate data to.
- __key__ string name of the association.
- __value__ tdata value; it can be any Javascript type including Array or Object.

### Accessing Data

can.data( can.$('#elm'), key )

- __wrappedNodeList__ node list to retrieve association data from.
- __key__ string name of the association.

Due to the way browsers security restrictions with plugins and external code,
the _data_ method cannot be used on `object` (unless it's a Flash plugin), `applet` or `embed` elements.
*/
//
/**
@description Add a class to elements.
@function can.addClass
@parent can.util
@signature `can.addClass(nodeList, className)`
@param {NodeList} nodeList The list of HTML elements to add the class to.
@param {String} className The class to add.

@body
`can.addClass( nodelist, className )` adds the specified class(es) to
nodelist's HTMLElements.  It does NOT replace any existing class(es)
already defined.

    // Before
    <div id="foo" class="monkey" />

    can.addClass(can.$("#foo"),"bar")

    // After
    <div id="foo" class="monkey bar" />

You can also pass multiple class(es) and it will add them to the existing
set also.

    // Before
    <div id="foo" class="monkey" />

    can.addClass(can.$("#foo"),"bar man")

    // After
    <div id="foo" class="monkey bar man" />

This works similarly to [http://api.jquery.com/addClass/ jQuery.fn.addClass].
*/
//
/**
@description Call a callback when a Deferred resolves.
@function can.when
@parent can.util
@signature `can.when(deferred)`
@param {Deferred|Object} deferred The Deferred, AJAX, or normal Objects to call the callback on.
@return {Deferred} __deferred__ if __deferred__ is a Deferred,
otherwise a Deferred that resolves to __deferred__.

@body
`can.when(deferred)` provides the ability to execute callback function(s)
typically based on a Deferred or AJAX object.

This is a wrapper API for the underlying library being used. If you're using jQuery, this is a wrapper API 
for [jQuery.when](http://api.jquery.com/jquery.when/);

    can.when( can.ajax('api/farm/animals') ).then(function(animals){
        alert(animals); //-> alerts the ajax response
    });

You can also use this to wait for the results of multiple deferreds.
    
    can.when( can.ajax('api/farm/animals'), can.ajax('api/farm/beacons') ).then(function(animals, beacons){
        // perform some logic using both the animals and beacons data
    });

You can also use this for regular JavaScript objects.

    $.when({ animals: [ 'cat' ] }).done(function(value){
        alert(value.animals[0]); //-> alerts 'cat'
    });
*/
//
/**
@constructor can.Deferred
@parent can.util

@description `can.Deferred` is a object that allows users to assign and chain callback
function(s) for the success or failure state of both asynchronous and synchronous function(s).

@signature `can.Deferred()`
@return {can.Deferred} A new Deferred object.
*/
//
/*
 * @prototype
 */
//
/**
@description Add callbacks to a Deferred.
@function can.Deferred.prototype.pipe
@signature `deferred.pipe(doneCallback[, failCallback])`
@param {Function} doneCallback A function called when the Deferred is resolved.
@param {Function} failCallback A function called when the Deferred is rejected.

@body
`deferred.pipe(doneCallback, failCallback)` is a utility to filter Deferred(s).

    var def = can.Deferred(),
    filtered = def.pipe(function(val) {
        return val + " is awesome!";
    });

    def.resolve('Can');

    filtered.done(function(value) {
        alert(value); // Alerts: 'Can is awesome!'
    });
*/
//
/**
@description Resolve a Deferred in a particular context.
@function can.Deferred.prototype.resolveWith resolveWith
@parent can.Deferred.prototype
@signature `deferred.resolveWith(context[, arguments])`
@param {Object} context Context passed to the `doneCallbacks` as the `this` object.
@param {Array} [arguments] Array of arguments that are passed to the `doneCallbacks`.

@body
`deferred.resolveWith(context, arguments)` resolves a Deferred and calls the `doneCallbacks` with the given arguments.

	var def = can.Deferred();
	def.done(function(success) {
		this.logSuccess(success); //-> "Can rocks!"
	});

	var context = {
		logSuccess: function(sucess) {
			console.log(sucess);
		}
	};
	def.resolveWith(context, ["Can rocks!"]);
*/
//
/**
@description Reject a Deferred in a particular context.
@function can.Deferred.prototype.rejectWith rejectWith
@parent can.Deferred.prototype
@signature `deferred.rejectWith(context[, arguments])`
@param {Object} context Context passed to the `failCallbacks` as the `this` object.
@param {Array} [arguments] Array of arguments that are passed to the `failCallback(s)`.

@body
`deferred.rejectWith(context, arguments)` rejects a Deferred and calls the `failCallbacks` with the given arguments.

	var def = can.Deferred();
	def.fail(function(error) {
		this.logError(error); //-> "Oh no!"
	});

	var context = {
		logError: function(error) {
			console.error(error);
		}
	};
	def.rejectWith(context, ["Oh no!"]);
*/
//
/**
@description Add one or more callbacks to be called when a Deferred is resolved.
@function can.Deferred.prototype.done done
@parent can.Deferred.prototype
@signature `deferred.done(doneCallbacks)`
@param {Function|Array} doneCallbacks A function, or an array of functions, to be called when the Deferred is resolved.

@body
`deferred.done(doneCallbacks)` adds one or more functions to be called when the Deferred object is resolved.

    var def = can.Deferred();
    def.done(function(){
        //- Deferred is done.
    });
*/
//
/**
@description Add a callback to be called when a Deferred is rejected.
@function can.Deferred.prototype.fail fail
@parent can.Deferred.prototype
@signature `deferred.fail(failCallbacks)`
@param {Function|Array} failCallbacks A function, or an array of functions, to be called when the Deferred is rejected.

@body
`deferred.fail(failCallbacks)` adds one or more functions to be called when the Deferred object is rejected.

    var def = can.Deferred();
    def.fail(function(){
        //- Deferred got rejected.
    });
*/
//
/**
@description Add one or more callbacks to be unconditionally called when a Deferred is resolved or rejected.
@function can.Deferred.prototype.always always
@parent can.Deferred.prototype
@signature `deferred.always(alwaysCallbacks)`
@param {Function|Array} alwaysCallbacks A function, or an array of functions, to be called whether the Deferred is resolved or rejected.

@body
`deferred.always(alwaysCallbacks)` adds one or more functions to be called when the Deferred object is either resolved or rejected.

    var def = can.Deferred();
    def.always(function(){
        //- Called whether the handler fails or is success.
    });
*/
//
/**
@description Add callbacks to a Deferred.
@function can.Deferred.prototype.then then
@parent can.Deferred.prototype
@signature `deferred.then(doneCallback[, failCallback])`
@param {Function} doneCallback A function called when the Deferred is resolved.
@param {Function} [failCallback] A function called when the Deferred is rejected.

@body
`deferred.then(doneCallback, failCallback)` adds handler(s) to be called when the Deferred object to be called after its resolved.

	var def = can.Deferred();
	def.then(function(success) {
		console.log(success);
	}, function(reason) {
		console.error(reason); //-> "Oh no! So sad."
	});

	def.reject("Oh no! So sad.");
*/
//
/**
@description Determine whether a Deferred has been resolved.
@function can.Deferred.prototype.isResolved isResolved
@parent can.Deferred.prototype
@signature `deferred.isResolved()`
@return {Boolean} Whether this Boolean has been resolved.

@body
`deferred.isResolved()` returns whether a Deferred object has been resolved.

    var def = can.Deferred();
    var resolved = def.isResolved();
*/
/**
@description Determine whether a Deferred has been rejected.
@function can.Deferred.prototype.isRejected isRejected
@signature `deferred.isRejected()`
@return {Boolean} Whether this Boolean has been rejected.

@body
`deferred.isRejected()` returns whether a Deferred object has been rejected.


    var def = can.Deferred();
    var rejected = def.isRejected()
*/
//
/**
@description Reject a Deferred.
@function can.Deferred.prototype.reject reject
@parent can.Deferred.prototype
@signature `deferred.reject([argument])`
@param {Object} [argument] The argument to call the `failCallback` with.

@body
`deferred.reject(args)` rejects the Deferred object and calls the fail callbacks with the given arguments.

	var def = can.Deferred();

	def.done(function(reason) {
	  console.log("Success! " + reason); //-> Success! Woohoo!
	});
	def.resolve("Woohoo!");
*/
//
/**
@description Resolve a Deferred.
@function can.Deferred.prototype.resolve resolve
@parent can.Deferred.prototype
@signature `deferred.resolve([argument])`
@param {Object} [argument] The argument to call the `doneCallback` with.

@body
`deferred.resolve( args )` resolves a Deferred object and calls the done callbacks with the given arguments.

    var def = can.Deferred();
    def.resolve({ animals: [ 'pig', 'cow' ] })
*/
//
/**
@function can.capitalize
@parent can.util
@description Capitalize the first letter of a string.
@signature `can.capitalize(str)`
@param {String} str The string to capitalize.
@return {String} The string with the first letter capitalized.

@body
    can.capitalize('candy is fun!'); //-> Returns: 'Candy is fun!'
*/
//
/**
@function can.camelize
@parent can.util
@description Capitalize the first letter after each hyphen in a string.
@signature `can.camelize(str)`
@param {String} str The string to camelize.
@return {String} The string with the first letter after each hyphen capitalized.

@body
    can.camelize('hello-world'); //-> Returns: 'helloWorld'
*/
//
/**
@function can.hyphenate
@parent can.util
@description Adds a hyphen before each uppercase letter and converts the entire string to lower case.
@signature `can.hyphenate(str)`
@param {String} str The string to hyphenate.
@return {String} The lowercase string with hyphens added before each letter that was uppercase in the source string.

@body
    can.hyphenate('helloWorld'); //-> Returns: 'hello-world'
*/
//
/**

@function can.sub
@parent can.util

@description Returns a string with {param} replaced values from data.

@signature `can.sub(str, data, remove, s)`
@param {String} str The string to make substitutes on
@param {Object} data The data to be used to look for properties.  If it's an array, multiple objects can be used.
@param {Boolean} [remove] if a match is found, remove the property from the object
@param {String} s The string to replace
@return {String} The converted string or `null` if any data to render are `undefined` or `null`

@body

`can.sub` returns a string with {param} replaced values from data, where `param` is the name of an object property.

    can.sub("foo {bar}", {bar: "far"}) //-> "foo far"
*/
//
/**
@function can.underscore
@parent can.util

@description Takes a CamelCase or mixedCase string and underscores the string on the capital letter.

@signature `can.underscore(str)`
@param {String} str The string to underscore
@return {String} the underscored string

@body

`can.underscore` takes a CamelCase or mixedCase string and underscores the string on the capital letter. If parts of the string are not CamelCase or mixedCase, it will not change them. `can.underscore` will lower case the entire string as well.

    can.underscore("OneTwo") //-> "one_two"
    can.underscore("OneTwo threeFour") //-> "one_two three_four"
*/
//
/**
@function can.esc
@parent can.util

@description Escapes a string for insertion into HTML.

@signature `can.esc(str)`
@param {String} str The string to escape
@return {String} The HTML escaped string.

@body

    can.esc( "<foo>&<bar>" ) //-> "&lt;foo&lt;&amp;&lt;bar&lt;"
*/
//
/**
@function can.getObject
@parent can.util
@signature `can.getObject(name, roots, add)`
Gets an object from a string.
@param {String} name the name of the object to look for
@param {Array} [roots] an array of root objects to look for the name.  If roots is not provided, the window is used.
@param {Boolean} [add] true to add missing objects to the path. false to remove found properties. undefined to not modify the root object
@return {Object} The object.
@body

Gets an object from a string.  It can also modify objects on the 'object path' by removing or adding properties.

    Foo = {Bar: {Zar: "Ted"}}
    can.getObject("Foo.Bar.Zar") //-> "Ted"
*/
//
/**
 * @typedef {{}} can.NodeList NodeList
 * @inherits From-Your-Base-Library
 *
 * A "NodeList" is __Library Specific__ implementation of
 * an array of DOM elements. [can.$] returns a "NodeList"
 * and [can.Control::element] is a "NodeList".
 *
 * The following details the "NodeList" object used
 * by each library.
 *
 * ## jQuery `jQuery( HTMLElement )`
 *
 * A [http://api.jquery.com/jQuery/ jQuery-wrapped] list of elements.
 *
 *     nodeList.text("Hello World")
 *
 * ## Zepto `Zepto( HTMLElement )`
 *
 * A Zepto-wrapped list of elements.
 *
 *     nodeList.text("Hello World")
 *
 * ## Dojo `new dojo.NodeList( HTMLElement )`
 *
 * Dojo's [http://dojotoolkit.org/reference-guide/1.9/dojo/NodeList.html dojo.NodeList] constructor function.
 *
 *     nodeList.text("Hello World")
 *
 *
 * ## Mootools `$$( HTMLElement )`
 *
 * The Mootools [Elements object](http://mootools.net/docs/core/Element/Element#Elements).
 *
 *     nodeList.empty().appendText("Hello World")
 *
 * ## YUI
 *
 * YUI's [NodeList](http://yuilibrary.com/yui/docs/node/).
 *
 *     nodeList.set('text',"Hello World")
 */
//
/**
 * @typedef {String} CSSSelectorString
 *
 * A [http://www.w3.org/TR/CSS2/selector.html CSS Selector] in a string like: `"#content .title"`.
 */
//
/**
 * @typedef {String} HttpMethod
 *
 * One of: `GET`, `POST`, `PUT`, or `DELETE`.
 */
