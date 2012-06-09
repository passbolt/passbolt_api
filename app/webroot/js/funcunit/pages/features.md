@page funcunit.features Features
@parent FuncUnit 9

## Why FuncUnit

TESTING IS PAINFUL.  Everyone hates testing, and most front end developers simply don't test.  There 
are a few reasons for this:

1. **Barriers to entry** - Difficult setup, installation, high cost, or difficult APIs.  QTP costs $5K per license.
2. **Completely foreign APIs** - Testing frameworks often use other languages (Ruby, C#, Java) and new APIs.
3. **Debugging across platforms** - You can't use firebug to debug a test that's driven by PHP.
4. **Low fidelity event simulation** - Tests are often brittle or low fidelity because frameworks aren't designed to test heavy JavaScript apps, so 
browser event simualation accuracy isn't a top priority.
5. **QA and developers can't communicate** - If only QA has the ability to run tests, sending bug reports is messy and time consuming.

FuncUnit aims to fix these problems:

1. FuncUnit is free and has no setup or installation (just requires Java and a browser). 
2. FuncUnit devs know jQuery, and FuncUnit leverages that knowldge with a jQuery-like API.
3. You can run tests in browser and set Firebug breakpoints.
4. Syn.js is a low level event simuation library that goes to extra trouble to make sure each browser simulates events exactly as intended.
5. Since tests are just JS and HTML, they can be checked into a project and any dev can run them easily.  QA just needs to send a URL to a broken 
test case.

There are many testing frameworks out there, but nothing comes close to being a complete solution for front end testing like FuncUnit does.

## Changes from 3.1

FuncUnit has undergone some big changes from JavaScriptMVC 3.1.

### Commandline revamp

The method of running from commandline is now more consistent. You configure reporting and settings the same 
way in settings.js.  Env, Phantom, and Selenium work in the same way.

### Steal.browsers

The new steal.browser API is a layer that abstracts opening a browser using a browser automation tool and 
makes it easy to add new ones.  PhantomJS was a new browser added with this API. Steal.browser can be used in 
other parts of JavaScriptMVC, like steal/html.

### No more separate browser drivers

There used to be separate browser drivers for running in browser and selenium. Now there is only one way of 
using the FuncUnit API, which brings consistency no matter the environments.

The driver system created difficult to debug issues when running from Selenium, which would not occur in 
browser mode. Without drivers, tests run more consistently.

Also, implementing features in both drivers was difficult and required a complex build step. Adding 
to the FuncUnit API is now simpler and faster.

### CI integration

Integration with Jenkins and Maven are part of the 3.2 changes. Using the XUnit plugin and passing error 
codes, you can more easily build FuncUnit into your build and CI system.

Its also possible to easily customize FuncUnit output by making your own reporting functionality. All you have 
to do is add your own FuncUnit event handlers.

### Passing around collections

FuncUnit's internals are reworked so that S is now a subbed version of jQuery.  S calls $ with the context 
set to your app window. "this" always points to current collection, whereas before it wasn't possible to access 
the actual collection, just the selector.

This has several advantages. Its easier to add plugins to S.  Its also easier to get information about 
the elements, using any jQuery.fn method.  Debugging is easier, because there is more visibility while 
executing asynchronous callbacks.  You can inspect at various points and see why the test is failing

### No breaking changes

We were very careful while making these changes to not break the API.  Any existing tests will still 
work, but will be less brittle when using Selenium and other automation tools.

** There is actually one minor difference, which most users won't ever notice. It used to be that you could 
"cache" S objects and reuse them within callbacks.  The query they represented would be re-run.  In 3.2+ FuncUnit, 
this is no longer the case.  Since S objects are just jQuery collections, synchronous queries need to be rerun by 
calling S again.

### Updated to latest QUnit

The latest version of QUnit has a few nice features and an updated UI.

### PhantomJS

PhantomJS support now makes it more feasible to run FuncUnit tests as part of a build process.

## Roadmap

The next FuncUnit features we plan to work on include. Let us know if you want these features or want to help!

* [http://siliconforks.com/jscoverage/ JSCoverage] integration for test coverage stats
* [http://code.google.com/p/js-test-driver/ JSTestDriver] integration

## Comparison to other frameworks

### Functional vs unit

### Automated vs browser based

### Accurate event simulation