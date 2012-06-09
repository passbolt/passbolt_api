@page funcunit.envjs EnvJS
@parent funcunit.integrations 1

[http://www.envjs.com/ EnvJS] is a simulated browser environment. It can be used for running FuncUnit  
unit tests. EnvJS can't be used for functional tests because it doesn't accurately implement event 
simulation.

## Use

There is no installation step for EnvJS. It comes with JavaScriptMVC.

1. Write a unit test
1. Run the test

@codestart
test("unit test", function(){
  var a = new Animal();
  a.setAge(2);
  equals(a.getAge(), 2, "age works");
})
@codeend

@codestart
./js funcunit/run envjs path/to/qunit.html
@codeend

The same reporting system that is described in [funcunit.integrations Integrations] works with EnvJS.