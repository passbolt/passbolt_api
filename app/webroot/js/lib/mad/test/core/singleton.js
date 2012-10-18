steal('funcunit', function () {

	module("mad.core", {
		// runs before each test
		setup: function () {},
		// runs after each test
		teardown: function () {}
	});

	test('Singleton : Check that a singleton is instanciated once', function () {
		mad.core.Singleton.extend('mad.test.singleton.SingletonUnitTest', {}, {});
		var s1 = mad.test.singleton.SingletonUnitTest.singleton();
		var s2 = mad.test.singleton.SingletonUnitTest.singleton();
		ok(s1 === s1, 'The same instance is well identify by javascript');
		ok(s1 === s2, 'The two instances of the singleton class are the same');
		delete mad.test.singleton.SingletonUnitTest;
	});

	test('Singleton : Check that the singleton is abstract', function () {
		raises(function () {
			new mad.core.Singleton();
		}, mad.error.CallAbstractFunctionException, mad.error.CallAbstractFunctionException.message);
	});

	test('Singleton : Check that the singleton constructor cannot be called (almost)', function () {
		mad.core.Singleton.extend('mad.test.singleton.SingletonUnitTest', {}, {});
		raises(function () {
			new mad.test.singleton.SingletonUnitTest();
		}, mad.error.CallPrivateFunctionException, mad.error.CallPrivateFunctionException.message);
		delete mad.test.singleton.SingletonUnitTest;
	});

	test('Singleton : Check instance variables of singleton instance', function () {
		mad.core.Singleton.extend('mad.test.singleton.SingletonUnitTest1', {
			'instanceVar1': null,
			'init': function () {
				this._super();
				this.instanceVar1 = 'instanceVar1';
			}
		});
		mad.core.Singleton.extend('mad.test.singleton.SingletonUnitTest2', {
			'instanceVar2': null,
			'init': function () {
				this._super();
				this.instanceVar2 = 'instanceVar2';
			}
		});
		var s1 = mad.test.singleton.SingletonUnitTest1.singleton();
		var s2 = mad.test.singleton.SingletonUnitTest2.singleton();
		equal(s1.instanceVar1, 'instanceVar1', 'Expected value for instance variables of singleton 1 ');
		equal(s2.instanceVar2, 'instanceVar2', 'Expected value for instance variables of singleton 2 ');
		delete mad.test.singleton.SingletonUnitTest1;
		delete mad.test.singleton.SingletonUnitTest2;
	});

	test('Singleton : Check static variables of singleton instance', function () {
		mad.core.Singleton.extend('mad.test.singleton.SingletonUnitTest1', {
			'constStaticVar1': 'constStaticVar1'
		}, {});
		mad.core.Singleton.extend('mad.test.singleton.SingletonUnitTest2', {
			'constStaticVar2': 'constStaticVar2'
		}, {});
		var s1 = mad.test.singleton.SingletonUnitTest1.singleton();
		var s2 = mad.test.singleton.SingletonUnitTest2.singleton();

		equal(mad.test.singleton.SingletonUnitTest1.constStaticVar1, 'constStaticVar1', 'Expected value for static variables of singleton 1 ');
		equal(mad.test.singleton.SingletonUnitTest2.constStaticVar2, 'constStaticVar2', 'Expected value for static variables of singleton 2 ');
		delete mad.test.singleton.SingletonUnitTest1;
		delete mad.test.singleton.SingletonUnitTest2;
	});

});