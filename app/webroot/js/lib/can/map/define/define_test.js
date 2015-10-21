/* jshint asi: false */
steal("can/map/define", "can/route", "can/test", "steal-qunit", function () {
	
	
	QUnit.module('can/map/define');

	// remove, type, default
	test('basics set', function () {
		var Defined = can.Map.extend({
			define: {
				prop: {
					set: function (newVal) {
						return "foo" + newVal;
					}
				}
			}
		});

		var def = new Defined();
		def.attr("prop", "bar");

		equal(def.attr("prop"), "foobar", "setter works");

		Defined = can.Map.extend({
			define: {
				prop: {
					set: function (newVal, setter) {
						setter("foo" + newVal);
					}
				}
			}
		});

		def = new Defined();
		def.attr("prop", "bar");

		equal(def.attr("prop"), "foobar", "setter callback works");

	});

	test("basics remove", function () {
		var ViewModel = can.Map.extend({
			define: {
				makeId: {
					remove: function () {
						this.removeAttr("models");
					}
				},
				models: {
					remove: function () {
						this.removeAttr("modelId");
					}
				},
				modelId: {
					remove: function () {
						this.removeAttr("years");
					}
				},
				years: {
					remove: function () {
						this.removeAttr("year");
					}
				}
			}
		});

		var mmy = new ViewModel({
			makes: [
				{id: 1}
			],
			makeId: 1,
			models: [
				{id: 2}
			],
			modelId: 2,
			years: [2010],
			year: 2010
		});

		var events = ["year", "years", "modelId", "models", "makeId"],
			eventCount = 0,
			batchNum;

		mmy.bind("change", function (ev, attr) {
			if (batchNum === undefined) {
				batchNum = ev.batchNum;
			}
			equal(attr, events[eventCount++], "got correct attribute");
			ok(ev.batchNum && ev.batchNum === batchNum, "batched");
		});

		mmy.removeAttr("makeId");

	});

	test("basics get", function () {

		var Person = can.Map.extend({
			define: {
				fullName: {
					get: function () {
						return this.attr("first") + " " + this.attr("last");
					}
				}
			}
		});

		var p = new Person({first: "Justin", last: "Meyer"});

		equal(p.attr("fullName"), "Justin Meyer", "sync getter works");

		var Adder = can.Map.extend({
			define: {
				more: {
					get: function (curVal, setVal) {
						var num = this.attr("num");
						setTimeout(function () {
							setVal(num + 1);
						}, 10);
					}
				}
			}
		});

		var a = new Adder({num: 1}),
			callbackVals = [
				[2, undefined, function () {
					a.attr("num", 2);
				}],
				[3, 2, function () {
					start();
				}]
			],
			callbackCount = 0;

		a.bind("more", function (ev, newVal, oldVal) {
			var vals = callbackVals[callbackCount++];
			equal(newVal, vals[0], "newVal is correct");
			equal(a.attr("more"), vals[0], "attr value is correct");

			equal(oldVal, vals[1], "oldVal is correct");

			setTimeout(vals[2], 10);
		});

		stop();
	});

	test("basic type", function () {

		expect(6);

		var Typer = can.Map.extend({
			define: {
				arrayWithAddedItem: {
					type: function (value) {
						if (value && value.push) {
							value.push("item");
						}
						return value;
					}
				},
				listWithAddedItem: {
					type: function (value) {
						if (value && value.push) {
							value.push("item");
						}
						return value;
					},
					Type: can.List
				}
			}
		});


		var t = new Typer();
		deepEqual(can.Map.keys(t), [], "no keys");

		var array = [];
		t.attr("arrayWithAddedItem", array);

		deepEqual(array, ["item"], "updated array");
		equal(t.attr("arrayWithAddedItem"), array, "leave value as array");

		t.attr("listWithAddedItem", []);

		ok(t.attr("listWithAddedItem") instanceof can.List, "convert to can.List");
		equal(t.attr("listWithAddedItem").attr(0), "item", "has item in it");

		t.bind("change", function (ev, attr) {
			equal(attr, "listWithAddedItem.1", "got a bubbling event");
		});

		t.attr("listWithAddedItem").push("another item");

	});

	test("basic Type", function () {
		var Foo = function (name) {
			this.name = name;
		};
		Foo.prototype.getName = function () {
			return this.name;
		};

		var Typer = can.Map.extend({
			define: {
				foo: {
					Type: Foo
				}
			}
		});

		var t = new Typer({foo: "Justin"});
		equal(t.attr("foo").getName(), "Justin", "correctly created an instance");

		var brian = new Foo("brian");

		t.attr("foo", brian);

		equal(t.attr("foo"), brian, "same instances");

	});

	test("type converters", function () {

		var Typer = can.Map.extend({
			define: {
				date: {  type: 'date' },
				string: {type: 'string'},
				number: {  type: 'number' },
				'boolean': {  type: 'boolean' },
				htmlbool: {  type: 'htmlbool' },
				leaveAlone: {  type: '*' }
			}
		});
		var obj = {};

		var t = new Typer({
			date: 1395896701516,
			string: 5,
			number: '5',
			'boolean': 'false',
			htmlbool: "",
			leaveAlone: obj
		});

		ok(t.attr("date") instanceof Date, "converted to date");

		equal(t.attr("string"), '5', "converted to string");

		equal(t.attr("number"), 5, "converted to number");

		equal(t.attr("boolean"), false, "converted to boolean");

		equal(t.attr("htmlbool"), true, "converted to htmlbool");

		equal(t.attr("leaveAlone"), obj, "left as object");
		t.attr({
			'number': '15'
		});
		ok(t.attr("number") === 15, "converted to number");

	});


	test("basics value", function () {
		var Typer = can.Map.extend({
			define: {
				prop: { value: 'foo' }
			}
		});

		equal(new Typer().attr('prop'), "foo", "value is used as default value");


		var Typer2 = can.Map.extend({
			define: {
				prop: {
					value: function () {
						return [];
					},
					type: "*"
				}
			}
		});

		var t1 = new Typer2(),
			t2 = new Typer2();
		ok(t1.attr("prop") !== t2.attr("prop"), "different array instances");
		ok(can.isArray(t1.attr("prop")), "its an array");


	});

	test("basics Value", function () {

		var Typer = can.Map.extend({
			define: {
				prop: {
					Value: Array,
					type: "*"
				}
			}
		});

		var t1 = new Typer(),
			t2 = new Typer();
		ok(t1.attr("prop") !== t2.attr("prop"), "different array instances");
		ok(can.isArray(t1.attr("prop")), "its an array");


	});


	test("setter with no arguments and returns undefined does the default behavior, the setter is for side effects only", function () {

		var Typer = can.Map.extend({
			define: {
				prop: {
					set: function () {
						this.attr("foo", "bar");
					}
				}
			}
		});

		var t = new Typer();

		t.attr("prop", false);

		deepEqual(t.attr(), { foo: "bar", prop: false });


	});

	test("type happens before the set", function () {
		var MyMap = can.Map.extend({
			define: {
				prop: {
					type: "number",
					set: function (newValue) {
						equal(typeof newValue, "number", "got a number");
						return newValue + 1;
					}
				}
			}
		});

		var map = new MyMap();
		map.attr("prop", "5");

		equal(map.attr("prop"), 6, "number");
	});

	test("getter and setter work", function () {
		expect(5);
		var Paginate = can.Map.extend({
			define: {
				page: {
					set: function (newVal) {
						this.attr('offset', (parseInt(newVal) - 1) * this.attr('limit'));
					},
					get: function () {
						return Math.floor(this.attr('offset') / this.attr('limit')) + 1;
					}
				}
			}
		});

		var p = new Paginate({limit: 10, offset: 20});

		equal(p.attr("page"), 3, "page get right");

		p.bind("page", function (ev, newValue, oldValue) {
			equal(newValue, 2, "got new value event");
			equal(oldValue, 3, "got old value event");
		});


		p.attr("page", 2);

		equal(p.attr("page"), 2, "page set right");

		equal(p.attr("offset"), 10, "page offset set");


	});

	test("getter with initial value", function(){

		var compute = can.compute(1);

		var Grabber = can.Map.extend({
			define: {
				vals: {
					type: "*",
					Value: Array,
					get: function(current, setVal){
						if(setVal){
							current.push( compute() );
						}
						return current;
					}
				}
			}
		});

		var g = new Grabber();
		// This assertion doesn't mean much.  It's mostly testing
		// that there were no errors.
		equal(g.attr("vals").length,0,"zero items in array" );

	});


	test("serialize basics", function(){
		var MyMap = can.Map.extend({
			define: {
				name: {
					serialize: function(){
						return;
					}
				},
				locations: {
					serialize: false
				},
				locationIds: {
					get: function(){
						var ids = [];
						this.attr('locations').each(function(location){
							ids.push(location.id);
						});
						return ids;
					},
					serialize: function(locationIds){
						return locationIds.join(',');
					}
				},
				bared: {
					get: function(){
						return this.attr("name")+"+bar";
					},
					serialize: true
				},
				ignored: {
					get: function(){
						return this.attr("name")+"+ignored";
					}
				}
			}
		});

		var map = new MyMap({name: "foo"});
		map.attr("locations", [{id: 1, name: "Chicago"}, {id: 2, name: "LA"}]);
		equal(map.attr("locationIds").length, 2, "get locationIds");
		equal(map.attr("locationIds")[0], 1, "get locationIds index 0");
		equal(map.attr("locations")[0].id, 1, "get locations index 0");

		var serialized = map.serialize();
		equal(serialized.locations, undefined, "locations doesn't serialize");
		equal(serialized.locationIds, "1,2", "locationIds serializes");
		equal(serialized.name, undefined, "name doesn't serialize");

		equal(serialized.bared, "foo+bar", "true adds computed props");
		equal(serialized.ignored, undefined, "computed props are not serialized by default");

	});

	test("serialize context", function(){
		var context, serializeContext;
		var MyMap = can.Map.extend({
			define: {
				name: {
					serialize: function(obj){
						context = this;
						return obj;
					}
				}
			},
			serialize: function(){
				serializeContext = this;
				can.Map.prototype.serialize.apply(this, arguments);

			}
		});

		var map = new MyMap();
		map.serialize();
		equal(context, map);
		equal(serializeContext, map);
	});

	test("methods contexts", function(){
		var contexts = {};
		var MyMap = can.Map.extend({
			define: {
				name: {
					value: 'John Galt',

					get: function(obj){
						contexts.get = this;
						return obj;
					},

					remove: function(obj){
						contexts.remove = this;
						return obj;
					},

					set: function(obj){
						contexts.set = this;
						return obj;
					},

					serialize: function(obj){
						contexts.serialize = this;
						return obj;
					},

					type: function(val){
						contexts.type = this;
						return val;
					}
				}

			}
		});

		var map = new MyMap();
		map.serialize();
		map.removeAttr('name');

		equal(contexts.get, map);
		equal(contexts.remove, map);
		equal(contexts.set, map);
		equal(contexts.serialize, map);
		equal(contexts.type, map);
	});

	test("value generator is not called if default passed", function () {
		var TestMap = can.Map.extend({
			define: {
				foo: {
					value: function () {
						throw '"foo"\'s value method should not be called.';
					}
				}
			}
		});

		var tm = new TestMap({ foo: 'baz' });

		equal(tm.attr('foo'), 'baz');
	});

	test("Value generator can read other properties", function () {
		var Map = can.Map.extend({
			letters: 'ABC',
			numbers: [1, 2, 3],
			define: {
				definedLetters: {
					value: 'DEF'
				},
				definedNumbers: {
					value: [4, 5, 6]
				},
				generatedLetters: {
					value: function () {
						return 'GHI';
					}
				},
				generatedNumbers: {
					value: function () {
						return new can.List([7, 8, 9]);
					}
				},

				// Get prototype defaults
				firstLetter: {
					value: function () {
						return this.attr('letters').substr(0, 1);
					}
				},
				firstNumber: {
					value: function () {
						return this.attr('numbers.0');
					}
				},

				// Get defined simple `value` defaults
				middleLetter: {
					value: function () {
						return this.attr('definedLetters').substr(1, 1);
					}
				},
				middleNumber: {
					value: function () {
						return this.attr('definedNumbers.1');
					}
				},

				// Get defined `value` function defaults
				lastLetter: {
					value: function () {
						return this.attr('generatedLetters').substr(2, 1);
					}
				},
				lastNumber: {
					value: function () {
						return this.attr('generatedNumbers.2');
					}
				}
			}
		});

		var map = new Map();
		var prefix = 'Was able to read dependent value from ';

		equal(map.attr('firstLetter'), 'A',
			prefix + 'traditional can.Map style property definition');
		equal(map.attr('firstNumber'), 1,
			prefix + 'traditional can.Map style property definition');

		equal(map.attr('middleLetter'), 'E',
			prefix + 'define plugin style default property definition');
		equal(map.attr('middleNumber'), 5,
			prefix + 'define plugin style default property definition');
		
		equal(map.attr('lastLetter'), 'I',
			prefix + 'define plugin style generated default property definition');
		equal(map.attr('lastNumber'), 9,
			prefix + 'define plugin style generated default property definition');
	});

	test('default behaviors with "*" work for attributes', function() {
		expect(9);
		var DefaultMap = can.Map.extend({
			define: {
				someNumber: {
					value: '5'
				},
				'*': {
					type: 'number',
					serialize: function(value) {
						return '' + value;
					},
					set: function(newVal) {
						ok(true, 'set called');
						return newVal;
					},
					remove: function(currentVal) {
						ok(true, 'remove called');
						return false;
					}
				}
			}
		});

		var map = new DefaultMap(),
			serializedMap;

		equal(map.attr('someNumber'), 5, 'value of someNumber should be converted to a number');
		map.attr('number', '10'); // Custom set should be called
		equal(map.attr('number'), 10, 'value of number should be converted to a number');
		map.removeAttr('number'); // Custom removed should be called
		equal(map.attr('number'), 10, 'number should not be removed');

		serializedMap = map.serialize();

		equal(serializedMap.number, '10', 'number serialized as string');
		equal(serializedMap.someNumber, '5', 'someNumber serialized as string');
		equal(serializedMap['*'], undefined, '"*" is not a value in serialized object');
	});

	test('models properly serialize with default behaviors', function() {
		var DefaultMap = can.Map.extend({
			define: {
				name: {
					value: 'Alex'
				},
				shirt: {
					value: 'blue',
					serialize: true
				},
				'*': {
					serialize: false
				}
			}
		});
		var map = new DefaultMap({age: 10, name: 'John'}),
			serializedMap = map.serialize();

		equal(serializedMap.age, undefined, 'age doesn\'t exist');
		equal(serializedMap.name, undefined, 'name doesn\'t exist');
		equal(serializedMap.shirt, 'blue', 'shirt exists');
	});

	test("nested define", function() {
		var nailedIt = 'Nailed it';
		var Example = can.Map.extend({ }, {
		  define: {
		    name: {
		      value: nailedIt
		    }
		  }
		});

		var NestedMap = can.Map.extend({ }, {
		  define: {
		    isEnabled: {
		      value: true
		    },
		    test: {
		      Value: Example
		    },
		    examples: {
		      value: {
		        define: {
		          one: {
		            Value: Example
		          },
		          two: {
		            value: {
		              define: {
		                deep: {
		                  Value: Example
		                }
		              }
		            }
		          }
		        }
		      }
		    }
		  }
		});

		var nested = new NestedMap();

		// values are correct
		equal(nested.attr('test.name'), nailedIt);
		equal(nested.attr('examples.one.name'), nailedIt);
		equal(nested.attr('examples.two.deep.name'), nailedIt);

		// objects are correctly instanced
		ok(nested.attr('test') instanceof Example);
		ok(nested.attr('examples.one') instanceof Example);
		ok(nested.attr('examples.two.deep') instanceof Example);
	});

	test('Can make an attr alias a compute (#1470)', 9, function(){
		var computeValue = can.compute(1);
		var GetMap = can.Map.extend({
			define: {
				value: {
					set: function(newValue, setVal, setErr, oldValue){
						if(newValue.isComputed) {
							return newValue;
						}
						if(oldValue && oldValue.isComputed) {
							oldValue(newValue);
							return oldValue;
						}
						return newValue;
					},
					get: function(value){
						return value && value.isComputed ? value() : value;
					}
				}
			}
		});
		
		var getMap = new GetMap();
		
		getMap.attr("value", computeValue);
		
		equal(getMap.attr("value"), 1);

		var bindCallbacks = 0;
		
		getMap.bind("value", function(ev, newVal, oldVal){
			//debugger;
			switch(bindCallbacks) {
				case 0:
					equal(newVal, 2, "0 - bind called with new val");
					equal(oldVal, 1, "0 - bind called with old val");
					break;
				case 1:
					equal(newVal, 3, "1 - bind called with new val");
					equal(oldVal, 2, "1 - bind called with old val");
					break;
				case 2:
					equal(newVal, 4, "2 - bind called with new val");
					equal(oldVal, 3, "2 - bind called with old val");
					break;
			}
			
			
			bindCallbacks++;
		});
		
		// Try updating the compute's value
		computeValue(2);
		
		// Try setting the value of the property
		getMap.attr("value", 3);
		
		equal(getMap.attr("value"), 3, "read value is 3");
		equal(computeValue(), 3, "the compute value is 3");
		
		// Try setting to a new comptue
		var newComputeValue = can.compute(4);
		
		getMap.attr("value", newComputeValue);
		
	});

	test('setting a value of a property with type "compute" triggers change events', function () {

		var handler;
		var message = 'The change event passed the correct {prop} when set with {method}';
		
		var createChangeHandler = function (expectedOldVal, expectedNewVal, method) {
			return function (ev, newVal, oldVal) {
				var subs = { prop: 'newVal', method: method };
				equal(newVal, expectedNewVal, can.sub(message, subs));
				subs.prop = 'oldVal';
				equal(oldVal, expectedOldVal, can.sub(message, subs));
			};
		};

		var ComputableMap = can.Map.extend({
			define: {
				computed: {
					type: 'compute',
				}
			}
		});

		var computed = can.compute(0);

		var m1 = new ComputableMap({
			computed: computed
		});

		equal(m1.attr('computed'), 0, 'm1 is 1');

		handler = createChangeHandler(0, 1, ".attr('computed', newVal)");
		

		handler = createChangeHandler(0, 1, ".attr('computed', newVal)");
		m1.bind('computed', handler);
		m1.attr('computed', 1);
		m1.unbind('computed', handler);

		handler = createChangeHandler(1, 2, "computed()");
		m1.bind('computed', handler);
		computed(2);
		m1.unbind('computed', handler);
	});

	test('replacing the compute on a property with type "compute"', function () {
		var compute1 = can.compute(0);
		var compute2 = can.compute(1);

		var ComputableMap = can.Map.extend({
			define: {
				computable: {
					type: 'compute'
				}
			}
		});

		var m = new ComputableMap();

		m.attr('computable', compute1);


		equal(m.attr('computable'), 0, 'compute1 readable via .attr()');

		m.attr('computable', compute2);


		equal(m.attr('computable'), 1, 'compute2 readable via .attr()');
	});

	// The old attributes plugin interferes severly with this test.
	// TODO remove this condition when taking the plugins out of the main repository
	test('value and get (#1521)', function () {
		var MyMap = can.Map.extend({
			define: {
				data: {
					value: function () {
						return new can.List(['test']);
					}
				},
				size: {
					value: 1,
					get: function (val) {
						var list = this.attr('data');
						var length = list.attr('length');
						return val + length;
					}
				}
			}
		});

		var map = new MyMap({});
		equal(map.attr('size'), 2);
	});

	
	test("One event on getters (#1585)", function(){

		var AppState = can.Map.extend({
			define: {
				person: {
					get: function(lastSetValue, setAttrValue) {
						if (lastSetValue) {
							return lastSetValue;
						} else if (this.attr("personId")) {
							setAttrValue( new can.Map({name: "Jose", id: 5}) );
						} else {
							return null;
						}
					}
				}
			}
		});
		
		var appState = new AppState();
		var personEvents = 0;
		appState.bind("person", function(ev, person) {
			personEvents++;
		});
		
		appState.attr("personId", 5);
		

		appState.attr("person", new can.Map({
			name: "Julia"
		}));

			
		equal(personEvents,2);
	});

	test('Can read a defined property with a set/get method (#1648)', function () {
		// Problem: "get" is not passed the correct "lastSetVal"
		// Problem: Cannot read the value of "foo"

		var Map = can.Map.extend({
			define: {
				foo: {
					value: '',
					set: function (setVal) {
						return setVal;
					},
					get: function (lastSetVal) {
						return lastSetVal;
					}
				}
			}
		});

		var map = new Map();

		equal(map.attr('foo'), '', 'Calling .attr(\'foo\') returned the correct value');

		map.attr('foo', 'baz');

		equal(map.attr('foo'), 'baz', 'Calling .attr(\'foo\') returned the correct value');
	});

	test('Can bind to a defined property with a set/get method (#1648)', 3, function () {
		// Problem: "get" is not called before and after the "set"
		// Problem: Function bound to "foo" is not called
		// Problem: Cannot read the value of "foo"

		var Map = can.Map.extend({
			define: {
				foo: {
					value: '',
					set: function (setVal) {
						return setVal;
					},
					get: function (lastSetVal) {
						return lastSetVal;
					}
				}
			}
		});

		var map = new Map();

		map.bind('foo', function () {
			ok(true, 'Bound function is called');
		});
		
		equal(map.attr('foo'), '', 'Calling .attr(\'foo\') returned the correct value');

		map.attr('foo', 'baz');

		equal(map.attr('foo'), 'baz', 'Calling .attr(\'foo\') returned the correct value');
	});
	
	
	test("type converters handle null and undefined in expected ways (1693)", function () {

		var Typer = can.Map.extend({
			define: {
				date: {  type: 'date' },
				string: {type: 'string'},
				number: {  type: 'number' },
				'boolean': {  type: 'boolean' },
				htmlbool: {  type: 'htmlbool' },
				leaveAlone: {  type: '*' }
			}
		});

		var t = new Typer().attr({
			date: undefined,
			string: undefined,
			number: undefined,
			'boolean': undefined,
			htmlbool: undefined,
			leaveAlone: undefined
		});

		equal(t.attr("date"), undefined, "converted to date");

		equal(t.attr("string"), undefined, "converted to string");

		equal(t.attr("number"), undefined, "converted to number");

		equal(t.attr("boolean"), false, "converted to boolean");

		equal(t.attr("htmlbool"), false, "converted to htmlbool");

		equal(t.attr("leaveAlone"), undefined, "left as object");
		
		t = new Typer().attr({
			date: null,
			string: null,
			number: null,
			'boolean': null,
			htmlbool: null,
			leaveAlone: null
		});

		equal(t.attr("date"), null, "converted to date");

		equal(t.attr("string"), null, "converted to string");

		equal(t.attr("number"), null, "converted to number");

		equal(t.attr("boolean"), false, "converted to boolean");

		equal(t.attr("htmlbool"), false, "converted to htmlbool");

		equal(t.attr("leaveAlone"), null, "left as object");

	});
	
	test('Initial value does not call getter', function() {
		expect(0);

		var Map = can.Map.extend({
			define: {
				count: {
					get: function(lastVal) {
						ok(false, 'Should not be called');
						return lastVal;
					}
				}
			}
		});

		new Map({ count: 100 });
	});
});
