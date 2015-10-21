steal('can/util/function', 'can/test', 'steal-qunit', function () {
	QUnit.module('can/util/function');

	var ctx1 = { name: 'David' };
	var ctx2 = { name: 'Justin' };

	test("can.debounce uses the correct context (#782)", function() {
		var debouncer = can.debounce(function(callback) {
			callback(this);
		}, 0);

		stop();

		debouncer.call(ctx1, function(ctx) {
			equal(ctx.name, 'David', 'Got correct context');
			debouncer.call(ctx2, function(ctx) {
				equal(ctx.name, 'Justin', 'Got correct context');
				start();
			});
		});
	});

	test("can.throttle uses the correct context", function() {
		var throttler = can.throttle(function(callback) {
			callback(this);
		}, 0);

		stop();

		throttler.call(ctx1, function(ctx) {
			equal(ctx.name, 'David', 'Got correct context');
			setTimeout(function() {
				throttler.call(ctx2, function(ctx) {
					equal(ctx.name, 'Justin', 'Got correct context');
					start();
				});
			}, 20);
		});
	});
});
