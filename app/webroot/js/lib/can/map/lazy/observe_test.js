steal('can/util', 'can/observe', 'can/map/lazy', 'can/test', 'steal-qunit', function () {
	QUnit.module('can/map/lazy map+list');
	test('Basic Map', 9, function () {
		var state = new can.LazyMap({
			category: 5,
			productType: 4,
			properties: {
				brand: [],
				model: [],
				price: []
			}
		});
		var added;
		state.bind('change', function (ev, attr, how, val, old) {
			equal(attr, 'properties.brand.0', 'correct change name');
			equal(how, 'add');
			equal(val[0].attr('foo'), 'bar', 'correct');
			added = val[0];
		});
		state.attr('properties.brand')
			.push({
				foo: 'bar'
			});
		state.unbind('change');
		added.bind('change', function (ev, attr, how, val, old) {
			equal(attr, 'foo', 'foo property set on added');
			equal(how, 'set', 'added');
			equal(val, 'zoo', 'added');
		});
		state.bind('change', function (ev, attr, how, val, old) {
			equal(attr, 'properties.brand.0.foo');
			equal(how, 'set');
			equal(val, 'zoo');
		});
		added.attr('foo', 'zoo');
	});
	test('list attr changes length', function () {
		var l = new can.List([
			0,
			1,
			2
		]);
		l.attr(3, 3);
		equal(l.length, 4);
	});
	test('list splice', function () {
		var l = new can.List([
			0,
			1,
			2,
			3
		]),
			first = true;
		l.bind('change', function (ev, attr, how, newVals, oldVals) {
			equal(attr, '1');
			if (first) {
				equal(how, 'remove', 'removing items');
				equal(newVals, undefined, 'no new Vals');
			} else {
				deepEqual(newVals, [
					'a',
					'b'
				], 'got the right newVals');
				equal(how, 'add', 'adding items');
			}
			first = false;
		});
		l.splice(1, 2, 'a', 'b');
		deepEqual(l.serialize(), [
			0,
			'a',
			'b',
			3
		], 'serialized');
	});
	test('list pop', function () {
		var l = new can.List([
			0,
			1,
			2,
			3
		]);
		l.bind('change', function (ev, attr, how, newVals, oldVals) {
			equal(attr, '3');
			equal(how, 'remove');
			equal(newVals, undefined);
			deepEqual(oldVals, [3]);
		});
		l.pop();
		deepEqual(l.serialize(), [
			0,
			1,
			2
		]);
	});
	test('changing an object unbinds', 4, function () {
		var state = new can.LazyMap({
			category: 5,
			productType: 4,
			properties: {
				brand: [],
				model: [],
				price: []
			}
		}),
			count = 0;
		var brand = state.attr('properties.brand');
		state.bind('change', function (ev, attr, how, val, old) {
			equal(attr, 'properties.brand');
			equal(count, 0, 'count called once');
			count++;
			equal(how, 'set');
			equal(val[0], 'hi');
		});
		state.attr('properties.brand', ['hi']);
		brand.push(1, 2, 3);
	});
	test('replacing with an object that object becomes observable', function () {
		var state = new can.LazyMap({
			properties: {
				brand: [],
				model: [],
				price: []
			}
		});
		ok(state.attr('properties')
			.bind, 'has bind function');
		state.attr('properties', {});
		ok(state.attr('properties')
			.bind, 'has bind function');
	});
	test('attr does not blow away old observable', function () {
		var state = new can.LazyMap({
			properties: {
				brand: ['gain']
			}
		});
		var oldCid = state.attr('properties.brand')
			._cid;
		state.attr({
			properties: {
				brand: []
			}
		}, true);
		deepEqual(state.attr('properties.brand')
			._cid, oldCid, 'should be the same map, so that views bound to the old one get updates');
		equal(state.attr('properties.brand')
			.length, 0, 'list should be empty');
	});
	test('sub observes respect attr remove parameter', function () {
		var bindCalled = 0,
			state = new can.LazyMap({
				monkey: {
					tail: 'brain'
				}
			});
		state.bind('change', function (ev, attr, how, newVal, old) {
			bindCalled++;
			equal(attr, 'monkey.tail');
			equal(old, 'brain');
			equal(how, 'remove');
		});
		state.attr({
			monkey: {}
		});
		equal('brain', state.attr('monkey.tail'), 'should not remove attribute of sub map when remove param is false');
		equal(0, bindCalled, 'remove event not fired for sub map when remove param is false');
		state.attr({
			monkey: {}
		}, true);
		equal(undefined, state.attr('monkey.tail'), 'should remove attribute of sub map when remove param is false');
		equal(1, bindCalled, 'remove event fired for sub map when remove param is false');
	});
	test('remove attr', function () {
		var state = new can.LazyMap({
			properties: {
				brand: [],
				model: [],
				price: []
			}
		});
		state.bind('change', function (ev, attr, how, newVal, old) {
			equal(attr, 'properties');
			equal(how, 'remove');
			deepEqual(old.serialize(), {
				brand: [],
				model: [],
				price: []
			});
		});
		state.removeAttr('properties');
		equal(undefined, state.attr('properties'));
	});
	test('remove nested attr', function () {
		var state = new can.LazyMap({
			properties: {
				nested: true
			}
		});
		state.bind('change', function (ev, attr, how, newVal, old) {
			equal(attr, 'properties.nested');
			equal(how, 'remove');
			deepEqual(old, true);
		});
		state.removeAttr('properties.nested');
		equal(undefined, state.attr('properties.nested'));
	});
	test('remove item in nested array', function () {
		var state = new can.LazyMap({
			array: [
				'a',
				'b'
			]
		});
		state.bind('change', function (ev, attr, how, newVal, old) {
			equal(attr, 'array.1');
			equal(how, 'remove');
			deepEqual(old, ['b']);
		});
		state.removeAttr('array.1');
		equal(state.attr('array.length'), 1);
	});
	test('remove nested property in item of array', function () {
		var state = new can.LazyMap({
			array: [{
				nested: true
			}]
		});
		state.bind('change', function (ev, attr, how, newVal, old) {
			equal(attr, 'array.0.nested');
			equal(how, 'remove');
			deepEqual(old, true);
		});
		state.removeAttr('array.0.nested');
		equal(undefined, state.attr('array.0.nested'));
	});
	test('remove nested property in item of array map', function () {
		var state = new can.List([{
			nested: true
		}]);
		state.bind('change', function (ev, attr, how, newVal, old) {
			equal(attr, '0.nested');
			equal(how, 'remove');
			deepEqual(old, true);
		});
		state.removeAttr('0.nested');
		equal(undefined, state.attr('0.nested'));
	});
	test('attr with an object', function () {
		var state = new can.LazyMap({
			properties: {
				foo: 'bar',
				brand: []
			}
		});
		state.bind('change', function (ev, attr, how, newVal) {
			equal(attr, 'properties.foo', 'foo has changed');
			equal(newVal, 'bad');
		});
		state.attr({
			properties: {
				foo: 'bar',
				brand: []
			}
		});
		state.attr({
			properties: {
				foo: 'bad',
				brand: []
			}
		});
		state.unbind('change');
		state.bind('change', function (ev, attr, how, newVal) {
			equal(attr, 'properties.brand.0');
			equal(how, 'add');
			deepEqual(newVal, ['bad']);
		});
		state.attr({
			properties: {
				foo: 'bad',
				brand: ['bad']
			}
		});
	});
	test('empty get', function () {
		var state = new can.LazyMap({});
		equal(state.attr('foo.bar'), undefined);
	});
	test('attr deep array ', function () {
		var state = new can.LazyMap({});
		var arr = [{
			foo: 'bar'
		}],
			thing = {
				arr: arr
			};
		state.attr({
			thing: thing
		}, true);
		ok(thing.arr === arr, 'thing unmolested');
	});
	test('attr semi-serialize', function () {
		var first = {
			foo: {
				bar: 'car'
			},
			arr: [
				1,
				2,
				3, {
					four: '5'
				}
			]
		}, compare = {
				foo: {
					bar: 'car'
				},
				arr: [
					1,
					2,
					3, {
						four: '5'
					}
				]
			};
		var res = new can.LazyMap(first)
			.attr();
		deepEqual(res, compare, 'test');
	});
	test('attr sends events after it is done', function () {
		var state = new can.LazyMap({
			foo: 1,
			bar: 2
		});
		state.bind('change', function () {
			equal(state.attr('foo'), -1, 'foo set');
			equal(state.attr('bar'), -2, 'bar set');
		});
		state.attr({
			foo: -1,
			bar: -2
		});
	});
	test('direct property access', function () {
		var state = new can.LazyMap({
			foo: 1,
			attr: 2
		});
		equal(state.foo, 1);
		equal(typeof state.attr, 'function');
	});
	test('pop unbinds', function () {
		var l = new can.List([{
			foo: 'bar'
		}]);
		var o = l.attr(0),
			count = 0;
		l.bind('change', function (ev, attr, how, newVal, oldVal) {
			count++;
			if (count === 1) {
				equal(attr, '0.foo', 'count is set');
			} else if (count === 2) {
				equal(how, 'remove', 'remove event called');
				equal(attr, '0', 'remove event called with correct index');
			} else {
				ok(false, 'change handler called too many times');
			}
		});
		equal(o.attr('foo'), 'bar');
		o.attr('foo', 'car');
		l.pop();
		o.attr('foo', 'bad');
	});
	test('splice unbinds', function () {
		var l = new can.List([{
			foo: 'bar'
		}]);
		var o = l.attr(0),
			count = 0;
		l.bind('change', function (ev, attr, how, newVal, oldVal) {
			count++;
			if (count === 1) {
				equal(attr, '0.foo', 'count is set');
			} else if (count === 2) {
				equal(how, 'remove');
				equal(attr, '0');
			} else {
				ok(false, 'called too many times');
			}
		});
		equal(o.attr('foo'), 'bar');
		o.attr('foo', 'car');
		l.splice(0, 1);
		o.attr('foo', 'bad');
	});
	test('always gets right attr even after moving array items', function () {
		var l = new can.List([{
			foo: 'bar'
		}]);
		var o = l.attr(0);
		l.unshift('A new Value');
		l.bind('change', function (ev, attr, how) {
			equal(attr, '1.foo');
		});
		o.attr('foo', 'led you');
	});
	test('recursive observers do not cause stack overflow', function () {
		expect(0);
		var a = new can.LazyMap();
		var b = new can.LazyMap({
			a: a
		});
		a.attr('b', b);
	});
	test('bind to specific attribute changes when an existing attribute\'s value is changed', function () {
		var paginate = new can.LazyMap({
			offset: 100,
			limit: 100,
			count: 2000
		});
		paginate.bind('offset', function (ev, newVal, oldVal) {
			equal(newVal, 200);
			equal(oldVal, 100);
		});
		paginate.attr('offset', 200);
	});
	test('bind to specific attribute changes when an attribute is removed', 2, function () {
		var paginate = new can.LazyMap({
			offset: 100,
			limit: 100,
			count: 2000
		});
		paginate.bind('offset', function (ev, newVal, oldVal) {
			equal(newVal, undefined);
			equal(oldVal, 100);
		});
		paginate.removeAttr('offset');
	});
	test('Array accessor methods', 11, function () {
		var l = new can.List([
			'a',
			'b',
			'c'
		]),
			sliced = l.slice(2),
			joined = l.join(' | '),
			concatenated = l.concat([
				2,
				1
			], new can.List([0]));
		ok(sliced instanceof can.List, 'Slice is an Observable list');
		equal(sliced.length, 1, 'Sliced off two elements');
		equal(sliced[0], 'c', 'Single element as expected');
		equal(joined, 'a | b | c', 'Joined list properly');
		ok(concatenated instanceof can.List, 'Concatenated is an Observable list');
		deepEqual(concatenated.serialize(), [
			'a',
			'b',
			'c',
			2,
			1,
			0
		], 'List concatenated properly');
		l.forEach(function (letter, index) {
			ok(true, 'Iteration');
			if (index === 0) {
				equal(letter, 'a', 'First letter right');
			}
			if (index === 2) {
				equal(letter, 'c', 'Last letter right');
			}
		});
	});
	test('instantiating can.List of correct type', function () {
		var Ob = can.LazyMap({
			getName: function () {
				return this.attr('name');
			}
		});
		var list = new Ob.List([{
			name: 'Tester'
		}]);
		equal(list.length, 1, 'List length is correct');
		ok(list[0] instanceof can.LazyMap, 'Initialized list item converted to can.LazyMap');
		ok(list[0] instanceof Ob, 'Initialized list item converted to Ob');
		equal(list[0].getName(), 'Tester', 'Converted to extended Map instance, could call getName()');
		list.push({
			name: 'Another test'
		});
		equal(list[1].getName(), 'Another test', 'Pushed item gets converted as well');
	});
	test('can.List.prototype.splice converts objects (#253)', function () {
		var Ob = can.LazyMap({
			getAge: function () {
				return this.attr('age') + 10;
			}
		});
		var list = new Ob.List([{
			name: 'Tester',
			age: 23
		}, {
			name: 'Tester 2',
			age: 44
		}]);
		equal(list[0].getAge(), 33, 'Converted age');
		list.splice(1, 1, {
			name: 'Spliced',
			age: 92
		});
		equal(list[1].getAge(), 102, 'Converted age of spliced');
	});
	test('removing an already missing attribute does not cause an event', function () {
		expect(0);
		var ob = new can.LazyMap();
		ob.bind('change', function () {
			ok(false);
		});
		ob.removeAttr('foo');
	});
	test('Only plain objects should be converted to Observes', function () {
		var ob = new can.LazyMap();
		ob.attr('date', new Date());
		ok(ob.attr('date') instanceof Date, 'Date should not be converted');
		var selected = can.$('body');
		ob.attr('sel', selected);
		if (can.isArray(selected)) {
			ok(ob.attr('sel') instanceof can.List, 'can.$() as array converted into List');
		} else {
			equal(ob.attr('sel'), selected, 'can.$() should not be converted');
		}
		ob.attr('element', document.getElementsByTagName('body')[0]);
		equal(ob.attr('element'), document.getElementsByTagName('body')[0], 'HTMLElement should not be converted');
		ob.attr('window', window);
		equal(ob.attr('window'), window, 'Window object should not be converted');
	});
	test('bind on deep properties', function () {
		expect(2);
		var ob = new can.LazyMap({
			name: {
				first: 'Brian'
			}
		});
		ob.bind('name.first', function (ev, newVal, oldVal) {
			equal(newVal, 'Justin');
			equal(oldVal, 'Brian');
		});
		ob.attr('name.first', 'Justin');
	});
	test('startBatch and stopBatch and changed event', 5, function () {
		var ob = new can.LazyMap({
			name: {
				first: 'Brian'
			},
			age: 29
		}),
			bothSet = false,
			changeCallCount = 0,
			changedCalled = false;
		ob.bind('change', function () {
			ok(bothSet, 'both properties are set before the changed event was called');
			ok(!changedCalled, 'changed not called yet');
			changeCallCount++;
		});
		stop();
		can.batch.start(function () {
			ok(true, 'batch callback called');
		});
		ob.attr('name.first', 'Justin');
		setTimeout(function () {
			ob.attr('age', 30);
			bothSet = true;
			can.batch.stop();
			start();
		}, 1);
	});
	test('startBatch callback', 4, function () {
		var ob = new can.LazyMap({
			game: {
				name: 'Legend of Zelda'
			},
			hearts: 15
		}),
			callbackCalled = false;
		ob.bind('change', function () {
			equal(callbackCalled, false, 'startBatch callback not called yet');
		});
		can.batch.start(function () {
			ok(true, 'startBatch callback called');
			callbackCalled = true;
		});
		ob.attr('hearts', 16);
		equal(callbackCalled, false, 'startBatch callback not called yet');
		can.batch.stop();
		equal(callbackCalled, true, 'startBatch callback called');
	});
	test('nested map attr', function () {
		var person1 = new can.LazyMap({
			name: {
				first: 'Josh'
			}
		}),
			person2 = new can.LazyMap({
				name: {
					first: 'Justin',
					last: 'Meyer'
				}
			}),
			count = 0;
		person1.bind('change', function (ev, attr, how, val, old) {
			equal(count, 0, 'change called once');
			count++;
			equal(attr, 'name');
			equal(val.attr('first'), 'Justin');
			equal(val.attr('last'), 'Meyer');
		});
		person1.attr('name', person2.attr('name'));
		person1.attr('name', person2.attr('name'));
	});
	test('Nested array conversion (#172)', 4, function () {
		var original = [
			[
				1,
				2
			],
			[
				3,
				4
			],
			[
				5,
				6
			]
		],
			list = new can.List(original);
		equal(list.length, 3, 'list length is correct');
		deepEqual(list.serialize(), original, 'Lists are the same');
		list.unshift([
			10,
			11
		], [
			12,
			13
		]);
		ok(list[0] instanceof can.List, 'Unshifted array converted to map list');
		deepEqual(list.serialize(), [
			[
				10,
				11
			],
			[
				12,
				13
			]
		].concat(original), 'Arrays unshifted properly');
	});
	test('can.List.prototype.replace (#194)', 7, function () {
		var list = new can.List([
			'a',
			'b',
			'c'
		]),
			replaceList = [
				'd',
				'e',
				'f',
				'g'
			],
			dfd = new can.Deferred();
		list.bind('remove', function (ev, arr) {
			equal(arr.length, 3, 'Three elements removed');
		});
		list.bind('add', function (ev, arr) {
			equal(arr.length, 4, 'Four new elements added');
		});
		list.replace(replaceList);
		deepEqual(list.serialize(), replaceList, 'Lists are the same');
		list.unbind('remove');
		list.unbind('add');
		list.replace();
		equal(list.length, 0, 'List has been emptied');
		list.push('D');
		stop();
		list.replace(dfd);
		setTimeout(function () {
			var newList = [
				'x',
				'y'
			];
			list.bind('remove', function (ev, arr) {
				equal(arr.length, 1, 'One element removed');
			});
			list.bind('add', function (ev, arr) {
				equal(arr.length, 2, 'Two new elements added from Deferred');
			});
			dfd.resolve(newList);
			deepEqual(list.serialize(), newList, 'Lists are the same');
			start();
		}, 100);
	});
	test('replace with a deferred that resolves to an List', function () {
		var def = new can.Deferred();
		def.resolve(new can.List([{
			name: 'foo'
		}, {
			name: 'bar'
		}]));
		var list = new can.List([{
			name: '1'
		}, {
			name: '2'
		}]);
		list.bind('change', function () {
			equal(list.length, 2, 'length is still 2');
			equal(list[0].attr('name'), 'foo', 'set to foo');
		});
		list.replace(def);
	});
	test('.attr method doesn\'t merge nested objects (#207)', function () {
		var test = new can.LazyMap({
			a: {
				a1: 1,
				a2: 2
			},
			b: {
				b1: 1,
				b2: 2
			}
		});
		test.attr({
			a: {
				a2: 3
			},
			b: {
				b1: 3
			}
		});
		deepEqual(test.attr(), {
			'a': {
				'a1': 1,
				'a2': 3
			},
			'b': {
				'b1': 3,
				'b2': 2
			}
		}, 'Object merged as expected');
	});
	test('IE8 error on list setup with List (#226)', function () {
		var list = new can.List([
			'first',
			'second',
			'third'
		]),
			otherList = new can.List(list);
		deepEqual(list.attr(), otherList.attr(), 'Lists are the same');
	});
	test('initialize List with a deferred', function () {
		stop();
		var def = new can.Deferred();
		var list = new can.List(def);
		list.bind('add', function (ev, items, index) {
			deepEqual(items, [
				'a',
				'b'
			]);
			equal(index, 0);
			start();
		});
		setTimeout(function () {
			def.resolve([
				'a',
				'b'
			]);
		}, 10);
	});
	test('triggering a event while in a batch (#291)', function () {
		expect(0);
		stop();
		var map = new can.LazyMap();
		can.batch.start();
		can.trigger(map, 'change', 'random');
		setTimeout(function () {
			can.batch.stop();
			start();
		}, 10);
	});
	test('dot separated keys (#257, #296)', 0, function () {
		// Not supported by LazyMap
		/*
		var ob = new can.LazyMap({
			'test.value': 'testing',
			other: {
				test: 'value'
			}
		});
		equal(ob['test.value'], 'testing', 'Set value with dot separated key properly');
		equal(ob.attr('test.value'), 'testing', 'Could retrieve value with .attr');
		equal(ob.attr('other.test'), 'value', 'Still getting dot separated value');
		ob.attr({
			'other.bla': 'othervalue'
		});
		equal(ob['other.bla'], 'othervalue', 'Key is not split');
		equal(ob.attr('other.bla'), 'othervalue', 'Could retrieve value with .attr');
		ob.attr('other.stuff', 'thinger');
		equal(ob.attr('other.stuff'), 'thinger', 'Set dot separated value');
		deepEqual(ob.attr('other')
			.serialize(), {
				test: 'value',
				stuff: 'thinger'
			}, 'Object set properly');
		*/
	});
	test('cycle binding', function () {
		var first = new can.LazyMap(),
			second = new can.LazyMap();
		first.attr('second', second);
		second.attr('first', second);
		var handler = function () {};
		first.bind('change', handler);
		ok(first._bindings, 'has bindings');
		first.unbind('change', handler);
		ok(!first._bindings, 'bindings removed');
	});
	test('Deferreds are not converted', function () {
		var dfd = can.Deferred(),
			ob = new can.LazyMap({
				test: dfd
			});
		ok(can.isDeferred(ob.attr('test')), 'Attribute is a deferred');
		ok(!ob.attr('test')
			._cid, 'Does not have a _cid');
	});
	test('Setting property to undefined', function () {
		var ob = new can.LazyMap({
			'foo': 'bar'
		});
		ob.attr('foo', undefined);
		equal(ob.attr('foo'), undefined, 'foo has a value.');
	});
	test('removing list items containing computes', function () {
		var list = new can.List([{
			comp: can.compute(function () {
				return false;
			})
		}]);
		list.pop();
		equal(list.length, 0, 'list is empty');
	});
	QUnit.module('can/map/lazy compute');
	test('Basic Compute', function () {
		var o = new can.LazyMap({
			first: 'Justin',
			last: 'Meyer'
		});
		var prop = can.compute(function () {
			return o.attr('first') + ' ' + o.attr('last');
		});
		equal(prop(), 'Justin Meyer');
		var handler = function (ev, newVal, oldVal) {
			equal(newVal, 'Brian Meyer');
			equal(oldVal, 'Justin Meyer');
		};
		prop.bind('change', handler);
		o.attr('first', 'Brian');
		prop.unbind('change', handler);
		o.attr('first', 'Brian');
	});
	test('compute on prototype', function () {
		var Person = can.LazyMap({
			fullName: function () {
				return this.attr('first') + ' ' + this.attr('last');
			}
		});
		var me = new Person({
			first: 'Justin',
			last: 'Meyer'
		});
		var fullName = can.compute(me.fullName, me);
		equal(fullName(), 'Justin Meyer');
		var called = 0;
		fullName.bind('change', function (ev, newVal, oldVal) {
			called++;
			equal(called, 1, 'called only once');
			equal(newVal, 'Justin Shah');
			equal(oldVal, 'Justin Meyer');
		});
		me.attr('last', 'Shah');
	});
	test('setter compute', function () {
		var project = new can.LazyMap({
			progress: 0.5
		});
		var computed = can.compute(function (val) {
			if (val) {
				project.attr('progress', val / 100);
			} else {
				return parseInt(project.attr('progress') * 100, 10);
			}
		});
		equal(computed(), 50, 'the value is right');
		computed(25);
		equal(project.attr('progress'), 0.25);
		equal(computed(), 25);
		computed.bind('change', function (ev, newVal, oldVal) {
			equal(newVal, 75);
			equal(oldVal, 25);
		});
		computed(75);
	});
	test('compute a compute', function () {
		var project = new can.LazyMap({
			progress: 0.5
		});
		var percent = can.compute(function (val) {
			if (val) {
				project.attr('progress', val / 100);
			} else {
				return parseInt(project.attr('progress') * 100, 10);
			}
		});
		percent.named = 'PERCENT';
		equal(percent(), 50, 'percent starts right');
		percent.bind('change', function () {});
		var fraction = can.compute(function (val) {
			if (val) {
				percent(parseInt(val.split('/')[0], 10));
			} else {
				return percent() + '/100';
			}
		});
		fraction.named = 'FRACTIOn';
		fraction.bind('change', function () {});
		equal(fraction(), '50/100', 'fraction starts right');
		percent(25);
		equal(percent(), 25);
		equal(project.attr('progress'), 0.25, 'progress updated');
		equal(fraction(), '25/100', 'fraction updated');
		fraction('15/100');
		equal(fraction(), '15/100');
		equal(project.attr('progress'), 0.15, 'progress updated');
		equal(percent(), 15, '% updated');
	});
	test('compute with a simple compute', function () {
		expect(4);
		var a = can.compute(5);
		var b = can.compute(function () {
			return a() * 2;
		});
		equal(b(), 10, 'b starts correct');
		a(3);
		equal(b(), 6, 'b updates');
		b.bind('change', function () {
			equal(b(), 24, 'b fires change');
		});
		a(12);
		equal(b(), 24, 'b updates when bound');
	});
	test('empty compute', function () {
		var c = can.compute();
		c.bind('change', function (ev, newVal, oldVal) {
			ok(oldVal === undefined, 'was undefined');
			ok(newVal === 0, 'now zero');
		});
		c(0);
	});
	test('only one update on a batchTransaction', function () {
		var person = new can.LazyMap({
			first: 'Justin',
			last: 'Meyer'
		});
		var func = can.compute(function () {
			return person.attr('first') + ' ' + person.attr('last') + Math.random();
		});
		var callbacks = 0;
		func.bind('change', function() {
			callbacks++;
		});
		person.attr({
			first: 'Brian',
			last: 'Moschel'
		});
		equal(callbacks, 1, 'only one callback');
	});
	test('only one update on a start and end transaction', function () {
		var person = new can.LazyMap({
			first: 'Justin',
			last: 'Meyer'
		}),
			age = can.compute(5);
		var func = can.compute(function (newVal, oldVal) {
			return person.attr('first') + ' ' + person.attr('last') + age() + Math.random();
		});
		var callbacks = 0;
		func.bind('change', function () {
			callbacks++;
		});
		can.batch.start();
		person.attr('first', 'Brian');
		stop();
		setTimeout(function () {
			person.attr('last', 'Moschel');
			age(12);
			can.batch.stop();
			equal(callbacks, 1, 'only one callback');
			start();
		});
	});
	test('Compute emits change events when an embbedded observe has properties added or removed', 4, function () {
		var obs = new can.LazyMap(),
			compute1 = can.compute(function () {
				var txt = obs.attr('foo');
				obs.each(function (val) {
					txt += val.toString();
				});
				return txt;
			});
		compute1.bind('change', function (ev, newVal, oldVal) {
			ok(true, 'change handler fired: ' + newVal);
		});
		obs.attr('foo', 1);
		obs.attr('bar', 2);
		obs.attr('foo', 3);
		obs.removeAttr('bar');
		obs.removeAttr('bar');
	});
	test('compute only updates once when a list\'s contents are replaced', function () {
		var list = new can.List([{
			name: 'Justin'
		}]),
			computedCount = 0;
		var compute = can.compute(function () {
			computedCount++;
			list.each(function (item) {
				item.attr('name');
			});
		});
		equal(0, computedCount, 'computes are not called until their value is read');
		compute.bind('change', function (ev, newVal, oldVal) {});
		equal(1, computedCount, 'binding computes to store the value');
		list.replace([{
			name: 'hank'
		}]);
		equal(2, computedCount, 'only one compute');
	});
	test('Generate computes from Observes with can.LazyMap.prototype.compute (#203)', 6, function () {
		var obs = new can.LazyMap({
			test: 'testvalue'
		});
		var compute = obs.compute('test');
		ok(compute.isComputed, '`test` is computed');
		equal(compute(), 'testvalue', 'Value is as expected');
		obs.attr('test', 'observeValue');
		equal(compute(), 'observeValue', 'Value is as expected');
		compute.bind('change', function (ev, newVal) {
			equal(newVal, 'computeValue', 'new value from compute');
		});
		obs.bind('change', function (ev, name, how, newVal) {
			equal(newVal, 'computeValue', 'Got new value from compute');
		});
		compute('computeValue');
		equal(compute(), 'computeValue', 'Got updated value');
	});
	test('compute of computes', function () {
		expect(2);
		var suggestedSearch = can.compute(null),
			searchQuery = can.compute(''),
			searchText = can.compute(function () {
				var suggested = suggestedSearch();
				if (suggested) {
					return suggested;
				} else {
					return searchQuery();
				}
			});
		equal('', searchText(), 'inital set');
		searchText.bind('change', function (ev, newVal) {
			equal(newVal, 'food', 'food set');
		});
		searchQuery('food');
	});
	test('compute doesn\'t rebind and leak with 0 bindings', function () {
		var state = new can.LazyMap({
			foo: 'bar'
		});
		var computedA = 0,
			computedB = 0;
		var computeA = can.compute(function () {
			computedA++;
			return state.attr('foo') === 'bar';
		});
		var computeB = can.compute(function () {
			computedB++;
			return state.attr('foo') === 'bar' || 15;
		});

		function aChange(ev, newVal) {
			if (newVal) {
				computeB.bind('change.computeA', function () {});
			} else {
				computeB.unbind('change.computeA');
			}
		}
		computeA.bind('change', aChange);
		aChange(null, computeA());
		equal(computedA, 1, 'binding A computes the value');
		equal(computedB, 1, 'A=true, so B is bound, computing the value');
		state.attr('foo', 'baz');
		equal(computedA, 2, 'A recomputed and unbound B');
		equal(computedB, 1, 'B was unbound, so not recomputed');
		state.attr('foo', 'bar');
		equal(computedA, 3, 'A recomputed => true');
		equal(computedB, 2, 'A=true so B is rebound and recomputed');
		computeA.unbind('change', aChange);
		computeB.unbind('change.computeA');
		state.attr('foo', 'baz');
		equal(computedA, 3, 'unbound, so didn\'t recompute A');
		equal(computedB, 2, 'unbound, so didn\'t recompute B');
	});
	test('compute setter without external value', function () {
		var age = can.compute(0, function (newVal, oldVal) {
			var num = +newVal;
			if (!isNaN(num) && 0 <= num && num <= 120) {
				return num;
			} else {
				return oldVal;
			}
		});
		equal(age(), 0, 'initial value set');
		age.bind('change', function (ev, newVal, oldVal) {
			equal(5, newVal);
			age.unbind('change', this.Constructor);
		});
		age(5);
		equal(age(), 5, '5 set');
		age('invalid');
		equal(age(), 5, '5 kept');
	});
	test('compute value', function () {
		expect(9);
		var input = {
			value: 1
		};
		var value = can.compute('', {
			get: function () {
				return input.value;
			},
			set: function (newVal) {
				input.value = newVal;
			},
			on: function (update) {
				input.onchange = update;
			},
			off: function () {
				delete input.onchange;
			}
		});
		equal(value(), 1, 'original value');
		ok(!input.onchange, 'nothing bound');
		value(2);
		equal(value(), 2, 'updated value');
		equal(input.value, 2, 'updated input.value');
		value.bind('change', function (ev, newVal, oldVal) {
			equal(newVal, 3, 'newVal');
			equal(oldVal, 2, 'oldVal');
			value.unbind('change', this.Constructor);
		});
		ok(input.onchange, 'binding to onchange');
		value(3);
		ok(!input.onchange, 'removed binding');
		equal(value(), 3);
	});
	test('compute bound to observe', function () {
		var me = new can.LazyMap({
			name: 'Justin'
		});
		var bind = me.bind,
			unbind = me.unbind,
			bindCount = 0;
		me.bind = function () {
			bindCount++;
			bind.apply(this, arguments);
		};
		me.unbind = function () {
			bindCount--;
			unbind.apply(this, arguments);
		};
		var name = can.compute(me, 'name');
		equal(bindCount, 0);
		equal(name(), 'Justin');
		var handler = function (ev, newVal, oldVal) {
			equal(newVal, 'Justin Meyer');
			equal(oldVal, 'Justin');
		};
		name.bind('change', handler);
		equal(bindCount, 1);
		name.unbind('change', handler);
		stop();
		setTimeout(function () {
			start();
			equal(bindCount, 0);
		}, 100);
	});
	test('binding to a compute on an observe before reading', function () {
		var me = new can.LazyMap({
			name: 'Justin'
		});
		var name = can.compute(me, 'name');
		var handler = function (ev, newVal, oldVal) {
			equal(newVal, 'Justin Meyer');
			equal(oldVal, 'Justin');
		};
		name.bind('change', handler);
		equal(name(), 'Justin');
	});
	test('compute bound to input value', function () {
		var input = document.createElement('input');
		input.value = 'Justin';
		var value = can.compute(input, 'value', 'change');
		equal(value(), 'Justin');
		value('Justin M.');
		equal(input.value, 'Justin M.', 'input change correctly');
		var handler = function (ev, newVal, oldVal) {
			equal(newVal, 'Justin Meyer');
			equal(oldVal, 'Justin M.');
		};
		value.bind('change', handler);
		input.value = 'Justin Meyer';
		value.unbind('change', handler);
		stop();
		setTimeout(function () {
			input.value = 'Brian Moschel';
			equal(value(), 'Brian Moschel');
			start();
		}, 50);
	});
	test('compute on the prototype', function () {
		expect(4);
		var Person = can.LazyMap.extend({
			fullName: can.compute(function (fullName) {
				if (arguments.length) {
					var parts = fullName.split(' ');
					this.attr({
						first: parts[0],
						last: parts[1]
					});
				} else {
					return this.attr('first') + ' ' + this.attr('last');
				}
			})
		});
		var me = new Person();
		var fn = me.attr({
			first: 'Justin',
			last: 'Meyer'
		})
			.attr('fullName');
		equal(fn, 'Justin Meyer', 'can read attr');
		me.attr('fullName', 'Brian Moschel');
		equal(me.attr('first'), 'Brian', 'set first name');
		equal(me.attr('last'), 'Moschel', 'set last name');
		var handler = function (ev, newVal, oldVal) {
			ok(newVal, 'Brian M');
		};
		me.bind('fullName', handler);
		me.attr('last', 'M');
		me.unbind('fullName', handler);
		me.attr('first', 'B');
	});
	test('join is computable (#519)', function () {
		expect(2);
		var l = new can.List([
			'a',
			'b'
		]);
		var joined = can.compute(function () {
			return l.join(',');
		});
		joined.bind('change', function (ev, newVal, oldVal) {
			equal(oldVal, 'a,b');
			equal(newVal, 'a,b,c');
		});
		l.push('c');
	});

	test("nested computes", function () {

		var data = new can.LazyMap({});
		var compute = data.compute('summary.button');
		compute.bind('change', function () {
			ok(true, "compute changed");
		});

		data.attr({
			summary: {
				button: 'hey'
			}
		}, true);
	});

});
