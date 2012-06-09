@page funcunit.integrations Integrations
@parent FuncUnit 5

FuncUnit integrates with third party browser automation and build tools.

## Browser automation

To integrate FuncUnit tests with QA automation, FuncUnit tests can be launched by several methods.

[funcunit.selenium Selenium] launches visual browsers. It runs selenium tests and reports results to the 
commandline.

@codestart
./js funcunit/run selenium path/to/funcunit.html
@codeend

[funcunit.envjs Envjs] is a simulated browser environment that runs in Rhino. It does not support visual 
simulation, like clicking or dragging, but can be used for basic unit testing.

@codestart
./js funcunit/run envjs path/to/qunit.html
@codeend

[funcunit.phantomjs PhantomJS] is a headless version of WebKit. It supports runnning full FuncUnit tests 
without actually launching a browser. It is faster than using Selenium to launch browsers.

@codestart
./js funcunit/run phantomjs path/to/funcunit.html
@codeend

## Build tools

Using browser automation tools, FuncUnit can be integrated into the project build.

[funcunit.maven Maven] is a build tool used with Java projects. FuncUnit tests can be run 
as a maven build step. When tests fail, the build also fails.

[funcunit.jenkins Jenkins] is a continuous integration tool, used to continuously run builds, tests, and 
report on the health of the codebase. FuncUnit can be tied into Jenkins and made to fail the Jenkins build 
if FuncUnit tests aren't passing, which would alert developers of problems immediately. 

## Settings.js

Settings.js is a file used to configure options used by the automation tools. Here is an example of a 
settings.js file.

@codestart
FuncUnit = {
	// the list of browsers that selenium runs tests on
	browsers: ["*firefox"],
	
	// the number of milliseconds between Selenium commands, "slow" is 500 ms
	speed: null, //"slow"
}
@codeend

When you launch tests with an automation tool as shown above, FuncUnit first looks for a settings.js file 
in the same directory as the test page. If one is not found, it uses funcunit/settings.js as the default. 

For example, if your funcunit test is in <code>contacts/tests/funcunit.html</code> you'd put a settings.js 
file in <code>contacts/tests/settings.js</code>.

## Commandline output

When browser automation tools run, the events that QUnit publishes (testStart, testDone, etc) are captured and 
used for reporting. FuncUnit comes with two reporters. Each listens to the events and prints results to the 
console. You can also provide your own custom reporter.

The reporter used is configured in settings.js.  The <code>output</code> property is a string that corresponds 
to the name of the JS file in the <code>funcunit/commandline/output</code> directory that should be used for 
reporting. This defaults to XUnit. 

### XUnit

[http://xunit.codeplex.com XUnit] is a unit testing framework for .NET, whose reporting format is now used in tools across different 
platforms.  XUnit defines a [http://xunit.codeplex.com/wikipage?title=XmlFormat standard XML test output file format]. 

Tools like Jenkins can read this format.  By using the FuncUnit XUnit reporter, every time FuncUnit runs, a test 
file called <code>testresults.xml</code> is written to the main directory of your project. 

Results are also printed to the console in an easy to read format.

@codestart
MABOSBMOSCHE-M1:jmvc31 bmoschel$ ./js funcunit/run phantomjs cookbook/funcunit.html 
Using Default Settings
starting steal.browser.phantomjs

recipe
  recipes present
    [x] There is at least one recipe
  create recipes
    [x] Typed Ice
  edit recipes
    [x] Typed Cold Tap Water
  destroy
    [x] Typed Ice Water
    [x] 

Time: 11 seconds, Memory: 81.06 MB

OK (4 tests, 5 assertions)
@codeend

### Customizing output

You can easily provide your own custom reporting output and behavior.

1. Create a file in the output directory like <code>funcunit/commandline/output/myreporter.js</code>
1. Change the output property in settings.js to "myreporter"
1. In your reporter, add methods for all the FuncUnit events (<code<FuncUnit.<eventName></code>) 
and report accordingly.

FuncUnit events (which mostly correspond to [http://docs.jquery.com/Qunit#API_documentation QUnit events]) 
include:

- browserStart - fired when Selenium starts a new browser
- browserDone - fired when Selenium finishes a browser
- testStart - a new test begins
- testDone - a test is done
- moduleStart - a module begins
- moduleDone - a module is done
- done - all tests are complete
- log - a console.log has been used

@codestart
FuncUnit.done = function( failures, total ){
  print("There were " + failures + " failed tests out of " + total;
})
@codeend
