@page funcunit.waits Waits
@parent FuncUnit 3

Wait commands are used to check conditions of your page. The test checks a condition repeatedly 
until its either true or a timeout period is reached.

## Wait commands

Wait commands are overloaded jQuery methods.  Just like jQuery methods are both setters and 
getters, depending on how many arguments are passed, FuncUnit wait commands are either waits or 
getters depending on arguments.

### Single argument waits

Most wait commands only require a single argument.

<code>wait( checkVal, [timeout], [callback], [message] )</code>

- [FuncUnit.prototype.size size]
- [FuncUnit.prototype.html html]
- [FuncUnit.prototype.text text]
- [FuncUnit.prototype.val val]
- [FuncUnit.prototype.offset offset]
- [FuncUnit.prototype.position position]
- [FuncUnit.prototype.scrollTop scrollTop]
- [FuncUnit.prototype.scrollLeft scrollLeft]
- [FuncUnit.prototype.height height]
- [FuncUnit.prototype.width width]
- [FuncUnit.prototype.innerWidth innerWidth]
- [FuncUnit.prototype.innerHeight innerHeight]
- [FuncUnit.prototype.outerWidth outerWidth]
- [FuncUnit.prototype.outerHeight outerHeight]

@codestart
// wait until there are 5 .foo elements
S(".foo").size(5)

// wait until .container has 500px height
S(".container").height(500)

// wait until .banner's text is "Done"
S(".banner").text("Done")
@codeend

### Two argument waits

Some jQuery methods accept 2 parameters.  Similarly, those wait methods accept two arguments.

<code>wait( keyVal, checkVal, [timeout], [callback], [message] )</code>

- [FuncUnit.prototype.data data]
- [FuncUnit.prototype.attr attr]
- [FuncUnit.prototype.hasClass hasClass]
- [FuncUnit.prototype.css css]

@codestart
// wait until $.data for .foo on the key "count" has 2
S(".foo").data("count", 2)

// wait until .contact has class "bar"
S(".contact").hasClass("bar", true)

// wait until .container has font-color red
S(".container").css("font-color", "red")
@codeend

### Zero argument waits

Several wait methods check for existence/visibility of elements. These require zero parameters.

<code>wait( [timeout], [callback], [message] )</code>

- [FuncUnit.prototype.exists exists]
- [FuncUnit.prototype.missing missing]
- [FuncUnit.prototype.visible visibile]
- [FuncUnit.prototype.invisible invisible]

@codestart
// wait until the .foo element is removed
S(".foo").missing();
@codeend

## Tester function

Instead of checking for a simple static value, tests can provide their own tester method. The tester 
method's arguments are whatever the wait normally accepts.  It returns true when the condition is met, 
after which the next wait or action begins.

@codestart
var initialWidth = S("#sliderMenu").width();

// wait until width is at least 200px
S("#sliderMenu").width(function( width ) {
  return width >= 200;
})
@codeend  

The tester method will be called repeatedly until it returns true, or the timeout is reached.

## Timeout

By default, wait commands will wait a 10s timeout period.  If the condition isn't true after that time, 
the test will fail.  

You can provide your own (optional) timeout for each wait condition as the parameter after 
the wait condition.  For example, the following will check if "#trigger" contains "I was triggered" for 
5 seconds before failing the test.

@codestart
("#trigger").text("I was triggered", 5000)
@codeend

The [FuncUnit.timeout] property is a global timeout value.  Its value sets the default timeout if a value isn't 
provided.

## Callback

Another optional parameter for each wait command is a callback method, which runs after the wait completes.

Inside a callback is the place to get information about a page and perform assertions. Callbacks are 
also useful for debugging.

@codestart
S(".foo").text("bar", function(){
  // runs after wait condition completes
})
@codeend

## Message

The last (optional) parameter for each wait command is a message.  By default, wait conditions will pass silently (without a passed assertion).  
If a user provides a message string, the wait condition will pass an assertion with the given message when the wait completes.  If the wait fails 
(the timeout is reached), this message will be provided to the failed assertion.

@codestart
S(".foo").text("bar", "the foo element has text bar")
@codeend
