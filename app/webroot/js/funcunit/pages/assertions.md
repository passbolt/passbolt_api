@page funcunit.getters Getters & Assertions
@parent FuncUnit 4

Getter commands are jQuery getter methods. At certain points throughout a test, its necessary 
to gather information about the page and perform assertions.  Unlike FuncUnit API methods, getters  
work synchronously.

## Getter methods

Inside FuncUnit callbacks, the S method returns a jQuery collection.  You can perform any jQuery getter method 
on it, like [http://api.jquery.com/text/ text], [http://api.jquery.com/val/ val], 
[http://api.jquery.com/width/ width], [http://api.jquery.com/size/ size], etc. Any jQuery method can be used 
as a getter.

@codestart
S(".foo").visible(function(){
  // perform getters inside wait callbacks
  var size = S(".bar").size();
})
@codeend

## Assertions

To perform assertions, use QUnit assertion methods.

- [http://docs.jquery.com/QUnit/ok#statemessage ok]
- [http://docs.jquery.com/QUnit/equal#actualexpectedmessage equal]
- [http://docs.jquery.com/QUnit/notEqual#actualexpectedmessage notEqual]
- [http://docs.jquery.com/QUnit/raises#blockexpectedmessage raises]
- [http://docs.jquery.com/QUnit/deepEqual#actualexpectedmessage deepEqual]

@codestart
S(".foo").visible(function(){
  // perform assertions inside wait callbacks
  var size = S(".bar").size();
  ok(size, 5, "there are 5 bars);
})
@codeend

## Tests without assertions

Its worth noting that its possible to write entire tests without using any getters or assertions. 
Any wait condition will fail an assertion if they reach the timeout before succeeding. 

Therefore, entire tests can be written with just action and wait methods. If the tests succeeds, it will finish 
without any assertions.  If it fails, it will throw a failed assertion.

@codestart
test("test with waits", function(){
  S(".foo").click();
  // if this wait condition never becomes true, test will fail
  S(".bar").visible();
})
@codeend

Often, writing a test with assertions adds more unnecessary code.

@codestart
test("with assertions", function(){
  S(".expander").click()
    .width(500, function(){
      // if the wait condition isn't met, this assertion won't run
      // therefore, this assertion isn't necessary
      ok(true, "expander is 500px");
    })
})
@codeend
