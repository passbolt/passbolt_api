steal('can/construct', 'steal-qunit', function () {
	/* global Foo, Car, Bar */
	QUnit.module('can/construct', {
		setup: function () {
			var Animal = this.Animal = can.Construct({
				count: 0,
				test: function () {
					return this.match ? true : false;
				}
			}, {
				init: function () {
					this.constructor.count++;
					this.eyes = false;
				}
			});
			var Dog = this.Dog = this.Animal({
				match: /abc/
			}, {
				init: function () {
					Animal.prototype.init.apply(this, arguments);
				},
				talk: function () {
					return 'Woof';
				}
			});
			this.Ajax = this.Dog({
				count: 0
			}, {
				init: function (hairs) {
					Dog.prototype.init.apply(this, arguments);
					this.hairs = hairs;
					this.setEyes();
				},
				setEyes: function () {
					this.eyes = true;
				}
			});
		}
	});
	test('inherit', function () {
		var Base = can.Construct({});
		ok(new Base() instanceof can.Construct);
		var Inherit = Base({});
		ok(new Inherit() instanceof Base);
	});
	test('Creating', function () {
		new this.Dog();
		var a1 = new this.Animal();
		new this.Animal();
		var ajax = new this.Ajax(1000);
		equal(2, this.Animal.count, 'right number of animals');
		equal(1, this.Dog.count, 'right number of animals');
		ok(this.Dog.match, 'right number of animals');
		ok(!this.Animal.match, 'right number of animals');
		ok(this.Dog.test(), 'right number of animals');
		ok(!this.Animal.test(), 'right number of animals');
		equal(1, this.Ajax.count, 'right number of animals');
		equal(2, this.Animal.count, 'right number of animals');
		equal(true, ajax.eyes, 'right number of animals');
		equal(1000, ajax.hairs, 'right number of animals');
		ok(a1 instanceof this.Animal);
		ok(a1 instanceof can.Construct);
	});
	test('new instance', function () {
		var d = this.Ajax.newInstance(6);
		equal(6, d.hairs);
	});
	test('namespaces', function () {
		var fb = can.Construct.extend('Foo.Bar');
		can.Construct('Todo', {}, {});
		ok(Foo.Bar === fb, 'returns class');
		equal(fb.shortName, 'Bar', 'short name is right');
		//!steal-remove-start
		if (can.dev && fb.name) {
			equal(fb.name, 'Bar', 'short name is right');
		}
		//!steal-remove-end

		equal(fb.fullName, 'Foo.Bar', 'fullName is right');
	});
	test('setups', function () {
		var order = 0,
			staticSetup, staticSetupArgs, staticInit, staticInitArgs, protoSetup, protoInitArgs, protoInit, staticProps = {
				setup: function () {
					staticSetup = ++order;
					staticSetupArgs = arguments;
					return ['something'];
				},
				init: function () {
					staticInit = ++order;
					staticInitArgs = arguments;
				}
			}, protoProps = {
				setup: function (name) {
					protoSetup = ++order;
					return ['Ford: ' + name];
				},
				init: function () {
					protoInit = ++order;
					protoInitArgs = arguments;
				}
			};
		can.Construct.extend('Car', staticProps, protoProps);
		new Car('geo');
		equal(staticSetup, 1);
		equal(staticInit, 2);
		equal(protoSetup, 3);
		equal(protoInit, 4);
		deepEqual(can.makeArray(staticInitArgs), ['something']);
		deepEqual(can.makeArray(protoInitArgs), ['Ford: geo']);
		deepEqual(can.makeArray(staticSetupArgs), [
			can.Construct,
			'Car',
			staticProps,
			protoProps
		], 'static construct');
		//now see if staticSetup gets called again ...
		Car.extend('Truck');
		equal(staticSetup, 5, 'Static setup is called if overwriting');
	});
	test('Creating without extend', function () {
		can.Construct('Bar', {
			ok: function () {
				ok(true, 'ok called');
			}
		});
		new Bar()
			.ok();
		Bar('Foo', {
			dude: function () {
				ok(true, 'dude called');
			}
		});
		new Foo()
			.dude(true);
	});
	
	//!steal-remove-start
	if (can.dev) {
		test('console warning if extend is not used without new (#932)', function () {
			
			var oldlog = can.dev.warn;
			can.dev.warn = function (text) {
				ok(text, "got a message");
				can.dev.warn = oldlog;
			};
			var K1 = can.Construct({});
			K1({});
		});
	}
	//!steal-remove-end
	
	test("setup called with original arguments", function(){
		var o1 = {
			setup: function(base, arg1, arg2){
				equal(o1, arg1, "first argument is correct");
				equal(o2, arg2, "second argument is correct");
			}
		};
		var o2 = {};
		can.Construct.extend(o1,o2);
	});
});
