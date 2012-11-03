steal('funcunit/qunit','funcunit/syn','jquery/event/pause').then(function(){

module("jquery/event/pause", {setup : function(){
	$("#qunit-test-area").html("")
	var div = $("<div id='wrapper_pause_test'><ul id='ul_pause_test'>"+
				"<li><p>Hello</p>"+
					"<ul><li><p id='foo_pause_test'>foo_pause_test Bar</p></li></ul>"+
				"</li></ul></div>").appendTo($("#qunit-test-area"));
	
}});

test("basics",3, function(){
	
	var calls =0,
		lastTime,
		space = function(){
			if(lastTime){
				
				ok(new Date - lastTime > 35,"space between times "+(new Date - lastTime))
			}
			lastTime = new Date()
		};
	
	$('#ul_pause_test').delegate("li", "show",function(ev){
		calls++;
		space();
		
		ev.pause();
		
		setTimeout(function(){
			ev.resume();	
		},100)
		
	})
	
	$('#wrapper_pause_test').bind('show', function(){
		space()
		equals(calls, 2, "both lis called");
		start()
	});
	stop();
	$('#foo_pause_test').trigger("show")
});







});