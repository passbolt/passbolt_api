steal('funcunit', function () {

	module("mad.string", {
		// runs before each test
		setup: function () {},
		// runs after each test
		teardown: function () {}
	});

	test('mad.string.uuid : The function generates unique id for 1,000.000 of tests', function () {

		var uuids = []
		assertResult = true;
		for (var i = 0; i < 1000000; i++) {
			var id = uuid();
			if (uuids[id]) {
				assertResult = false;
				break;
			}
			uuids[id] = true;
		}
		ok(assertResult, 'uuid generates unique id for 1.000.000 of tests');
	});

});