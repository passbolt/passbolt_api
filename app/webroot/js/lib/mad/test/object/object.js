module("MadSquirrel", {
    // runs before each test
    setup: function(){
    },
    // runs after each test
    teardown: function(){
    }
});


test('Object mapping : map simple object to another', function(){
	var object = {
		'key1':'value1',
		'key2':'value2',
		'key3':'value3'
	}
	var map = {
		'key1':'key2',
		'key2':'key3',
		'key3':'key1'
	};
	var mappedObject = mapObject(object, map);
	ok(
		mappedObject['key1']=='value2' && mappedObject['key2']=='value3' && mappedObject['key3']=='value1',
		'The object has well been mapped'
	);
});

test('Object mapping : map object to another with sub targets', function(){
	var object = {
		'key1':'value1',
		'key2':'value2',
		'key3':'value3'
	}
	var map = {
		'key1.sub1':'key2',
		'key2.sub2.sub21':'key3',
		'key3.sub3.sub31.sub32':'key1'
	};
	var mappedObject = mapObject(object, map);
	ok(
		mappedObject.key1.sub1=='value2' && mappedObject.key2.sub2.sub21=='value3' && mappedObject.key3.sub3.sub31.sub32=='value1',
		'The object has well been mapped'
	);
});

test('Object mapping : map object to another with sub targets and transformation function', function(){
	var object = {
		'key1':'value1',
		'key2':'value2',
		'key3':'value3'
	}
	var map = {
		'key1.sub1':'key2',
		'key2.sub2.sub21':'key3',
		'key3.sub3.sub31.sub32':{
			'key':'key1',
			func:function(value){ return value+' changed'}
		}
	};
	var mappedObject = mapObject(object, map);
	ok(
		mappedObject.key1.sub1=='value2' && mappedObject.key2.sub2.sub21=='value3' && mappedObject.key3.sub3.sub31.sub32=='value1 changed',
		'The object has well been mapped'
	);
});