can = {};
steal('can/util/zepto','funcunit/qunit', 'can/util/destroyed.js',function(){
	
module("zepto-fill")

test("deferred", function(){
	var d = $.ajax({
		url: 'thing.json',
		async: false,
		dataType : 'text'
	})
	d.done(function(text){
		ok(true,"called")
	})
	
})

test("destroyed",1, function(){
	$("#qunit-test-area").append("<div id='foo'>foo</div>")
	$('#foo').bind('destroyed', function(){
		ok(true, "called")
	})
	
	$('#foo').remove()
})

})
