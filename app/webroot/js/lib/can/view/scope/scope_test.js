steal("can/view/scope", "can/route", "can/test", "steal-qunit", function () {
	QUnit.module('can/view/scope');
	
	test("basics",function(){

		var items = new can.Map({ people: [{name: "Justin"},[{name: "Brian"}]], count: 1000 });
		
		var itemsScope = new can.view.Scope(items),
		arrayScope = new can.view.Scope(itemsScope.attr('people'), itemsScope),
		firstItem = new can.view.Scope( arrayScope.attr('0'), arrayScope );
		
		var nameInfo = firstItem.read('name');
		deepEqual(nameInfo.reads, ["name"]);
		equal(nameInfo.scope, firstItem);
		equal(nameInfo.value,"Justin");
		equal(nameInfo.rootObserve, items.people[0]);

	});
	/*
	 * REMOVE
	 test("adding items",function(){
	 expect(1);

	 var base = new can.view.Scope({}),
	 cur = base.add(new can.Map());


	 cur._data.bind("items",function(ev, newVal, oldVal){
	 ok(newVal.length, "newVal is an array")
	 })

	 cur.attr("items",[1])

	 })(*/
	/*	test("current context",function(){
	 var base = new can.view.Scope({}),
	 cur = base.add("foo")

	 equal( cur.get(".").value, "foo", ". returns value");

	 equal( cur.attr("."), "foo", ". returns value");

	 equal( cur.get("this").value, "foo", "this returns value");

	 equal( cur.attr("this"), "foo", "this returns value");
	 })*/
	/*test("highest scope observe is parent observe",function(){
	 var parent = new can.Map({name: "Justin"})
	 var child = new can.Map({vals: "something"})

	 var base = new can.view.Scope(parent),
	 cur = base.add(child);

	 var data = cur.get("bar")

	 equal(data.parent, parent, "gives highest parent observe")
	 equal(data.value, undefined, "no value")
	 })*/
	/*	test("computes on scope",function(){
	 var base = new can.view.Scope({}),
	 cur = base.add(can.compute({name: {first: "justin"}}));

	 var data = cur.get("name.first");
	 equal(data.value, "justin", "computes on path will be evaluted")
	 })*/
	/*	test("functions on an observe get called with this correctly", function(){

	 var Person = can.Map.extend({
	 fullName: function(){
	 equal( this.attr('first'), "Justin" )
	 }
	 })

	 var me = new Person({name: "Justin"})
	 var base = new can.view.Scope(me),
	 cur = base.add({other: "foo"})

	 var data = cur.get("fullName");

	 equal(data.value, Person.prototype.fullName, "got the raw function");
	 equal(data.parent, me, "parent provided")

	 })*/
	test('can.view.Scope.prototype.computeData', function () {
		var map = new can.Map();
		var base = new can.view.Scope(map);
		var age = base.computeData('age')
			.compute;
		equal(age(), undefined, 'age is not set');
		age.bind('change', function (ev, newVal, oldVal) {
			equal(newVal, 31, 'newVal is provided correctly');
			equal(oldVal, undefined, 'oldVal is undefined');
		});
		age(31);
		equal(map.attr('age'), 31, 'maps age is set correctly');
	});
	test('backtrack path (#163)', function () {
		var row = new can.Map({
			first: 'Justin'
		}),
			col = {
				format: 'str'
			}, base = new can.view.Scope(row),
			cur = base.add(col);
		equal(cur.attr('.'), col, 'got col');
		equal(cur.attr('..'), row, 'got row');
		equal(cur.attr('../first'), 'Justin', 'got row');
	});

	test('nested properties with compute', function () {
		var me = new can.Map({
			name: {
				first: 'Justin'
			}
		});
		var cur = new can.view.Scope(me);
		var compute = cur.computeData('name.first')
			.compute;
		var changes = 0;
		compute.bind('change', function (ev, newVal, oldVal) {
			if (changes === 0) {
				equal(oldVal, 'Justin');
				equal(newVal, 'Brian');
			} else if (changes === 1) {
				equal(oldVal, 'Brian');
				equal(newVal, undefined);
			} else if (changes === 2) {
				equal(oldVal, undefined);
				equal(newVal, 'Payal');
			} else if (changes === 3) {
				equal(oldVal, 'Payal');
				equal(newVal, 'Curtis');
			}
			changes++;
		});
		equal(compute(), 'Justin', 'read value after bind');
		me.attr('name.first', 'Brian');
		me.removeAttr('name');
		me.attr('name', {
			first: 'Payal'
		});
		me.attr('name', new can.Map({
			first: 'Curtis'
		}));
	});
	test('function at the end', function () {
		var compute = new can.view.Scope({
			me: {
				info: function () {
					return 'Justin';
				}
			}
		})
			.computeData('me.info')
			.compute;
		equal(compute(), 'Justin');
		var fn = function () {
			return this.name;
		};
		var compute2 = new can.view.Scope({
			me: {
				info: fn,
				name: 'Hank'
			}
		})
			.computeData('me.info', {
				isArgument: true,
				args: []
			})
			.compute;
		equal(compute2()(), 'Hank');
	});
	test('binds to the right scope only', function () {
		var baseMap = new can.Map({
			me: {
				name: {
					first: 'Justin'
				}
			}
		});
		var base = new can.view.Scope(baseMap);
		var topMap = new can.Map({
			me: {
				name: {}
			}
		});
		var scope = base.add(topMap);
		var compute = scope.computeData('me.name.first')
			.compute;
		compute.bind('change', function (ev, newVal, oldVal) {
			equal(oldVal, 'Justin');
			equal(newVal, 'Brian');
		});
		equal(compute(), 'Justin');
		// this should do nothing
		topMap.attr('me.name.first', 'Payal');
		baseMap.attr('me.name.first', 'Brian');
	});
	test('Scope read returnObserveMethods=true', function () {
		var MapConstruct = can.Map.extend({
			foo: function (arg) {
				equal(this, data.map, 'correct this');
				equal(arg, true, 'correct arg');
			}
		});
		var data = {
			map: new MapConstruct()
		};
		var res = can.view.Scope.read(data, [
			'map',
			'foo'
		], {
			returnObserveMethods: true,
			isArgument: true
		});
		res.value(true);
	});
	test('rooted observable is able to update correctly', function () {
		var baseMap = new can.Map({
			name: {
				first: 'Justin'
			}
		});
		var scope = new can.view.Scope(baseMap);
		var compute = scope.computeData('name.first')
			.compute;
		equal(compute(), 'Justin');
		baseMap.attr('name', new can.Map({
			first: 'Brian'
		}));
		equal(compute(), 'Brian');
	});
	test('computeData reading an object with a compute', function () {
		var sourceAge = 21;
		var age = can.compute(function (newVal) {
			if (newVal) {
				sourceAge = newVal;
			} else {
				return sourceAge;
			}
		});
		var scope = new can.view.Scope({
			person: {
				age: age
			}
		});
		var computeData = scope.computeData('person.age');
		var value = computeData.compute();
		equal(value, 21, 'correct value');
		computeData.compute(31);
		equal(age(), 31, 'age updated');
	});
	test('computeData with initial empty compute (#638)', function () {
		expect(2);
		var compute = can.compute();
		var scope = new can.view.Scope({
			compute: compute
		});
		var computeData = scope.computeData('compute');
		equal(computeData.compute(), undefined);
		computeData.compute.bind('change', function (ev, newVal) {
			equal(newVal, 'compute value');
		});
		compute('compute value');
	});

	test('Can read static properties on constructors (#634)', function () {
		can.Map.extend('can.Foo', {
			static_prop: 'baz'
		}, {
			proto_prop: 'thud'
		});
		var data = new can.Foo({
			own_prop: 'quux'
		}),
			scope = new can.view.Scope(data);
		equal(scope.computeData('constructor.static_prop')
			.compute(), 'baz', 'static prop');
	});

	test("Can read static properties on constructors (#634)", function () {
		can.Map.extend("can.Foo", {
			static_prop: "baz"
		}, {
			proto_prop: "thud"
		});
		var data = new can.Foo({
			own_prop: "quux"
		}),
			scope = new can.view.Scope(data);

		equal(scope.computeData("constructor.static_prop")
			.compute(), "baz", "static prop");
	});

	test('Scope lookup restricted to current scope with ./ (#874)', function() {
		var current;
		var scope = new can.view.Scope(
				new can.Map({value: "A Value"})
			).add(
				current = new can.Map({})
			);
		
		var compute = scope.computeData('./value').compute;
		
		equal(compute(), undefined, "no initial value");
		
		
		compute.bind("change", function(ev, newVal){
			equal(newVal, "B Value", "changed");
		});
		
		compute("B Value");
		equal(current.attr("value"), "B Value", "updated");
		
	});
	
	test('reading properties on undefined (#1314)', function(){
		
		var scope = new can.view.Scope(undefined);
		
		var compute = scope.compute("property");
		
		equal(compute(), undefined, "got back undefined");
		
	});
	

	test("Scope attributes can be set (#1297, #1304)", function(){
		var comp = can.compute('Test');
		var map = new can.Map({
			other: {
				name: "Justin"
			}
		});
		var scope = new can.view.Scope({
			name: "Matthew",
			other: {
				person: {
					name: "David"
				},
				comp: comp
			}
		});

		scope.attr("name", "Wilbur");
		equal(scope.attr("name"), "Wilbur", "Value updated");

		scope.attr("other.person.name", "Dave");
		equal(scope.attr("other.person.name"), "Dave", "Value updated");

		scope.attr("other.comp", "Changed");
		equal(comp(), "Changed", "Compute updated");

		scope = new can.view.Scope(map);
		scope.attr("other.name", "Brian");

		equal(scope.attr("other.name"), "Brian", "Value updated");
		equal(map.attr("other.name"), "Brian", "Name update in map");
	});
	
	test("computeData.compute get/sets computes in maps", function(){
		var compute = can.compute(4);
		var map = new can.Map();
		map.attr("computer", compute);
		
		var scope = new can.view.Scope(map);
		var computeData = scope.computeData("computer",{});
		
		equal( computeData.compute(), 4, "got the value");
		
		computeData.compute(5);
		equal(compute(), 5, "updated compute value");
		equal( computeData.compute(), 5, "the compute has the right value");
	});
	
	test("computesData can find update when initially undefined parent scope becomes defined (#579)", function(){
		expect(2);
		
		var map = new can.Map();
		var scope = new can.view.Scope(map);
		var top = scope.add(new can.Map());
		
		var computeData = top.computeData("value",{});
		
		equal( computeData.compute(), undefined, "initially undefined");
		
		computeData.compute.bind("change", function(ev, newVal){
			equal(newVal, "first");
		});
		
		map.attr("value","first");
		
		
	});

});
