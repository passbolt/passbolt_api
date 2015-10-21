/*global School*/
steal("can/map/setter", "can/test", "steal-qunit", function () {
	QUnit.module('can/map/setter');
	test('setter testing works', function () {
		var Contact = can.Map({
			setBirthday: function (raw) {
				if (typeof raw === 'number') {
					return new Date(raw);
				} else if (raw instanceof Date) {
					return raw;
				}
			}
		});
		var date = new Date(),
			contact = new Contact({
				birthday: date.getTime()
			});
		// set via constructor
		equal(contact.birthday.getTime(), date.getTime(), 'set as birthday');
		// set via attr method
		date = new Date();
		contact.attr('birthday', date.getTime());
		equal(contact.birthday.getTime(), date.getTime(), 'set via attr');
		// set via attr method w/ multiple attrs
		date = new Date();
		contact.attr({
			birthday: date.getTime()
		});
		equal(contact.birthday.getTime(), date.getTime(), 'set as birthday');
	});
	test('error binding', 1, function () {
		can.Map('School', {
			setName: function (name, success, error) {
				if (!name) {
					error('no name');
				}
				return error;
			}
		});
		var school = new School();
		school.bind('error', function (ev, attr, error) {
			equal(error, 'no name', 'error message provided');
		});
		school.attr('name', '');
	});
	test('asyncronous setting', function () {
		var Meyer = can.Map({
			setName: function (newVal, success) {
				setTimeout(function () {
					success(newVal + ' Meyer');
				}, 1);
			}
		});
		stop();
		var me = new Meyer();
		me.bind('name', function (ev, newVal) {
			equal(newVal, 'Justin Meyer');
			equal(me.attr('name'), 'Justin Meyer');
			start();
		});
		me.attr('name', 'Justin');
	});
	
	test('setter function values are automatically batched (#815)', function(){
		var Mapped = can.Map.extend({
			setFoo: function(newValue){
				this.attr("zed","ted");
				return newValue;
			}
		});
		
		var map = new Mapped(),
			batchNum;
		
		map.bind("zed", function(ev){
			batchNum = ev.batchNum;
			ok(batchNum, "zed event is batched");
		});
		map.bind("foo", function(ev){
			equal(batchNum, ev.batchNum, "batchNums are the same");
		});
			
		map.attr("foo","bar");
		
	});
	
	
	//!steal-remove-start
	if (can.dev) {
		test('setter function warns if a timeout did not happen (#808)', function () {
			stop();
			var oldlog = can.dev.warn;
			can.dev.warnTimeout = 10;
			can.dev.warn = function (text) {
				ok(text, "got a message");
				can.batch.stop(true);
				can.dev.warn = oldlog;
				start();
			};
			var Mapped = can.Map.extend({
				setFoo: function(newValue){}
			});
		
			var map = new Mapped();
			map.attr("foo", 1);
			
		});
		
		test('setter function does not warn if setter is called back quickly (#808)', function () {
			stop();
			expect(1);
			var oldlog = can.dev.warn;
			can.dev.warnTimeout = 100;
			var firstMsg = false;
			can.dev.warn = function (text) {
				// first warn is not the relevant message, there is a deprecation warning we need to ignore
				if(!firstMsg) {
					return;
				}
				ok(false, "got a message");
				start();
			};
			var Mapped = can.Map.extend({
				setFoo: function(newValue, setter){
					setTimeout(function(){
						setter("BAR");
					},10);
				}
			});
		
			var map = new Mapped();
			map.attr("foo", 1);
			map.bind("foo", function(ev, newVal){
				equal(newVal, "BAR", "new val set");
				start();
				can.dev.warn = oldlog;
			});
			
		});
		
		
	}
	//!steal-remove-end

});
