steal("jquery/dom/dimensions",
	'jquery/view/micro',
	'funcunit/qunit').then(function () {

	module("jquery/dom/dimensions");

	test("outerHeight and width", function () {
		$("#qunit-test-area").html("//jquery/dom/dimensions/styles.micro", {});
		var div = $("#qunit-test-area div"),
			baseHeight = div.height();
		equals(div.outerHeight(), baseHeight + 4, 'outerHeight() is adding border width');
		equals(div.outerHeight(true), baseHeight + 4 + 10, 'outerHeight(true) is adding border width and margins');
		div.outerHeight(50, true);
		equals(div.height(), 50 - 4 - 10, 'Div height set as expected');
	});

	test("animate", function () {
		$("#qunit-test-area").html("//jquery/dom/dimensions/styles.micro", {});
		var div = $("#qunit-test-area div");
		stop();
		div.animate({ outerHeight : 50 }, 100, function() {
			div.outerHeight(50, true);
			equals(div.height(), 50 - 4 - 10, 'Div height animated as expected');
			start();
		});
	});

});