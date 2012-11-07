steal('funcunit/qunit')
	.then('funcunit')
	.then('./app.js', function(){
		module("Adapters")
		test("QUnit adapter test", function(){
			S('.clickme').click();
			S('.clickresult').text("clicked", "clicked the link")
		})
	})