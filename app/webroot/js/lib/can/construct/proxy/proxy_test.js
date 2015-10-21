steal("can/construct/proxy", "can/control", "steal-qunit", function () {
	/* global Car */
	var isSteal = typeof steal !== 'undefined';
	QUnit.module('can/construct/proxy');
	test('static proxy if control is loaded first', function () {
		var curVal = 0;
		expect(2);
		can.Control('Car', {
			show: function (value) {
				equal(curVal, value);
			}
		}, {});
		var cb = Car.proxy('show');
		curVal = 1;
		cb(1);
		curVal = 2;
		var cb2 = Car.proxy('show', 2);
		cb2();
	});
	test('proxy', function () {
		var curVal = 0;
		expect(2);
		can.Construct('Car', {
			show: function (value) {
				equal(curVal, value);
			}
		}, {});
		var cb = Car.proxy('show');
		curVal = 1;
		cb(1);
		curVal = 2;
		var cb2 = Car.proxy('show', 2);
		cb2();
	});
	// this won't work in dist mode (this functionality is removed)
	if (isSteal) {
		test('proxy error', 1, function () {
			can.Construct('Car', {});
			try {
				Car.proxy('huh');
				ok(false, 'I should have errored');
			} catch (e) {
				ok(true, 'Error was thrown');
			}
		});
	}
});
