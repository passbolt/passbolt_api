import "test/bootstrap";

describe("mad.Map", function(){

	it("should inherit can.Construct", function() {
		var map = new mad.Map();
		expect(map).to.be.instanceOf(can.Construct);
	});

	it("mapObject() should map simple objects", function() {
		var object = {
			'key1': 'value1',
			'key2': 'value2',
			'key3': 'value3'
		};
		var map = new mad.Map({
			'key1': 'key2',
			'key2': 'key3',
			'key3': 'key1'
		});
		var mappedObject = map.mapObject(object);
		expect(mappedObject.key1).to.be.equal('value2');
		expect(mappedObject.key2).to.be.equal('value3');
		expect(mappedObject.key3).to.be.equal('value1');
	});

	it("mapObject() should map nested keys", function() {
		var object = {
			'key1': 'value1',
			'key2': 'value2',
			'key3': 'value3'
		};
		var map = new mad.Map({
			'key1.sub1': 'key2',
			'key2.sub2.sub21': 'key3',
			'key3.sub3.sub31.sub32': 'key1'
		});
		var mappedObject = map.mapObject(object);
		expect(mappedObject.key1.sub1).to.be.equal('value2');
		expect(mappedObject.key2.sub2.sub21).to.be.equal('value3');
		expect(mappedObject.key3.sub3.sub31.sub32).to.be.equal('value1');
	});

	it("mapObject() should map nested keys and should be able to use transformation functions", function() {
		var object = {
			'key1': 'value1',
			'key2': 'value2',
			'key3': 'value3'
		};
		var map = new mad.Map({
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
		expect(mappedObject.key1.sub1).to.be.equal('value2');
		expect(mappedObject.key2.sub2.sub21).to.be.equal('value3');
		expect(mappedObject.key3.sub3.sub31.sub32).to.be.equal('value1 changed');
	});

	it("mapObject() should map array of simple objects", function() {
		var object = {
			'key1': 'value1',
			'key2': 'value2',
			'key3': 'value3'
		};
		var map = new mad.Map({
			'key1': 'key2',
			'key2': 'key3',
			'key3': 'key1'
		});
		var arr = [object, object, object];
		var mappedObjects = map.mapObjects(arr);
		expect(mappedObjects).to.be.array;
		expect(mappedObjects.length).not.to.be.equal(0);

		for (var i in mappedObjects) {
			var mappedObject = mappedObjects[i];
			expect(mappedObject.key1).to.be.equal('value2');
			expect(mappedObject.key2).to.be.equal('value3');
			expect(mappedObject.key3).to.be.equal('value1');
		}
	});

});
