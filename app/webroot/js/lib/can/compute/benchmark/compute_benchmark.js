steal('can/compute',
	'can/compute/proto_compute.js',
	'steal-benchmark',
	'can/map',

function (can, Compute, b) {
	/* jshint ignore:start */
	
	
	b.suite("can/compute")
	
	.add("compute that reads two props",
		function () {
			can.batch.start();
			num++;
			person.attr({
				first: "Bob"+num,
				last: "Marley"+num
			});
        	can.batch.stop();
		},{
			setup: function(){
				var person = new can.Map({
					first: 'Bob',
					last: 'Marley'
				});
				var c = new can.Compute(function() {
					return person.attr('first') + person.attr('last');
				});
				c.bind('change', function() {});
				var num = 0;
			}
		});

	/*
	module('can.compute');
	test('create/bind/read', function() {
		expect(0);

		benchmarks.add(
		"can.compute create/bind/read",
		function () {},
		function () {
			var c = can.compute(0);
			c.bind('change', function() {});
			c(1);
			c();
		},
		function () {});
	});

	test('create/bind/read with multiple attributes', function() {
		expect(0);

		benchmarks.add(
		"can.compute create/bind/read with multiple attributes",
		function () {
			var person = new can.Map({
				first: 'Bob',
				last: 'Marley'
			});
		},
		function () {
			var c = can.compute(function() {
				return person.attr('first') + person.attr('last');
			});
			c.bind('change', function() {});
			c();
		},
		function () {});
	});

	module('can.Compute');
	test('create/bind/read', function() {
		expect(0);

		benchmarks.add(
		"can.Compute create/bind/read",
		function () {},
		function () {
			var c = new can.Compute(0);
			c.bind('change', function() {});

			c.set(1);
			c.get();
		},
		function () {});
	});

	test('create/bind/read with multiple attributes', function() {
		expect(0);

		benchmarks.add(
		"can.Compute create/bind/read with multiple attributes",
		function () {
			var person = new can.Map({
				first: 'Bob',
				last: 'Marley'
			});
		},
		function () {
			var c = new can.Compute(function() {
				return person.attr('first') + person.attr('last');
			});
			c.bind('change', function() {});
			c.get();
		},
		function () {});
	});*/
	/* jshint ignore:end */
});
