//we probably have to have this only describing where the tests are
steal("jquery")
 .then("funcunit/syn")  //load your app
 .then('funcunit/qunit')  //load qunit
 .then(
 	"./syn_test.js", 
 	"./mouse_test.js", 
	"./key_test.js"
)