steal('can/util/object', './isplain/isplain_test.js', 'steal-qunit', function () {
	QUnit.module('can/util/object');
	test('same', function () {
		ok(can.Object.same({
			type: 'FOLDER'
		}, {
			type: 'FOLDER',
			count: 5
		}, {
			count: null
		}), 'count ignored');
		ok(can.Object.same({
			type: 'folder'
		}, {
			type: 'FOLDER'
		}, {
			type: 'i'
		}), 'folder case ignored');

		// Issue #773
		ok(!can.Object.same(
			{foo: null},
			{foo: new Date()}
		), 'nulls and Dates are not considered the same. (#773)');

		ok(!can.Object.same(
			{foo: null},
			{foo: {}}
		), 'nulls and empty objects are not considered the same. (#773)');
	});
	test('subsets', function () {
		var res1 = can.Object.subsets({
			parentId: 5,
			type: 'files'
		}, [{
			parentId: 6
		}, {
			type: 'folders'
		}, {
			type: 'files'
		}]);
		deepEqual(res1, [{
			type: 'files'
		}]);
		var res2 = can.Object.subsets({
			parentId: 5,
			type: 'files'
		}, [{}, {
			type: 'folders'
		}, {
			type: 'files'
		}]);
		deepEqual(res2, [{}, {
			type: 'files'
		}]);
		var res3 = can.Object.subsets({
			parentId: 5,
			type: 'folders'
		}, [{
			parentId: 5
		}, {
			type: 'files'
		}]);
		deepEqual(res3, [{
			parentId: 5
		}]);
	});
	test('subset compare', function () {
		ok(can.Object.subset({
			type: 'FOLDER'
		}, {
			type: 'FOLDER'
		}), 'equal sets');
		ok(can.Object.subset({
			type: 'FOLDER',
			parentId: 5
		}, {
			type: 'FOLDER'
		}), 'sub set');
		ok(!can.Object.subset({
			type: 'FOLDER'
		}, {
			type: 'FOLDER',
			parentId: 5
		}), 'wrong way');
		ok(!can.Object.subset({
			type: 'FOLDER',
			parentId: 7
		}, {
			type: 'FOLDER',
			parentId: 5
		}), 'different values');
		ok(can.Object.subset({
			type: 'FOLDER',
			count: 5
		}, {
			type: 'FOLDER'
		}, {
			count: null
		}), 'count ignored');
		ok(can.Object.subset({
			type: 'FOLDER',
			kind: 'tree'
		}, {
			type: 'FOLDER',
			foo: true,
			bar: true
		}, {
			foo: null,
			bar: null
		}), 'understands a subset');
		ok(can.Object.subset({
			type: 'FOLDER',
			foo: true,
			bar: true
		}, {
			type: 'FOLDER',
			kind: 'tree'
		}, {
			foo: null,
			bar: null,
			kind: null
		}), 'ignores nulls');
	});
	test('searchText', function () {
		var item = {
			id: 1,
			name: 'thinger'
		}, searchText = {
				searchText: 'foo'
			}, compare = {
				searchText: function (items, paramsText, itemr, params) {
					equal(item, itemr);
					equal(searchText, params);
					return true;
				}
			};
		ok(can.Object.subset(item, searchText, compare), 'searchText');
	});
});
