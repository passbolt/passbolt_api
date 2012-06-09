@page funcunit.finding Finding Elements
@parent FuncUnit 1

## The S Method

S is a copy of the $ method.  It is used to find elements in the page you're testing.  It works 
slightly differently than $.

@codestart
S( selector, [context] )
@codeend

### Params

__selector__ <code>{String|Function|Object}</code>

_String_

The S method accepts any valid jQuery selector string, just like $.  One difference from $ is that this 
query will happen in the context of the window of the page you're testing, not the QUnit page where it runs.

Depending on the context in which S is called, this selector may be used immediately and return a jQuery 
collection, or it may cache the selector, which will be used in the asynchronous queue later to find elements 
when previous queued methods have finished.  Read more about this below.

@codestart
// look up .foo elements in the application window
S(".foo")
@codeend

_Function_

If a function is provided, it will be added to the queue to be run after previous queued methods.

@codestart
// Wait for this to be visible
S(".grid").visible()

// Run after the previous wait completes
S(function(){
	ok(S(".foo").hasClass('bar'))
})
@codeend

_Object_

If you want to reference the test page's window or document, pass <code>S.window</code> 
or <code>S.window.document</code>.

@codestart
// click the test page's document element
S(S.window.document).click()
@codeend

__[context]__ <code>{Number|String}</code>

Context is an optional parameter.  If provided, the number or string is used as the 
frame number or name in the document.frames array of the tested page.  The frame is looked up, 
and this is passed as the context of the query.

@codestart
// find something in the 0th frame
S("a.mylink", 0)

// find something within the frame that has name="myframe"
S("a.another, "myframe")
@codeend

### Synchronous vs asynchronous queries

As mentioned above, S sometimes performs synchronous queries and returns a jQuery collection object.  
Other times, it returns nothing, caches the selector, and adds a method to [FuncUnit._queue].

__Synchronous__

All [funcunit.actions actions] and [funcunit.waits waits] are asynchronous commands.  They add themselves to 
[FuncUnit.queue] and run in order.  Each action and wait accepts an optional callback parameter.  This callback 
runs after the queued method completes.

Inside these callbacks, you get information about the page and perform assertions, synchronously. 
Any time S is called inside a callback, it runs synchronously and returns a jQuery collection.

@codestart
S(".foo").visible(function(){
  // this will run immediately and return a $ collection
  var size = S(".bar").size();
  equal(size, 5);
})
@codeend

Inside these callbacks, you can set breakpoints and debug tests.

Sometimes, you want to get information about the page as soon as a test begins.  Later in a callback, 
you can compare this initial value with the current value.

If you call S at the start of a test, it will also run immediately and return a $ collection. Until the first 
item is added to the queue, S will run synchronously.

@codestart
test("contacts test", function(){
  // runs synchronously
  var origNbrItems = S(".contacts").size();
  S(".addNew").click(function(){
    var newNbrItems = S(".contacts").size();
    ok(newNbrItems > origNbrItems);
  });
})
@codeend

Beware that you MUST put any synchronous getters inside a callback or at before any actions or waits.

@codestart
test("contacts test", function(){
  S(".addNew").click(function(){
    var newNbrItems = S(".contacts").size();
    ok(newNbrItems > origNbrItems);
  });
  // this will fail!  
  var origNbrItems = S(".contacts").size();
})
@codeend

__Asynchronous__

Except for the cases outlined above, the S method needs to run asynchronously in the correct queue order. In this case, doing an 
immediate query and returning a collection would be wasteful and slow down test performance.

@codestart
// 1. query for .container
// 2. add a method to the queue that repeatedly checks when .container is visible
S(".container").visible();

// 3. when the previous method completes, do a query for .foo
// 4. click .foo
S(".foo").click()
@codeend

### Why S?

S is a "copy" of $, created using [http://api.jquery.com/jQuery.sub/ jQuery.sub].  All FuncUnit methods, 
like actions, waits, and traversers, are added to S.fn.  All the jQuery methods that FuncUnit doesn't 
overload are callable on S collections. 

The reason for this is to preserve jQuery in the test page, unmodified.  If you want to use jQuery, none of 
its methods are modified. jQuery can be used to do unit testing, or to directly access the test page and do 
custom things.

@codestart
// accessing elements within the test page
ok($(".foo").hasClass("bar"))
@codeend

### Extending S

Occassionally there will be tests that need some jQuery plugins to run correctly.  To extend S 

1. Load your jQuery plugin
1. Add the plugin method to S.fn

@codestart
steal("funcunit", "resources/myplugin.js", function(){
  S.fn.myplugin = $.fn.myplugin;
  // test code goes here
})
@codeend 

Most likely this method works synchronously so you have to use it inside a callback, where S is 
returning synchronously.

@codestart
// inside a wait callback, S returns a jQuery collection
S(".foo").visible(function(){
  // call myplugin on jQuery collection
  var els = S(".contact").myplugin()
})
@codeend

## Traversing

FuncUnit provides its own asynchronous versions of jQuery traverser methods 
[FuncUnit.prototype.find find], [FuncUnit.prototype.closest closest], 
[FuncUnit.prototype.next next], [FuncUnit.prototype.prev prev], 
[FuncUnit.prototype.siblings siblings], [FuncUnit.prototype.last last], 
and [FuncUnit.prototype.first first].  The FuncUnit versions add themselves to 
[FuncUnit._queue].  When they are called, they take the current jQuery collection object, 
modify it with their traversal, and pass the result to the next method in the queue.

@codestart
// click .container, wait for width to be 500px
S(".container").click().width(500)
  // find .contact inside container, click it
  .find(".contact").click()
@codeend
