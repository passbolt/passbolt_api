throw "New Steal shouldn't include can/util/util.js";

var library = 'can/util/jquery';
if (window.STEALSTANDALONE){
	library = 'can/util/standalone';
} else if (window.STEALDOJO){
	library = 'can/util/dojo';
} else if( window.STEALMOO) {
	library = 'can/util/mootools';
} else if(window.STEALYUI){
	library = 'can/util/yui';
} else if(window.STEALZEPTO){
	library = 'can/util/zepto';
}

steal(library, function(can) {
	return can;
});

/**
 @function can.isDeferred
 @parent can.util

 `can.isDeferred` returns if an object is an instance of [can.Deferred].

 ## Example
 Convert any value to a Deferred:

 function convertDeferred(dfd) {
 return can.isDeferred(dfd) ? dfd : can.Deferred(dfd);
 }

 @param {String} str the string to trim
 @return {String} the value of the string
 */

/**
@function can.trim
@parent can.util

`can.trim(string)` removes leading and trailing whitespace from a string.  It will also
remove all newlines, spaces including non-breaking, and tabs.  If these occur in the middle
of the string, then they will be persisted.

    can.trim( " foo " ) // -> "foo"

@param {String} str the string to trim
@return {String} the value of the string
 */
//
/**
@function can.makeArray
@parent can.util

`can.makeArray(object)` convert an array-like object into a array.

    can.makeArray({0 : "zero", 1: "one", length: 2})
       // -> ["zero","one"]

@param {Object} object to transform into array
@return {Array} converted array
 */
//
/**
@function can.isArray
@parent can.util

`can.array(object)` returns if the object is an Array.

    can.isArray([]) //-> true
    can.isArray(false)

@param {Array} arr any JS object
@return {Boolean} true if an array
*/
//
/**
@function can.each
@parent can.util

`can.each(object, function)` iterates through an array or object like
like [http://api.jquery.com/jQuery.each/ jQuery.each].

    can.each([{prop: "val1"}, {prop: "val2"}], 
		function( value, index ) {
      // function called with
      //  index=0 value={prop: "val1"}
      //  index=1 value={prop: "val2"}
    })

@param {Object} arr any JS object or array
@return {Object} the function passed to can.each
*/
//
/**
@function can.extend
@parent can.util

`can.extend(target, objectN)` merges the contents of two or more objects together into the first object
similarly to [http://api.jquery.com/jQuery.extend/ jQuery.extend].

    var first = {},
        second = {a: "b"},
        thrid = {c: "d"};

    can.extend(first, second, third); //-> first

    first  //-> {a: "b",c : "d"}
    second //-> {a: "b"}
    thrid  //-> {c: "d"}

@param {Object} target The target object to extend
@param {Object} [object1] An object containing properties to merge
@param {Object} [objectN] Additional objects containing properties to merge
@return {Object} The target object
*/
//
/**
@function can.param
@parent can.util
Parameterizes an object into a query string
like [http://api.jquery.com/jQuery.param/ jQuery.param].

    can.param({a: "b", c: "d"}) //-> "a=b&c=d"

@param {Object} obj An array or object to serialize
@return {String} The serialized object
*/
//
/**
@function can.isEmptyObject
@parent can.util
`can.isEmptyObject(object)` returns if an object has no properties similar to
[http://api.jquery.com/jQuery.isEmptyObject/ jQuery.isEmptyObject].

    can.isEmptyObject({})      //-> true
    can.isEmptyObject({a:"b"}) //-> false

@param {Object} object to evaluate if empty or not
@param {Boolean} Whether the object is empty
*/
//
/**
@function can.proxy
@parent can.util
`can.proxy(function)` accepts a function and returns a 
new one that will always the context from which it was 
called.  This works similar to [http://api.jquery.com/jQuery.proxy/ jQuery.proxy].

     var func = can.proxy(function(one){
       return this.a + one
     }, {a: "b"}); 
     func("two") //-> "btwo" 

@param {Function} function to return in the same context
@param {Object} context The context for the new function
@return {Function} The new function
*/
//
/**
@function can.isFunction
@parent can.util
`can.isFunction(object)` returns if an object is a function similar to
[http://api.jquery.com/jQuery.isFunction/ jQuery.isFunction].

     can.isFunction({})           //-> false
     can.isFunction(function(){}) //-> true

@param {Object} object to evaluate if is function
@return {Boolean} Whether the object is a function
*/
//
/**
@function can.bind
@parent can.util

`can.bind(eventName, handler)` binds a callback handler
on an object for a given event.  It works on:

  - HTML elements and the window
  - Objects
  - Objects with bind / unbind methods
  
The idea is that bind can be used on anything that produces events
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
    })

@param {String} eventName The type of event to bind to
@param {Function} handler The handler for the event
@return {Object} this
*/
//
/**
@function can.unbind
@parent can.util

`can.unbind(eventName, handler)` unbinds a callback handler
from an object for a given event.  It works on:

  - HTML elements and the window
  - Objects
  - Objects with bind / unbind methods
  
The idea is that unbind can be used on anything that produces events
and it will figure out the appropriate way to 
unbind to it.  Typically, `can.unbind` is only used internally to
CanJS; however, if you are making libraries or extensions, use
`can.bind` to listen to events independent of the underlying library.


__Binding/unbinding to an object__

    var obj = {},
      handler = function(ev, arg1, arg){
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

@param {String} eventName The type of event to unbind from
@param {Function} handler The handler for the event
@return {Object} this
*/
//
/**
@function can.delegate
@parent can.util

`can.delegate(selector, eventName, handler)` binds a delegate handler
on an object for a given event.  It works on:

  - HTML elements and the window
  
The idea is that delegate can be used on anything that produces delegate events
and it will figure out the appropriate way to 
bind to it.  Typically, `can.delegate` is only used internally to
CanJS; however, if you are making libraries or extensions, use
`can.delegate` to listen to events independent of the underlying library.

__Delegate binding to an HTMLElement__

    var el = document.getElementById('foo')
    can.delegate.call(el, ".selector", "click", function(ev){
      this // el
    })

@param {String} selector The selector to delegate
@param {String} eventName The type of event to bind to
@param {Function} handler The handler for the event
@return {Object} this
*/
//
/**
@function can.undelegate
@parent can.util

`can.undelegate(selector, eventName, handler)` unbinds a delegate handler
on an object for a given event.  It works on:

  - HTML elements and the window
  
The idea is that undelegate can be used on anything that produces delegate events
and it will figure out the appropriate way to 
bind to it.  Typically, `can.undelegate` is only used internally to
CanJS; however, if you are making libraries or extensions, use
`can.undelegate` to listen to events independent of the underlying library.

__Delegate/undelegate binding to an HTMLElement__

    var el = document.getElementById('foo'),
      handler = function(ev){
        this // el
      };
    can.delegate.call(el, ".selector", "click", handler)
    can.undelegate.call(el, ".selector", "click", handler)

@param {String} selector The selector to undelegate
@param {String} eventName The type of event to unbind from
@param {Function} handler The handler for the event
@return {Object} this
*/
//
/**
@function can.trigger
@parent can.util

Trigger an event on an element or object.

@param {can.$|Object} obj The object to trigger the event on
@param {String} event The event to trigger
@param {Array} [args] The event data
 */
