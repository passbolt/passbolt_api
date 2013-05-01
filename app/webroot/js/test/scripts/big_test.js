/**
 * This test loads every test in JavaScriptMVC
 */
load('test/scripts/getting_started_test.js')

print("==========================  generators =============================")
load('steal/generate/test/run.js');


print("==========================  compression ============================")
load('steal/build/test/run.js');
load('jquery/view/test/compression/run.js');


print("========================== unit/functional ============================")
load('steal/rhino/rhino.js');
load('funcunit/loader.js');
FuncUnit.load('test.html');

