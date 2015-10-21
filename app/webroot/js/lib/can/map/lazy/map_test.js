/* jshint asi:true*/
steal("can/map/lazy", "can/compute", "can/test", "steal-qunit", function (undefined) {

	QUnit.module('can/map/lazy')

	test("Basic Map", 4, function () {

		var state = new can.LazyMap({
			category: 5,
			productType: 4
		});

		state.bind("change", function (ev, attr, how, val, old) {
			equal(attr, "category", "correct change name")
			equal(how, "set")
			equal(val, 6, "correct")
			equal(old, 5, "correct")
		});

		state.attr("category", 6);

		state.unbind("change");

	});

	test("Nested Map", 5, function () {
		var me = new can.LazyMap({
			name: {
				first: "Justin",
				last: "Meyer"
			}
		});

		ok(me.attr("name") instanceof can.LazyMap);

		me.bind("change", function (ev, attr, how, val, old) {
			equal(attr, "name.first", "correct change name")
			equal(how, "set")
			equal(val, "Brian", "correct")
			equal(old, "Justin", "correct")
		})

		me.attr("name.first", "Brian");

		me.unbind("change")

	})

	test("remove attr", function () {
		var state = new can.LazyMap({
			category: 5,
			productType: 4
		});
		state.removeAttr("category");
		deepEqual(can.LazyMap.keys(state), ["productType"], "one property");
	});

	test("nested event handlers are not run by changing the parent property (#280)", function () {

		var person = new can.LazyMap({
			name: {
				first: "Justin"
			}
		})
		person.bind("name.first", function (ev, newName) {
			ok(false, "name.first should never be called")
			//equal(newName, "hank", "name.first handler called back with correct new name")
		});
		person.bind("name", function () {
			ok(true, "name event triggered")
		})

		person.attr("name", {
			first: "Hank"
		});

	});

	test("cyclical objects (#521)", 0, function () {
		// Not supported by LazyMap
		/*
		var foo = {};
		foo.foo = foo;

		var fooed = new can.LazyMap(foo);

		ok(true, "did not cause infinite recursion");

		ok(fooed.attr('foo') === fooed, "map points to itself")

		var me = {
			name: "Justin"
		}
		var references = {
			husband: me,
			friend: me
		}
		var ref = new can.LazyMap(references)

		ok(ref.attr('husband') === ref.attr('friend'), "multiple properties point to the same thing")
		*/
	})

	test('Getting attribute that is a can.compute should return the compute and not the value of the compute (#530)', function () {
		var compute = can.compute('before');
		var map = new can.LazyMap({
			time: compute
		});

		equal(map.time, compute, 'dot notation call of time is compute');
		equal(map.attr('time'), compute, '.attr() call of time is compute');
	})

	test('_cid add to original object', function () {
		var map = new can.LazyMap(),
			obj = {
				'name': 'thecountofzero'
			};

		map.attr('myObj', obj);
		ok(!obj._cid, '_cid not added to original object');
	})

	test("can.each used with maps", function () {
		can.each(new can.LazyMap({
			foo: "bar"
		}), function (val, attr) {

			if (attr === "foo") {
				equal(val, "bar")
			} else {
				ok(false, "no properties other should be called " + attr)
			}

		})
	})

	test("can.Map serialize triggers reading (#626)", function () {
		var old = can.__observe;

		var attributesRead = [];
		var readingTriggeredForKeys = false;

		can.__observe = function (object, attribute) {
			if (attribute === "__keys") {
				readingTriggeredForKeys = true;
			} else {
				attributesRead.push(attribute);
			}
		};

		var testMap = new can.LazyMap({
			cats: "meow",
			dogs: "bark"
		});

		// We need the original serialize since it can possibly be monkey patched
		testMap.serialize = can.LazyMap.prototype.serialize;
		testMap.serialize();


		ok( can.inArray("cats", attributesRead ) !== -1 && can.inArray( "dogs", attributesRead ) !== -1,  "map serialization triggered __reading on all attributes");
		
		ok(readingTriggeredForKeys, "map serialization triggered __reading for __keys");

		can.__observe = old;
	});

	test("Test top level attributes", 7, function () {
		var test = new can.LazyMap({
			'my.enable': false,
			'my.item': true,
			'my.count': 0,
			'my.newCount': 1,
			'my': {
				'value': true,
				'nested': {
					'value': 100
				}
			}
		});

		equal(test.attr('my.value'), true, 'correct');
		equal(test.attr('my.nested.value'), 100, 'correct');
		ok(test.attr("my.nested") instanceof can.LazyMap);

		equal(test.attr('my.enable'), false, 'falsey (false) value accessed correctly');
		equal(test.attr('my.item'), true, 'truthey (true) value accessed correctly');
		equal(test.attr('my.count'), 0, 'falsey (0) value accessed correctly');
		equal(test.attr('my.newCount'), 1, 'falsey (1) value accessed correctly');
	});

});