//
/**
@function can.ajax
@parent can.util

`can.ajax( settings )` is used to make an asynchronous HTTP (Ajax) request 
similar to [http://api.jquery.com/jQuery.ajax/ jQuery.ajax].

	can.ajax({
		url: 'ajax/farm/animals',
		success: function(animals) {
			can.$('.farm').html(animals);
		}
	});

@param {Object} options Ajax request configuration options
@return {Deferred}
*/
//
/**
@function can.$
@parent can.util

`can.$(selector|element|elements)` returns the the underlying
library's NodeList.  It can be passed
a css selector, a HTMLElement or an array of HTMLElements.

The following lists how the NodeList is created by each library:

 - __jQuery__ `jQuery( HTMLElement )`
 - __Zepto__ `Zepto( HTMLElement )`
 - __Dojo__ `new dojo.NodeList( HTMLElement )`
 - __Mootools__ `$$( HTMLElement )`
 - __YUI__ `Y.all(selector)` or `Y.NodeList`

@param {String|Element|NodeList} selector The selector to pass to the underlying library
@return {NodeList}
*/
//
/**
@function can.buildFragment
@parent can.util

`can.buildFragment(html, node)` returns a document fragment for the HTML passed.

@param {String} html HTML strings
@param {Array} node element used for accessing the ownerDocument
@return {DocumentFragment}
*/
//
/**
@function can.append
@parent can.util

`can.append( wrappedNodeList, html )` inserts content to the end of each wrapped node list item(s) passed.

	// Before
	<div id="demo" />
	
	can.append( can.$('#demo'), 'Demos are fun!' );
	
	// After
	<div id="demo">Demos are fun!</div>

@param {Object} wrappedNodeList
@param {String} html string to append
*/
//
/**
@function can.remove
@parent can.util

`can.remove( wrappedNodeList )` removes the set of matched element(s) from the DOM.

	<div id="wrap"/>
	can.remove(can.$('#wrap')) //-> removes 'wrap'
	
@param {Object} wrappedNodeList of elements to remove from dom.
*/
//
/**
@function can.data
@parent can.util

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

@param {NodeList} wrapped The wrapped node list to associate data with
@param {String} key The data property to access
@param {Object} [value] The data value to store
@return {Object} The value for the given key
*/
//
/**
@function can.addClass
@parent can.util

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

@param {String} class name to add to the wrapped node list
*/
//
/**
@function can.when
@parent can.util

`can.when(args)` provides the ability to execute callback function(s) 
typically based on a Deferred or AJAX object.

	can.when( can.ajax('api/farm/animals') ).then(function(animals){ 
     	alert(animals); //-> alerts the ajax response
	});
	
You can also use this for regular JavaScript objects.

	$.when( { animals: [ 'cat' ] } ).done(function(animals){ 
		alert(animals[0]); //-> alerts 'cat' 
	});

@param {Object} deferreds ajax or JavaScript objects
*/
//
/**
@class can.Deferred
@parent can.util

`can.Deferred` is a object that allows users to assign and chain callback 
function(s) for the success or failure state of both asynchronous and synchronous function(s).

*/
//
/*
 * @prototype
 */
