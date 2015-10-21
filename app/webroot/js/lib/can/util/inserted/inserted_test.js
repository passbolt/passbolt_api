steal('can/util/inserted', 'steal-qunit', function () {
	module('can/util/inserted');
	if (window.jQuery) {
		test('jquery', function () {
			var el = $('<div>');
			el.bind('inserted', function () {
				ok(true, 'inserted called');
			});
			$('#qunit-fixture')
				.append(el);
		});
	}
	
	if(window.Zepto) {
		test('zepto', function () {
			expect(1);
			var el = $('<div>');
			
			el.bind('inserted', function () {
				ok(true, 'inserted called');
			});
			
			$('#qunit-fixture').html(el);
		});
	}
	
});
