steal("can/control/plugin", "steal-qunit", function () {
	if (!window.jQuery) {
		return;
	}

	/* global My */
	QUnit.module('can/control/plugin');
	test('pluginName', function () {
		expect(8);
		can.Control('My.TestPlugin', {
			pluginName: 'my_plugin'
		}, {
			init: function (el, ops) {
				ok(true, 'Init called');
				equal(ops.testop, 'testing', 'Test argument set');
			},
			method: function (arg) {
				ok(true, 'Method called');
				equal(arg, 'testarg', 'Test argument passed');
			},
			update: function (options) {
				ok(true, 'Update called');
			}
		});
		var ta = can.$('<div/>')
			.addClass('existing_class')
			.appendTo($('#qunit-fixture'));
		ta.my_plugin({
			testop: 'testing'
		});
		// Init
		ok(ta.hasClass('my_plugin'), 'Should have class my_plugin');
		ta.my_plugin();
		// Update
		ta.my_plugin('method', 'testarg');
		// method()
		ta.control()
			.destroy();
		// destroy
		ok(!ta.hasClass('my_plugin'), 'Shouldn\'t have class my_plugin after being destroyed');
		ok(ta.hasClass('existing_class'), 'Existing class should still be there');
	});
	test('.control(), .controls() and _fullname', function () {
		expect(3);
		can.Control('My.TestPlugin', {});
		var ta = can.$('<div/>')
			.appendTo($('#qunit-fixture'));
		ok(ta.my_test_plugin, 'Converting Control name to plugin name worked');
		ta.my_test_plugin();
		equal(ta.controls()
			.length, 1, '.controls() returns one instance');
		ok(ta.control() instanceof My.TestPlugin, 'Control is instance of test plugin');
	});
	test('update', function () {
		can.Control({
			pluginName: 'updateTest'
		}, {});
		var ta = can.$('<div/>')
			.addClass('existing_class')
			.appendTo($('#qunit-fixture'));
		ta.updateTest();
		// Init
		ta.updateTest({
			testop: 'testing'
		});
		equal(ta.control()
			.options.testop, 'testing', 'Test option has been extended properly');
	});
	test('calling methods', function () {
		can.Control({
			pluginName: 'callTest'
		}, {
			returnTest: function () {
				return 'Hi ' + this.name;
			},
			setName: function (name) {
				this.name = name;
			}
		});
		var ta = can.$('<div/>')
			.appendTo($('#qunit-fixture'));
		ta.callTest();
		ok(ta.callTest('setName', 'Tester') instanceof jQuery, 'Got jQuery element as return value');
		equal(ta.callTest('returnTest'), 'Hi Tester', 'Got correct return value');
	});
	test('always use pluginName first in .control(name) (#448)', 4, function () {
		can.Control('SomeName', {
			pluginName: 'someTest'
		}, {});
		can.Control({
			pluginName: 'otherTest'
		}, {});
		var ta = can.$('<div/>')
			.appendTo($('#qunit-fixture'));
		ta.someTest();
		ta.otherTest();
		var control = ta.control('someTest');
		ok(control, 'Got a control from pluginName');
		equal(control.constructor.pluginName, 'someTest', 'Got correct control');
		control = ta.control('otherTest');
		ok(control, 'Got a control from pluginName');
		equal(control.constructor.pluginName, 'otherTest', 'Got correct control');
	});
});
