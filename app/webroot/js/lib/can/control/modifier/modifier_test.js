steal('can/util', 'can/control/modifier', 'can/util/event.js', 'steal-qunit', function (can) {
	module('can/control/modifier');
	test('nested selectors', function () {
		var paw, tail;
		var controllerClass = can.Control({
			'.cat .paw click': function () {
				paw++;
			},
			'.cat .tail click': function () {
				tail++;
			}
		});
		can.$('#qunit-fixture')[0].innerHTML = '<div class=\'cat\'><div class=\'paw\'></div><div class=\'tail\'></div></div>';
		new controllerClass(can.$('#qunit-fixture'));
		paw = 0;
		tail = 0;
		can.trigger(can.$('.tail'), 'click');
		equal(tail, 1);
		equal(paw, 0);
	});
	test('pluginName', function () {
		stop();
		var controllerClass = can.Control({
			defaults: {
				binder: undefined
			}
		}, {
			' click:debounce(30)': function () {
				ok(this instanceof can.Control, 'Debounced function has the correct context.');
				fooToTheBar = true;
				run++;
			},
			'bar:debounce(30)': function () {
				run2++;
			},
			'{binder} click:debounce(50)': function () {
				run2++;
			},
			'span click:debounce(50)': function () {
				run3++;
			}
		});
		can.$('#qunit-fixture')[0].innerHTML = '<div id="foo"><span>Test</span></div><div id="bar"></div>';
		/**/
		var controller1 = new controllerClass('#foo', {
			binder: can.$(document.body)
		}),
			run = 0,
			run2 = 0,
			run3 = 0,
			fooToTheBar;
		new controllerClass('#bar', {
			binder: can.$(document.body)
		});
		// Do a bunch of clicks!
		can.trigger(can.$('#foo'), 'click');
		can.trigger(can.$('#foo span'), 'click');
		can.trigger(can.$('#bar'), 'click');
		can.trigger(can.$('#foo'), 'click');
		can.trigger(can.$('#bar'), 'click');
		can.trigger(can.$('#foo'), 'click');
		can.trigger(can.$('#bar'), 'click');
		// Make sure foo is still undefined (should be > 30ms before its defined)
		ok(!fooToTheBar, '`fooToTheBar` is undefined.');
		ok('bar' in controller1, 'Method name gets aliased correctly');
		controller1.bar();
		controller1.bar();
		controller1.bar();
		controller1.bar();
		// Check if
		setTimeout(function () {
			ok(fooToTheBar, '`fooToTheBar` is true.');
			equal(run, 2, '`run` is 2');
			equal(run2, 1, '`run2` is 1');
			// Do a bunch more clicks!
			can.trigger(can.$('#foo'), 'click');
			can.trigger(can.$('#bar'), 'click');
			can.trigger(can.$('#foo'), 'click');
			can.trigger(can.$('#bar'), 'click');
			can.trigger(can.$('#foo'), 'click');
			can.trigger(can.$('#bar'), 'click');
			can.trigger(can.$(document.body), 'click');
			setTimeout(function () {
				equal(run3, 1, '`run3` is 1');
				equal(run, 4, '`run` is 4');
				can.remove(can.$('#foo'));
				start();
			}, 40);
		}, 40);
	});

	test('Modifiers should work with objects that don\'t implement delegate (#754)', function() {
		stop();
		var some_event_fired = false,
				some_event_debounce_fired = false;

		// Create event-dispatching class
		var some_object = {
			bind: can.bind,
			unbind: can.unbind,
			dispatch: can.dispatch
		};

		// Create control listening for object events
		var SomeControl = can.Control({
			setObject: function(object) {
				this.options.object = object;
				this.on();
			},
			"{object} some_event": function() {
				some_event_fired = true;
			},
			"{object} some_event:debounce(50)": function() {
				some_event_debounce_fired = true;
			}
		});

		var some_control = new SomeControl(document.body);
		some_control.setObject(some_object);

		// Fire the event
		some_object.dispatch("some_event");
		equal(some_event_fired, true, "Basic event handler fired");
		setTimeout(function() {
			equal(some_event_debounce_fired, true, "Debounced event handler fired");
			start();
		}, 250);
	});
});
