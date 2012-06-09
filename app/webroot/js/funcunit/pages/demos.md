@page funcunit.demos Demos
@parent FuncUnit 8

This page contains examples of tests that solve common problems.

## Login

If an application requires login, the tests to automate logging in before we can reach 
the other pages.  Usually when testing in browser mode, this step can be skipped, since 
developers will already be logged in when running tests.  Here's a login step that only runs 
when using automation tools

@codestart
test("login test", function () {
	if (/mode=commandline/.test(window.location.search)) {
		S.open("/login")
		S("#username").exists().click().type("superadmin")
		S("#password").exists().click().type("password")
		S(".submit input").exists().click()
		
		// wait for next page to load
		S(".content").visible(function () {
			ok(true, "logged in");
		})
	} else {
		ok(true, "assuming you are logged in");
	}
})
@codeend

## Testing with fixtures

Its often very useful to fixturize your entire application, so that tests can be run without any 
server running.  This allows you to isolate problems to JavaScript code. It also allows you to write 
non-brittle tests, since the data you depend on is not changing.  A good pattern for this is to load 
a settings file, which loads fixtures whenever an application page is opened via filesystem.

@codestart
if(location.protocol === "file:" || /fixtures\=on/.test(location.search)){
	steal('fixtures');
}
@codeend

## Srchr Smoke Test

This section will walk through creating a smoke test for the [http://javascriptmvc.com/srchr/srchr.html Srchr application].  
Srchr is a simple demo application that lets you search several sources for images.  There is a search pane, 
tabs, a history pane, and a results area.

@image jmvc/images/srchr.png

The purpose of a smoke test is to test enough functionality in an application to verify its working correctly, as 
quickly as possible.

In this smoke test we'll:

1. click the Flickr search option
1. type "puppy" in the search box
1. wait for results to show up in the results panel
1. verify 10 results are visible
1. verify the history panel shows "puppy"

Let's start by creating a skeleton test with some pseudocode:

@codestart
module("Srchr",{
	setup: function() {
		S.open("//srchr/srchr.html");
	}
})

test("Smoke Test", function(){
	// click search options
	// type puppy
	// wait for results
	// verify 10 results
	// verify history has puppy
})
@codeend

Now lets start to fill in these commands, leaving the actual selectors for last.

@codestart
flickrInput.click();
// \r means hit enter, which submits the form
searchInput.click().type("puppy\r");
resultElements.visible(function(){
	equals(resultsElements.size(), 10, "There are 10 results");
	ok(/puppy/.test( historyEl.text() ), "History has puppy");
})
@codeend

In this test, we're performing the search, waiting for results to appear, then asserting conditions of our page. 
Here's the test with selectors filled in:

@codestart
 S('#cb_flickr').click();
 S('#query').click().type('puppy\r');
 
 S('#flickr li').visible(function(){
      equals(S('#flickr li').size(), 10, 'There are 10 results')
      ok( /puppy/.test( S('#history .text').text() ), 'History has puppy')
 })
@codeend