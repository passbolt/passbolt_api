steal('can/control/modifier/key', 'steal-qunit', function () {
	QUnit.module('can/control/modifier/key');
	test('key down triggered', 1, function () {
		var Tester = can.Control({
			'keydown': function () {},
			'keydown:(shift+p)': function (elm, ev) {
				ok('key event pressed!');
			}
		});
		new Tester(document.body);
		// trigger event
		var e = jQuery.Event('keydown');
		e.which = 80;
		e.keyCode = 80;
		e.shiftKey = true;
		e.altKey = false;
		e.charCode = 0;
		e.ctrlKey = false;
		e.metaKey = false;
		$(document.body)
			.trigger(e);
	});
});
