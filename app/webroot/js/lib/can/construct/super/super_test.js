steal("can/construct/super", "steal-qunit", function () {
	QUnit.module('can/construct/super');
	test('prototype super', function () {
		var A = can.Construct({
			init: function (arg) {
				this.arg = arg + 1;
			},
			add: function (num) {
				return this.arg + num;
			}
		});
		var B = A({
			init: function (arg) {
				this._super(arg + 2);
			},
			add: function (arg) {
				return this._super(arg + 1);
			}
		});
		var b = new B(1);
		equal(b.arg, 4);
		equal(b.add(2), 7);
	});
	test('static super', function () {
		var First = can.Construct({
			raise: function (num) {
				return num;
			}
		}, {});
		var Second = First({
			raise: function (num) {
				return this._super(num) * num;
			}
		}, {});
		equal(Second.raise(2), 4);
	});
	test('findAll super', function () {
		var Parent = can.Construct({
			findAll: function () {
				equal(this.shortName, 'child');
				return new can.Deferred();
			},
			shortName: 'parent'
		}, {});
		var Child = Parent({
			findAll: function () {
				return this._super();
			},
			shortName: 'child'
		}, {});
		stop();
		expect(1);
		Child.findAll({});
		start();
	});
	//!steal-remove-start
	// To avoid JSHint complaining about the missing getter
	/* jshint ignore:start */
	if(Object.getOwnPropertyDescriptor) {
		test("_super supports getters and setters", function () {
			var Person = can.Construct.extend({
				get age() {
					return 42;
				},

				set name(value) {
					this._name = value;
				},

				get name() {
					return this._name;
				}
			});

			var OtherPerson = Person.extend({
				get age() {
					return this._super() + 8;
				},

				set name(value) {
					this._super(value + '_super');
				}
			});

			var test = new OtherPerson();
			test.base = 2;
			equal(test.age, 50, 'Getter and _super works');
			test.name = 'David';
			equal(test.name, 'David_super', 'Setter ran');
		});
	}
	/* jshint ignore:end */
	//!steal-remove-end
});
