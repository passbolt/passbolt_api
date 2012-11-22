steal('funcunit/qunit', 'funcunit/syn', 'jquery/event/key', function(QUnit, Syn) {
	
module('jquery/event/key');

test("type some things", function(){
	$("#qunit-test-area").append("<input id='key' />")
	var keydown, keypress, keyup;
	$('#key').keydown(function(ev){
		keydown = ev.keyName();
	}).keypress(function(ev){
		keypress = ev.keyName();
	}).keyup(function(ev){
		keyup = ev.keyName();
	});
	
	stop();
	
	Syn.key("a","key", function(){
		equals(keydown, "a","keydown");
		equals(keypress,"a","keypress");
		equals(keyup,   "a","keyup");
		start();
	});
})
	
})
