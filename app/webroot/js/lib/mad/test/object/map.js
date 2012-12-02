steal('funcunit', function () {

	module("mad.object", {
		// runs before each test
		setup: function () {},
		// runs after each test
		teardown: function () {}
	});

	test('mapObject : map simple object to another', function () {
		var object = {
			'key1': 'value1',
			'key2': 'value2',
			'key3': 'value3'
		};
		var map = new mad.object.Map({
			'key1': 'key2',
			'key2': 'key3',
			'key3': 'key1'
		});
		var mappedObject = map.mapObject(object);
		ok(mappedObject.key1 == 'value2' && mappedObject.key2 == 'value3' && mappedObject.key3 == 'value1', 'The object has well been mapped');
	});

	test('mapObject : map object to another with sub targets', function () {
		var object = {
			'key1': 'value1',
			'key2': 'value2',
			'key3': 'value3'
		};
		var map = new mad.object.Map({
			'key1.sub1': 'key2',
			'key2.sub2.sub21': 'key3',
			'key3.sub3.sub31.sub32': 'key1'
		});
		var mappedObject = map.mapObject(object);
		ok(mappedObject.key1.sub1 == 'value2' && mappedObject.key2.sub2.sub21 == 'value3' && mappedObject.key3.sub3.sub31.sub32 == 'value1', 'The object has well been mapped');
	});

	test('mapObject : map object to another with sub targets and transformation function', function () {
		var object = {
			'key1': 'value1',
			'key2': 'value2',
			'key3': 'value3'
		};
		var map = new mad.object.Map({
			'key1.sub1': 'key2',
			'key2.sub2.sub21': 'key3',
			'key3.sub3.sub31.sub32': {
				'key': 'key1',
				func: function (value) {
					return value + ' changed';
				}
			}
		});
		var mappedObject = map.mapObject(object);
		ok(mappedObject.key1.sub1 == 'value2' && mappedObject.key2.sub2.sub21 == 'value3' && mappedObject.key3.sub3.sub31.sub32 == 'value1 changed', 'The object has well been mapped');
	});

	test('mapObjects : map array of simple objects to another', function () {
		var object = {
			'key1': 'value1',
			'key2': 'value2',
			'key3': 'value3'
		};
		var map = new mad.object.Map({
			'key1': 'key2',
			'key2': 'key3',
			'key3': 'key1'
		});

		//		raises(function () {
		//			var mappedObjects = map.mapObjects(object);
		//		}, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
		var arr = [object, object, object];
		var mappedObjects = map.mapObjects(arr);
		var mappingAssert = true;
		ok(mappedObjects instanceof Array, 'The Object.mapObjects function return an array as expected');
		equal(mappedObjects.length, arr.length, 'The array of mapped objects contains as many entries as the array of original objects');

		for (var i in mappedObjects) {
			var mappedObject = mappedObjects[i];
			mappingAssert &= mappedObject['key1'] == 'value2' && mappedObject['key2'] == 'value3' && mappedObject['key3'] == 'value1';
		}
		ok(mappingAssert, 'The array of objects has well been mapped');
	});

});