steal('can/test', 'steal-qunit', function () {
	QUnit.module('can/util/array/each');

	// The following test is from jQuery’s solution to this bug:
	// https://github.com/jquery/jquery/pull/2185
	test('iOS 8 64-bit JIT object length bug', function () {
		expect(4);

		var i;
		for (i = 0; i < 1000; i++) {
			can.each([]);
		}

		i = 0;
		can.each({1: '1', 2: '2', 3: '3'}, function (index) {
			equal(++i, index, 'Iterate over object');
		});
		equal(i, 3, 'Last index should be the length of the array');
	});

});