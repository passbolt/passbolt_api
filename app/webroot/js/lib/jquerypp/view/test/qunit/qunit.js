//we probably have to have this only describing where the tests are
steal("jquery/view",
	"can/view/micro",
	"can/view/ejs",
	"can/view/jaml",
	"can/view/tmpl")  //load your app
 .then('funcunit/qunit')  //load qunit
 .then("./view_test.js");