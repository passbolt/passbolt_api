(function () {

module("can/control/route",{
	setup : function(){
		stop();
		window.location.hash = "";
		setTimeout(function(){
			start();
		},13)
	}
});

test("routes changed", function () {
	expect(3);

	//setup controller
	can.Control("Router", {
		"foo/:bar route" : function () {
			ok(true, 'route updated to foo/:bar')
		},

		"foos route" : function () {
			ok(true, 'route updated to foos');
		},

		"route" : function () {
			ok(true, 'route updated to empty')
		}
	});

	// init controller
	var router = new Router(document.body);

	can.trigger(window, 'hashchange');

	window.location.hash = '!foo/bar';
	can.trigger(window, 'hashchange');

	window.location.hash = '!foos';
	can.trigger(window, 'hashchange');
	router.destroy();

});

test("route pointers", function(){
	expect(1);
	var Tester = can.Control({
		"lol/:wat route" : "meth",
		meth : function(){
			ok(true, "method pointer called")
		}
	});
	var tester = new Tester(document.body);
	window.location.hash = '!lol/wat';
	can.trigger(window, 'hashchange');
	tester.destroy();
})


})();
