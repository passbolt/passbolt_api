steal("can/util", "can/list", "can/test", "can/compute", "steal-qunit", function(){
	
	QUnit.module('can/list');
	
	test('list attr changes length', function () {
		var l = new can.List([
			0,
			1,
			2
		]);
		l.attr(3, 3);
		equal(l.length, 4);
	});
	test('removeAttr on list', function() {
		var l = new can.List([0, 1, 2]);
		l.removeAttr(1);
		equal(l.attr('length'), 2);
		deepEqual(l.attr(), [0, 2]);
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
				equal(how, 'remove');
				equal(attr, '0');
			} else {
				ok(false, 'called too many times');
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
	test('splice removes items in IE (#562)', function () {
		var l = new can.List(['a']);
		l.splice(0, 1);
		ok(!l.attr(0), 'all props are removed');
	});

	test('list sets up computed attributes (#790)', function() {
		var List = can.List.extend({
			i: can.compute(0),
			a: 0
		});

		var l = new List([1]);
		equal(l.attr('i'), 0);

		var Map = can.Map.extend({
			f: can.compute(0)
		});

		var m = new Map();
		m.attr('f');
	});

	test('reverse triggers add/remove events (#851)', function() {
		expect(6);
		var l = new can.List([1,2,3]);

		l.bind('change', function() { ok(true, 'change should be called'); });
		l.bind('set', function() { ok(false, 'set should not be called'); });
		l.bind('add', function() { ok(true, 'add called'); });
		l.bind('remove', function() { ok(true, 'remove called'); });
		l.bind('length', function() { ok(true, 'length should be called'); });

		l.reverse();
	});

	test('filter', function(){
		var l = new can.List([{id: 1, name: "John"}, {id: 2, name: "Mary"}]);

		var filtered = l.filter(function(item){
			return item.name === "Mary";
		});

		notEqual(filtered._cid, l._cid, "not same object");
		equal(filtered.length, 1, "one item");
		equal(filtered[0].name, "Mary", "filter works");
	});
	
	
	test('removing expandos on lists', function(){
		var list = new can.List(["a","b"]);
		
		list.removeAttr("foo");
		
		equal(list.length, 2);
	});

	test('No Add Events if List Splice adds the same items that it is removing. (#1277, #1399)', function() {
		var list = new can.List(["a","b"]);

		list.bind('add', function() {
			ok(false, 'Add callback should not be called.');
		});

		list.bind('remove', function() {
			ok(false, 'Remove callback should not be called.');
		});

	  var result = list.splice(0, 2, "a", "b");

	  deepEqual(result, ["a", "b"]);
	});
	
	test("add event always returns an array as the value (#998)", function() {
		var list = new can.List([]),
			msg;
		list.bind("add", function(ev, newElements, index) {
			deepEqual(newElements, [4], msg);
		});
		msg = "works on push";
		list.push(4);
		list.pop();
		msg = "works on attr()";
		list.attr(0, 4);
		list.pop();
		msg = "works on replace()";
		list.replace([4]);
	});
	
	test("Setting with .attr() out of bounds of length triggers add event with leading undefineds", function() {
		var list = new can.List([1]);
		list.bind("add", function(ev, newElements, index) {
			deepEqual(newElements, [undefined, undefined, 4],
					  "Leading undefineds are included");
			equal(index, 1, "Index takes into account the leading undefineds from a .attr()");
		});
		list.attr(3, 4);
	});

	test("No events should fire if removals happened on empty arrays", function() {
		var list = new can.List([]),
			msg;
		list.bind("remove", function(ev, removed, index) {
			ok(false, msg);
		});
		msg = "works on pop";
		list.pop();
		msg = "works on shift";
		list.shift();
		ok(true, "No events were fired.");
	});

	test('setting an index out of bounds does not create an array', function() {
		expect(1);
		var l = new can.List();

		l.attr('1', 'foo');
		equal(l.attr('1'), 'foo');
	});

	test('splice with similar but less items works (#1606)', function() {
		var list = new can.List([ 'aa', 'bb', 'cc']);

		list.splice(0, list.length, 'aa', 'cc', 'dd');
		deepEqual(list.attr(), ['aa', 'cc', 'dd']);

		list.splice(0, list.length, 'aa', 'cc');
		deepEqual(list.attr(), ['aa', 'cc']);
	});
});
