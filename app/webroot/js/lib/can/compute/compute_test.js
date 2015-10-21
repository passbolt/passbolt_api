steal("can/compute", "can/test", "can/map", "steal-qunit", function () {
	QUnit.module('can/compute');
	test('single value compute', function () {
		var num = can.compute(1);
		num.bind('change', function (ev, newVal, oldVal) {
			equal(newVal, 2, 'newVal');
			equal(oldVal, 1, 'oldVal');
		});
		num(2);
	});
	test('inner computes values are not bound to', function () {
		var num = can.compute(1);
		var outer = can.compute(function() {
			var inner = can.compute(function() {
				return num() + 1;
			});
			return 2 * inner();
		});

		var handler = function () {};
		outer.bind('change', handler);
		// We do a timeout because we temporarily bind on num so that we can use its cached value.
		stop();
		setTimeout(function () {
			equal(num.computeInstance._bindings, 1, 'inner compute only bound once');
			equal(outer.computeInstance._bindings, 1, 'outer compute only bound once');
			start();
		}, 50);
	});

	test('can.compute.truthy', function () {
		var result = 0;
		var num = can.compute(3);
		var truthy = can.compute.truthy(num);
		var tester = can.compute(function () {
			if (truthy()) {
				return ++result;
			} else {
				return ++result;
			}
		});
		tester.bind('change', function (ev, newVal, oldVal) {
			if (num() === 0) {
				equal(newVal, 2, '2 is the new val');
			} else if (num() === -1) {
				equal(newVal, 3, '3 is the new val');
			} else {
				ok(false, 'change should not be called');
			}
		});
		equal(tester(), 1, 'on bind, we call tester once');
		num(2);
		num(1);
		num(0);
		num(-1);
	});
	test('a binding compute does not double read', function () {
		var sourceAge = 30,
			timesComputeIsCalled = 0;
		var age = can.compute(function (newVal) {
			timesComputeIsCalled++;
			if (timesComputeIsCalled === 1) {
				ok(true, 'reading age to get value');
			} else if (timesComputeIsCalled === 2) {
				equal(newVal, 31, 'the second time should be an update');
			} else if (timesComputeIsCalled === 3) {
				ok(true, 'called after set to get the value');
			} else {
				ok(false, 'You\'ve called the callback ' + timesComputeIsCalled + ' times');
			}
			if (arguments.length) {
				sourceAge = newVal;
			} else {
				return sourceAge;
			}
		});
		var info = can.compute(function () {
			return 'I am ' + age();
		});
		var k = function () {};
		info.bind('change', k);
		equal(info(), 'I am 30');
		age(31);
		equal(info(), 'I am 31');
	});
	test('cloning a setter compute (#547)', function () {
		var name = can.compute('', function (newVal) {
			return this.txt + newVal;
		});
		var cloned = name.clone({
			txt: '.'
		});
		cloned('-');
		equal(cloned(), '.-');
	});
	
	test('compute updated method uses get and old value (#732)', function () {
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
		
		
		input.value = 3;
		input.onchange({});
		
		ok(!input.onchange, 'removed binding');
		equal(value(), 3);
	});
	
	test("a compute updated by source changes within a batch is part of that batch", function(){
		
		var computeA = can.compute("a");
		var computeB = can.compute("b");
		
		var combined1 = can.compute(function(){
			
			return computeA()+" "+computeB();
			
		});
		
		var combined2 = can.compute(function(){
			
			return computeA()+" "+computeB();
			
		});
		
		var combo = can.compute(function(){
			return combined1()+" "+combined2();
		});
		
		var callbacks = 0;
		combo.bind("change", function(){
			if(callbacks === 0){
				ok(true, "called change once");
			} else {
				ok(false, "called change multiple times");
			}
			callbacks++;
		});
		
		can.batch.start();
		computeA("A");
		computeB("B");
		can.batch.stop();
	});
	
	test("compute.async can be like a normal getter", function(){
		var first = can.compute("Justin"),
			last = can.compute("Meyer"),
			fullName = can.compute.async("", function(){
				return first()+" "+last();
			});
			
		equal(fullName(), "Justin Meyer");
	});
	
	test("compute.async operate on single value", function(){
		
		var a = can.compute(1);
		var b = can.compute(2);
				
		var obj = can.compute.async({}, function( curVal ){
			if(a()) {
				curVal.a = a();
			} else {
				delete curVal.a;
			}
			if(b()) {
				curVal.b = b();
			} else {
				delete curVal.b;
			}
			return curVal;
		});
		
		obj.bind("change", function(){});
		
		deepEqual( obj(), {a: 1, b: 2}, "object has all properties" );
		
		a(0);
		
		deepEqual( obj(), {b: 2}, "removed a" );
		
		b(0);
		
		deepEqual( obj(), {}, "removed b" );
		
	});
	
	test("compute.async async changing value", function(){
		
		var a = can.compute(1);
		var b = can.compute(2);
				
		var async = can.compute.async(undefined,function( curVal, setVal ){
			
			if(a()) {
				setTimeout(function(){
					setVal("a");
				},10);
			} else if(b()) {
				setTimeout(function(){
					setVal("b");
				},10);
			} else {
				return null;
			}
		});
		
		var changeArgs = [
			{newVal: "a", oldVal: undefined, run: function(){ a(0); } },
			{newVal: "b", oldVal: "a", run: function(){ b(0); }},
			{newVal: null, oldVal: "b", run: function(){ start(); }}
		],
			changeNum = 0;
		
		stop();
		
		
		async.bind("change", function(ev, newVal, oldVal){
			var data = changeArgs[changeNum++];
			equal( newVal, data.newVal, "newVal is correct" );
			equal( oldVal, data.oldVal, "oldVal is correct" );
			
			setTimeout(data.run, 10);
			
		});
		
		
		
	});

	test("can.Construct derived classes should be considered objects, not functions (#450)", function() {
		var foostructor = can.Map({ text: "bar" }, {}),
			obj = {
				next_level: {
					thing: foostructor,
					text: "In the inner context"
				}
			},
			read;
		foostructor.self = foostructor;

		read = can.compute.read(obj, ["next_level","thing","self","text"]);
		equal(read.value, "bar", "static properties on a can.Construct-based function");

		read = can.compute.read(obj, ["next_level","thing","self"], { isArgument: true });
		ok(read.value === foostructor, "arguments shouldn't be executed");

		foostructor.self = function() { return foostructor; };
		read = can.compute.read(obj, ["next_level","thing","self","text"], { executeAnonymousFunctions: true });
		equal(read.value, "bar", "anonymous functions in the middle of a read should be executed if requested");
	});
	
	test("compute.async read without binding", function(){
		
		var source = can.compute(1);
		
		var async = can.compute.async([],function( curVal, setVal ){
			curVal.push(source());
			return curVal;
		});
		
		ok(async(), "calling async worked");
		
		
		
	});
	

	test("compute.read works with a Map wrapped in a compute", function() {
		var parent = can.compute(new can.Map({map: {first: "Justin" }}));
		var reads = ["map", "first"];

		var result = can.compute.read(parent, reads);
		equal(result.value, "Justin", "The correct value is found.");
	});

	test('compute.read works with a Map wrapped in a compute', function() {
		var parent = new can.Compute(new can.Map({map: {first: 'Justin' }}));
		var reads = ['map', 'first'];

		var result = can.Compute.read(parent, reads);
		equal(result.value, 'Justin', 'The correct value is found.');
	});

	test("compute.read returns constructor functions instead of executing them (#1332)", function() {
		var Todo = can.Map.extend({});
		var parent = can.compute(new can.Map({map: { Test: Todo }}));
		var reads = ["map", "Test"];

		var result = can.compute.read(parent, reads);
		equal(result.value, Todo, 'Got the same Todo');
	});
	
	test("compute.set with different values", 4, function() {
		var comp = can.compute("David");
		var parent = {
			name: "David",
			comp: comp
		};
		var map = new can.Map({
			name: "David"
		});

		map.bind('change', function(ev, attr, how, value) {
			equal(value, "Brian", "Got change event on map");
		});
		
		can.compute.set(parent, "name", "Matthew");
		equal(parent.name, "Matthew", "Name set");

		can.compute.set(parent, "comp", "Justin");
		equal(comp(), "Justin", "Name updated");

		can.compute.set(map, "name", "Brian");
		equal(map.attr("name"), "Brian", "Name updated in map");
	});


	// ========================================
	QUnit.module('can/Compute');
	
	test('single value compute', function () {
		expect(2);
		var num = new can.Compute(1);
		num.bind('change', function (ev, newVal, oldVal) {
			equal(newVal, 2, 'newVal');
			equal(oldVal, 1, 'oldVal');
		});
		num.set(2);
	});

	test('inner computes values are not bound to', function () {
		var num = new can.Compute(1),
			numBind = num.bind,
			numUnbind = num.unbind;
		var bindCount = 0;
		num.bind = function() {
			bindCount++;
			return numBind.apply(this, arguments);
		};
		num.unbind = function() {
			bindCount--;
			return numUnbind.apply(this, arguments);
		};
		var outer = new can.Compute(function() {
			var inner = new can.Compute(function() {
				return num.get() + 1;
			});
			return 2 * inner.get();
		});
		var handler = function() {};
		outer.bind('change', handler);
		// We do a timeout because we temporarily bind on num so that we can use its cached value.
		stop();
		setTimeout(function() {
			equal(bindCount, 1, 'compute only bound to once');
			start();
		}, 50);
	});

	test('can.Compute.truthy', function() {
		var result = 0;
		var num = new can.Compute(3);
		var truthy = can.Compute.truthy(num);
		var tester = new can.Compute(function() {
			if(truthy.get()) {
				return ++result;
			} else {
				return ++result;
			}
		});

		tester.bind('change', function(ev, newVal, oldVal) {
			if (num.get() === 0) {
				equal(newVal, 2, '2 is the new val');
			} else if (num.get() === -1) {
				equal(newVal, 3, '3 is the new val');
			} else {
				ok(false, 'change should not be called');
			}
		});
		equal(tester.get(), 1, 'on bind, we call tester once');
		num.set(2);
		num.set(1);
		num.set(0);
		num.set(-1);
	});

	test('a binding compute does not double read', function () {
		var sourceAge = 30,
			timesComputeIsCalled = 0;
		var age = new can.Compute(function (newVal) {
			timesComputeIsCalled++;
			if (timesComputeIsCalled === 1) {
				ok(true, 'reading age to get value');
			} else if (timesComputeIsCalled === 2) {
				equal(newVal, 31, 'the second time should be an update');
			} else if (timesComputeIsCalled === 3) {
				ok(true, 'called after set to get the value');
			} else {
				ok(false, 'You\'ve called the callback ' + timesComputeIsCalled + ' times');
			}
			if (arguments.length) {
				sourceAge = newVal;
			} else {
				return sourceAge;
			}
		});

		var info = new can.Compute(function () {
			return 'I am ' + age.get();
		});

		var k = function () {};
		info.bind('change', k);
		equal(info.get(), 'I am 30');
		age.set(31);
		equal(info.get(), 'I am 31');
	});

	test('cloning a setter compute (#547)', function () {
		var name = new can.Compute('', function(newVal) {
			return this.txt + newVal;
		});

		var cloned = name.clone({
			txt: '.'
		});

		cloned.set('-');
		equal(cloned.get(), '.-');
	});

	test('compute updated method uses get and old value (#732)', function () {
		expect(9);

		var input = {
			value: 1
		};

		var value = new can.Compute('', {
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

		equal(value.get(), 1, 'original value');
		ok(!input.onchange, 'nothing bound');
		value.set(2);
		equal(value.get(), 2, 'updated value');
		equal(input.value, 2, 'updated input.value');

		value.bind('change', function (ev, newVal, oldVal) {
			equal(newVal, 3, 'newVal');
			equal(oldVal, 2, 'oldVal');
			value.unbind('change', this.Constructor);
		});

		ok(input.onchange, 'binding to onchange');

		input.value = 3;
		input.onchange({});

		ok(!input.onchange, 'removed binding');
		equal(value.get(), 3);
	});

	test('a compute updated by source changes within a batch is part of that batch', function () {
		var computeA = new can.Compute('a');
		var computeB = new can.Compute('b');
		
		var combined1 = new can.Compute(function() {
			return computeA.get() + ' ' + computeB.get();
		});

		var combined2 = new can.Compute(function() {
			return computeA.get() + ' ' + computeB.get();
		});

		var combo = new can.Compute(function() {
			return combined1.get() + ' ' + combined2.get();
		});

		var callbacks = 0;
		combo.bind('change', function(){
			if(callbacks === 0){
				ok(true, 'called change once');
			} else {
				ok(false, 'called change multiple times');
			}
			callbacks++;
		});
		
		can.batch.start();
		computeA.set('A');
		computeB.set('B');
		can.batch.stop();
	});

	test('compute.async can be like a normal getter', function() {
		var first = new can.Compute('Justin'),
			last = new can.Compute('Meyer'),
			fullName = can.Compute.async('', function(){
				return first.get() + ' ' + last.get();
			});

		equal(fullName.get(), 'Justin Meyer');
	});

	test('compute.async operate on single value', function() {
		var a = new can.Compute(1);
		var b = new can.Compute(2);

		var obj = can.Compute.async({}, function(curVal) {
			if(a.get()) {
				curVal.a = a.get();
			} else {
				delete curVal.a;
			}

			if(b.get()) {
				curVal.b = b.get();
			} else {
				delete curVal.b;
			}

			return curVal;
		});

		obj.bind('change', function() {});
		deepEqual(obj.get(), {a: 1, b: 2}, 'object has all properties');

		a.set(0);
		deepEqual(obj.get(), {b: 2}, 'removed a');

		b.set(0);
		deepEqual(obj.get(), {}, 'removed b');
	});

	test('compute.async async changing value', function() {
		var a = new can.Compute(1);
		var b = new can.Compute(2);

		var async = can.Compute.async(undefined, function(curVal, setVal) {
			if(a.get()) {
				setTimeout(function() {
					setVal('a');
				}, 10);
			} else if(b.get()) {
				setTimeout(function() {
					setVal('b');
				}, 10);
			} else {
				return null;
			}
		});

		var changeArgs = [
			{newVal: 'a', oldVal: undefined, run: function() { a.set(0); } },
			{newVal: 'b', oldVal: 'a', run: function() { b.set(0); }},
			{newVal: null, oldVal: 'b', run: function() { start(); }}
		],
		changeNum = 0;

		stop();

		async.bind('change', function(ev, newVal, oldVal) {
			var data = changeArgs[changeNum++];
			equal( newVal, data.newVal, 'newVal is correct' );
			equal( oldVal, data.oldVal, 'oldVal is correct' );

			setTimeout(data.run, 10);
		});
	});

	test('can.Construct derived classes should be considered objects, not functions (#450)', function() {
		var foostructor = can.Map({ text: 'bar' }, {}),
			obj = {
				next_level: {
					thing: foostructor,
					text: 'In the inner context'
				}
			},
			read;
		foostructor.self = foostructor;

		read = can.Compute.read(obj, ['next_level','thing','self','text']);
		equal(read.value, 'bar', 'static properties on a can.Construct-based function');

		read = can.Compute.read(obj, ['next_level','thing','self'], { isArgument: true });
		ok(read.value === foostructor, 'arguments shouldn\'t be executed');

		foostructor.self = function() { return foostructor; };
		read = can.Compute.read(obj, ['next_level','thing','self','text'], { executeAnonymousFunctions: true });
		equal(read.value, 'bar', 'anonymous functions in the middle of a read should be executed if requested');
	});

	test('compute.async read without binding', function() {
		var source = new can.Compute(1);

		var async = can.Compute.async([],function( curVal, setVal ) {
			curVal.push(source.get());
			return curVal;
		});

		ok(async.get(), 'calling async worked');
	});

	test('Compute.async set uses last set or initial value', function() {

		var add = new can.Compute(1);

		var fnCount = 0;

		var async = can.Compute.async(10,function( curVal ) {
			switch(fnCount++) {
				case 0:
					equal(curVal, 10);
					break;
				case 1:
					equal(curVal, 20);
					break;
				case 2:
					equal(curVal, 30, "on bind");
					break;
				case 3:
					equal(curVal, 30, "on bind");
					break;
			}
			return curVal+add.get();
		});

		equal(async.get(), 11, "initial value");
		
		async.set(20);
		
		async.bind("change", function(){});
		
		async.set(20);
		
		async.set(30);
	});


	
	test("setting compute.async with a observable dependency gets a new value and can re-compute", 4, function(){
		// this is needed for define with a set and get.
		var compute = can.compute(1);
		var add;
		
		var async = can.compute.async(1, function(curVal){
			add = curVal;
			return compute()+add;
		});
		
		
		equal( async(), 2, "can read unbound");
		
		async.bind("change", function(ev, newVal, oldVal){
			equal(newVal, 3, "change new val");
			equal(oldVal, 2, "change old val");
		});
		
		
		async(2);
		
		equal( async(), 3, "can read unbound");
	});

	test('compute.async getter has correct when length === 1', function(){
		var m = new can.Map();

		var getterCompute = can.compute.async(false, function (singleArg) {
			equal(this, m, 'getter has the right context');
		}, m);

		getterCompute.bind('change', can.noop);
	});

	test("bug with nested computes and batch ordering (#1519)", function(){
	
		var ft = can.compute('a');
		var other = can.compute(3);
		
		var propA = can.compute(function(){
			return ft() ==='a';
		});
		
		var propB = can.compute(function(){
			return ft() === 'b';
		});
		
		var combined = can.compute(function(){
			var valA = propA(),
				valB = propB();

			return valA || valB;
		});
		
		equal(combined(), true);
		
		combined.bind('change', function(){ });
		
		ft.bind('change', function() {
			can.batch.start();
			other(2);
			can.batch.stop();
		});

		can.batch.start();
		ft('b');
		can.batch.stop();

		equal(combined(), true);
		equal(other(), 2);
	});
	
	test("can.Compute.read can read a promise (#179)", function(){
		
		var def = new can.Deferred();
		var map = new can.Map();
		
		var c = can.compute(function(){
			return can.Compute.read({map: map},["map","data","value"]).value;
		});
		
		var calls = 0;
		c.bind("change", function(ev, newVal, oldVal){
			calls++;
			equal(calls, 1, "only one call");
			equal(newVal, "Something", "new value");
			equal(oldVal, undefined, "oldVal");
			start();
		});
		
		map.attr("data", def);
		
		setTimeout(function(){
			def.resolve("Something");
		},2);
		
		stop();
		
	});

	test('compute change handler context is set to the function not can.Compute', function() {
		var comp = can.compute(null);

		comp.bind('change', function() {
			equal(typeof this, 'function');
		});

		comp('test');
	});

	test('Calling .unbind() on un-bound compute does not throw an error', function () {
		var count =  can.compute(0);
		count.unbind('change');
		ok(true, 'No error was thrown');
	});
});
