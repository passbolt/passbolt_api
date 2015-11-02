steal('can/construct', 'funcunit', function () {

	module("mad.core", {
		// runs before each test
		setup: function () {},
		// runs after each test
		teardown: function () {}
	});

	/* *****************************************************************************
	 * Test the decorator feature
	 **************************************************************************** */

	can.Construct('mad.test.ClassUnitToDecorateTest', {
		'staticVar': null,
		'staticFunc': function () {
			return 'I am a simple static function';
		}
	}, {
		'instanceFunction': function () {
			return 'I am a simple prototype function';
		},
		'toDecorate': function () {
			return 'I am the function to decorate';
		}
	});

	mad.test.DecoratorLvl1 = {
		'toDecorate': function () {
			return this._super() + ' which has been decorated';
		},
		'decoratorLvl1Func': function () {
			return 'I am a function of the decorator lvl 1';
		}
	};

	mad.test.DecoratorLvl2 = {
		'toDecorate': function () {
			return '[---NinjaStyle--- ' + this._super() + ' ---NinjaStyle---]';
		},
		'decoratorLvl1Func': function () {
			return this._super() + ' decorated by the decorator lvl 2';
		},
		'decoratorLvl2Func': function () {
			return 'I am a function of the decorator lvl 2';
		}
	};

	test('Class.decorate : Prototype properties of the decorator lvl1 class have been added to the instance to decorate', function () {
		var i1 = new mad.test.ClassUnitToDecorateTest();
		i1.decorate('mad.test.DecoratorLvl1');
		ok(typeof i1.toDecorate != 'undefined', 'The original function toDecorate still exists');
		ok(typeof i1.decoratorLvl1Func != 'undefined', 'The function decoratorLvl1Func from the decorator Decorator lvl1 has been added');
	});

	test('Class.decorate : Prototype properties of the decorator lvl1 are working like native prototype properties', function () {
		var i1 = new mad.test.ClassUnitToDecorateTest();
		i1.decorate('mad.test.DecoratorLvl1');
		equal(i1.toDecorate(), 'I am the function to decorate which has been decorated');
		equal(i1.decoratorLvl1Func(), 'I am a function of the decorator lvl 1');
	});

	test('Class.decorate : Prototype properties of the decorator lvl2 class have been added to the instance to decorate', function () {
		var i1 = new mad.test.ClassUnitToDecorateTest();
		i1.decorate('mad.test.DecoratorLvl1').decorate('mad.test.DecoratorLvl2');
		ok(typeof i1.decoratorLvl2Func != 'undefined', 'The function decoratorLvl2Func from the decorator Decorator lvl2 has been added');
	});

	test('Class.decorate : Prototype properties of the decorator lvl2 are working like native prototype properties', function () {
		var i1 = new mad.test.ClassUnitToDecorateTest();
		i1.decorate('mad.test.DecoratorLvl1').decorate('mad.test.DecoratorLvl2');
		equal(i1.toDecorate(), '[---NinjaStyle--- I am the function to decorate which has been decorated ---NinjaStyle---]');
		equal(i1.decoratorLvl1Func(), 'I am a function of the decorator lvl 1 decorated by the decorator lvl 2');
		equal(i1.decoratorLvl2Func(), 'I am a function of the decorator lvl 2');
	});

	/* *****************************************************************************
	 * Test the augment feature
	 **************************************************************************** */

	can.Construct('mad.test.ClassToAugment', /** @static */ {
		'staticVar': null,
		'staticFunc': function () {
			return 'I am a simple static function';
		}
	}, /** @prototype */ {
		'instanceVar1': null,
		'multipleConstructorCounter': 0,
		'init': function () {
			this.multipleConstructorCounter++;
		},
		'instanceFunction': function () {
			return 'I am a simple prototype function';
		}
	});

	can.Construct('mad.test.Augmentator', /** @static */ {
		'augmentedStaticVar': 'I am a static variable',
		'augmentedStaticFunc': function () {
			return 'I am a simple static function which augments the Class';
		}
	}, /** @prototype */ {
		'augmentedVar': 'I am an augmented variable',
		'init': function () {
			this.multipleConstructorCounter++;
		},
		'augmentedFunc': function () {
			return 'I am a simple prototype function which augments the Class';
		},
		'useInstanceVar': function () {
			this.instanceVar1 = 'Instance variable manipuled from the augmented function';
		},
		'useInstanceFunc': function () {
			return this.instanceFunction() + ' called from an augmented function';
		}
	});

	// Augment a class to test
	mad.test.ClassToAugment.augment('mad.test.Augmentator');

	test('Class.augment : Prototype properties of the augmented class have been added to the class to augment', function () {
		//variables are present
		ok(typeof mad.test.ClassToAugment.prototype.augmentedVar != 'undefined', 'The class has been augmented with the instance variable mad.test.Augmentator.prototype.augmentedVar');
		//functions are present
		ok(typeof mad.test.ClassToAugment.prototype.augmentedFunc != 'undefined', 'The class has been augmented with the instance function mad.test.Augmentator.prototype.augmentedFunc');
		ok(typeof mad.test.ClassToAugment.prototype.useInstanceVar != 'undefined', 'The class has been augmented with the instance function mad.test.Augmentator.prototype.useInstanceVar');
		ok(typeof mad.test.ClassToAugment.prototype.useInstanceFunc != 'undefined', 'The class has been augmented with the instance function mad.test.Augmentator.prototype.useInstanceFunc');
	});

	test('Class.augment : Static properties of the augmented class have been added to the class to augment', function () {
		//variables are present
		ok(typeof mad.test.ClassToAugment.augmentedStaticVar != 'undefined', 'The class has been augmented with the static variable mad.test.Augmentator.staticVar');
		//functions are present
		ok(typeof mad.test.ClassToAugment.augmentedStaticFunc != 'undefined', 'The class has been augmented with the static function mad.test.Augmentator.staticFunc');
	});

	test('Class.augment : Augmented prototype properties are working like native prototype properties', function () {
		var i1 = new mad.test.ClassToAugment();

		// Multiple constructors execution
		equal(i1.multipleConstructorCounter, 2, 'The constructor is called in each class');

		// Augmented prototype variable
		equal(i1.augmentedVar, 'I am an augmented variable', 'Augmented prototype variable ok');

		// Simple augmented function work like native
		equal(i1.augmentedFunc(), 'I am a simple prototype function which augments the Class', 'Simple augmented prototype function ok');

		// Augmented function can manipulate instance's variables
		i1.useInstanceVar();
		equal(i1.instanceVar1, 'Instance variable manipuled from the augmented function', 'Augmented function can manipulate instance\'s variables');

		// Augmented function can manipulate instance's functions
		equal(i1.useInstanceFunc(), 'I am a simple prototype function called from an augmented function', 'Augmented function can manipulate instance\'s functions');

		i1 = null;
	});

	test('Class.augment : Augmented static properties are working like native static properties', function () {
		// Augmented static variable
		equal(mad.test.ClassToAugment.augmentedStaticVar, 'I am a static variable', 'Augmented static variable ok');

		// Simple augmented function work like native
		equal(mad.test.ClassToAugment.augmentedStaticFunc(), 'I am a simple static function which augments the Class', 'Simple augmented static function ok');
	});

});