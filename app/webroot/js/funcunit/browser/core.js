(function(){
	
	//if there is an old FuncUnit, use that for settings
	var oldFuncUnit = window.FuncUnit;

/**
@class FuncUnit
@parent index 2
@test test.html
@download http://github.com/downloads/jupiterjs/funcunit/funcunit-beta-5.zip

FuncUnit tests web applications with a simple jQuery-like syntax. Via integration with 
[funcunit.selenium Selenium] and [funcunit.phantomjs PhantomJS], you can run the same tests automated.

FuncUnit uses [http://docs.jquery.com/Qunit QUnit] for organizing tests and assertions.  But FuncUnit extends QUnit so you can:

 - [FuncUnit.open Open] a web page
 - [funcunit.finding Query] for elements
 - [funcunit.actions Simulate] a user action
 - [funcunit.waits Wait] for a condition to be true
 - [funcunit.getters Get] information about your page and run assertions
 
Then, you can:
 
 - Run tests in the browser
 - [funcunit.integrations Integrate] with browser automation and build tools

The [funcunit.getstarted FuncUnit Getting Started] guide is a quick walkthrough of creating and running a test.

## Set up a test

[http://docs.jquery.com/Qunit QUnit] provides the basic structure needed to write unit or functional tests.

__Module__

[http://docs.jquery.com/QUnit/module#namelifecycle Modules] are groups of tests with setup and teardown methods that run for each test.

    module("Contacts", {
      // runs before each test
      setup: function(){
        // setup code
      },
      // runs after each test
      teardown: function(){
        // cleanup code
      }
    })

__Test__

    test("findOne", function(){
      // define a test
    })

__Assertions__

    test("counter", function() {
      ok(Conctacts.first().name, "there is a name property");
      equal(Contacts.counter(), 5, "there are 5 contacts");
    });

## Open a page

The following uses <code>S.open( URL )</code> to open autocomplete.html before every test.

    module("autosuggest",{
      setup: function() {
        S.open('autosuggest.html')
      }
    });

## Query for elements

FuncUnit tests are written just like jQuery.  The [funcunit.finding S method] is a copy of jQuery, which queries for elements in 
the application page.  Like $, FuncUnit methods are chainable on the results of S.

    // grab the #description element, wait for it to be visible, type in it
    S("#description").visible().type("Test Framework")

## Simulate user actions

When you're testing a widget, you need to simulate the [funcunit.actions actions] that a user takes.  FuncUnit uses the 
[syn] library to accurately simulate the correct low level events like mouseup and keypress for high 
level actions like [click] and [type].  The following shows how to simulate common user actions.

__Click__

    // click a button
    S('#submit_button').click()

__Type__

    // type in an input
    S('#task_name').type("Learn FuncUnit")

__Drag__

    // drag a task item to the trash area
    S('.task').drag(".trash");

## Wait for page conditions

After a user action, your test page's event handlers run and the page is changed. 
Wait commands are used to wait for some page condition before continuing.

Waits are overloaded jQuery getter methods.  <code>S.fn.text( textVal, callback )</code> 
waits for an element's $.fn.text to match the textVal.

    // wait for result to show "task complete"
    S("#result").text("task complete")

__Visible__

    // wait for first result to be visible
    S('#autocomplete_results:first-child').visible()

__Width__

    // after clicking a menu item, wait for its width to be 200px
    S('#horizontal_menu_item').width(200)

__Val__

    // wait for the input value
    S('#language_input').val("JavaScript")

__Size__

    // wait for number of matched elements
    S('.contact').size(5)

There are many more [funcunit.waits waits] possible. 


## Get information and run assertions

After simulating an action and waiting for the page to change, you often want to get information 
about an element and run assertions.  You can use jQuery getter methods in combination with QUnit assertions.

These methods (which return synchronous results) are used in callbacks that run after a wait method completes.

    // wait until we have some results, then call the calback
    S('.autocomplete_item').visible(function(){
      equal( S('.autocomplete_item').size(), 5, "there are 5 results")
    })

## Running in browser

These tests can be loaded in any browser.  The app page opens in a separate window and results show up in the QUnit page.

    test("JavaScript results",function(){
      S('input').click().type("JavaScript")
    
      // wait until we have some results
      S('.autocomplete_item').visible(function(){
        equal( S('.autocomplete_item').size(), 5, "there are 5 results")
      })
    });

<a href='funcunit/test/autosuggest/funcunit.html'>Run this test</a> (turn off your popup blocker!)

## Integrating with automation and build tools

The same tests can be run via browser automation tools: [funcunit.selenium Selenium], 
[funcunit.phantomjs PhantomJS], and [funcunit.envjs Envjs].

These tools are driven via commandline.

    js funcunit/run phantomjs path/to/funcunit.html

Results are reported on the commandline.  Failed tests can be made to fail your build via [funcunit.maven Maven] 
or integrated with CI tools like [funcunit.jenkins Jenkins].

 */
	FuncUnit = oldFuncUnit.jQuery.sub();
	var origFuncUnit = FuncUnit;
	// override the subbed init method
	// context can be an object with frame and forceSync:
	// - a number or string: this is a frame name/number, and means only do a sync query
	// - true: means force the query to be sync only
	FuncUnit = function( selector, context ) {
		// if you pass true as context, this will avoid doing a synchronous query
		var frame,
			forceSync, 
			isSyncOnly = false,
			isAsyncOnly = false;
			
		if(typeof context === "string"){
			frame = context;
		}
		if(context && typeof context.forceSync === "boolean"){
			forceSync = context.forceSync;	
			if (typeof context.frame == "number" || typeof context.frame == "string") {
				frame = context.frame;
				context = context.frame;
			}
		}
		isSyncOnly = typeof forceSync === "boolean"? forceSync: isSyncOnly;
		// if its a function, just run it in the queue
		if(typeof selector == "function"){
			return FuncUnit.wait(0, selector);
		}
		// if the app window already exists, adjust the params (for the sync return value)
		this.selector = selector;
		// run this method in the queue also
		if(isSyncOnly === true){
			var collection = performSyncQuery(selector, context, this);
			collection.frame = frame;
			return collection;
		} else if(isAsyncOnly === true){
			performAsyncQuery(selector, context, this);
			return this;
		} else { // do both
			performAsyncQuery(selector, context, this);
			var collection = performSyncQuery(selector, context, this);
			collection.frame = frame;
			return collection;
		}
	}
	
	var getContext = function(context){
			if (typeof context === "number" || typeof context === "string") {
				// try to get the context from an iframe in the funcunit document
				var sel = (typeof context === "number" ? "iframe:eq(" + context + ")" : "iframe[name=" + context + "]"),
					frames = new origFuncUnit.fn.init(sel, FuncUnit.win.document, true);
				context = (frames.length ? frames.get(0).contentWindow : FuncUnit.win).document;
			} else {
				context = FuncUnit.win.document;
			}
			return context;
		},
		performAsyncQuery = function(selector, context, self){
			FuncUnit.add({
				method: function(success, error){
					if (FuncUnit.win) {
						context = getContext(context);
					}
					this.selector = selector;
					this.bind = new origFuncUnit.fn.init( selector, context, true );
					success();
					return this;
				},
				error: "selector failed: " + selector,
				type: "query"
			});
		},
		performSyncQuery = function(selector, context, self){
			if (FuncUnit.win) {
				context = getContext(context);
			}
			return new origFuncUnit.fn.init( selector, context, true );
		}
	
	oldFuncUnit.jQuery.extend(FuncUnit, oldFuncUnit, origFuncUnit)
	FuncUnit.prototype = origFuncUnit.prototype;
	S = FuncUnit;
	
	
})()