//
/**
@function pipe
`deferred.pipe(done, fail)` is a utility to filter Deferred(s).

	var def = can.Deferred(),
		filtered = def.pipe(function(val) {
			return val + " is awesome!";
		});

	def.resolve('Can');

	filtered.done(function(value) {
		alert(value); // Alerts: 'Can is awesome!'
	});

@param {Object} doneCallbacks A function called when the Deferred is resolved.
@param {Object} failCallbacks A function called when the Deferred is rejected.
*/
//
/**
@function resolveWith
`deferred.resolveWith(context, doneCallbacks)` resolves a Deferred and calls the `doneCallbacks` give the arguments.

	var def = can.Deferred();
	def.resolveWith(this, { animals: [ 'cows', 'monkey', 'panda' ] })
	
@param {Object} context Context passed to the `doneCallbacks` as the `this` object.
@param {Object} args Optional array of args that are passed to the `doneCallbacks`.
*/
//
/**
@function rejectWith
`deferred.rejectWith(context, failCallbacks)` rejects a Deferred and calls the `failCallbacks` give the arguments.

	var def = can.Deferred();
	def.rejectWith(this, { error: "Animals are gone." })
	
@param {Object} context Context passed to the `doneCallbacks` as the `this` object.
@param {Object} args Optional array of args that are passed to the `failCallbacks`.
*/
//
/**
@function done
`deferred.done(successCallbacks)` adds handler(s) to be called when the Deferred object is resolved.

	var def = can.Deferred();
	def.done(function(){
		//- Deferred is done.
	});

@param {Object} successCallbacks function that is called when the Deferred is resolved.
 */
/**
 * @function fail

`deferred.fail(successCallbacks)` adds handler(s) to be called when the Deferred object is rejected.

 var def = can.Deferred();
 def.fail(function(){
 //- Deferred got rejected.
 });
 */
//
/**
@function always
`deferred.always( alwaysCallbacks )` adds handler(s) to be called when the Deferred object is either resolved or rejected.

	var def = can.Deferred();
	def.always( function(){
		//- Called whether the handler fails or is success.
	});

@param {Object} alwaysCallbacks A function called when the Deferred is resolved or rejected.
*/
//
/**
@function then
`deferred.then( doneCallbacks, failCallbacks )` adds handler(s) to be called when the Deferred object to be called after its resolved.

	var def = can.Deferred();
	def.then(function(){
		//- Called when the deferred is resolved.
	}, function(){
		//- Called when the deferred fails.
	})

@param {Object} doneCallbacks A function called when the Deferred is resolved.
@param {Object} failCallbacks A function called when the Deferred is rejected.
*/
//
/**
@function isResolved
`deferred.isResolved()` returns whether a Deferred object has been resolved.

	var def = can.Deferred();
	var resolved = def.isResolved(); 
	
*/
/**
@function isRejected
`deferred.isRejected()` returns whether a Deferred object has been rejected.

	var def = can.Deferred();
	var rejected = def.isRejected()

*/
//
/**
@function reject
`deferred.reject( args )` rejects the Deferred object and calls the fail callbacks with the given arguments.

	var def = can.Deferred();
	def.reject({ error: 'Thats not an animal.' })

@param {Object} arguments Optional arguments that are passed to the fail callbacks.
*/
//
/**
@function resolve
`deferred.resolve( args )` resolves a Deferred object and calls the done callbacks with the given arguments.

	var def = can.Deferred();
	def.resolve({ animals: [ 'pig', 'cow' ] })

@param {Object} arguments Optional arguments that are passed to the done callbacks.
*/
//
