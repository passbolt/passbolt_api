steal('can/map', 'can/list', 'can/test/benchmarks.js', function (Map, List, benchmarks) {
	var objects, map;
	benchmarks.add('Adding a big array to an object', function () {
		objects = [];
		for (var i = 0; i < 10; i++) {
			objects.push({
				prop: 'prop',
				nest: {
					prop: 'prop',
					nest: {
						prop: 'prop'
					}
				}
			});
		}
	}, function () {
		map = new can.Map();
		map.attr('obj', objects);
	});

	var NumbersMap;
	benchmarks.add('Overwriting defaults', function () {
		NumbersMap = can.Map.extend({
			numbers: [1, 2, 3, 4, 5, 6],
			foo: 'string',
			bar: {},
			zed: false
		});
	}, function () {
		new NumbersMap();
		new NumbersMap({
			numbers: ['a', 'b', 'c', 'd']
		});
		new NumbersMap({
			foo: 'blah blah blah'
		});
	});
});
