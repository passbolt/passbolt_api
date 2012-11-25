steal("jquery/event/drop",'funcunit/syn', 'funcunit/qunit', function($, Syn) {
	
module("jquery/event/drop");

test("new drop added", 3, function(){
	var div = $("<div>"+
			"<div id='drag'></div>"+
			"<div id='midpoint'></div>"+
			"<div id='drop'></div>"+
			"</div>");

	div.appendTo($("#qunit-test-area"));
	var basicCss = {
		width: "20px",
		height: "20px",
		position: "absolute",
		border: "solid 1px black"
	}
	$("#drag").css(basicCss).css({top: "0px", left: "0px", zIndex: 1000, backgroundColor: "red"})
	$("#midpoint").css(basicCss).css({top: "0px", left: "30px"})
	$("#drop").css(basicCss).css({top: "0px", left: "60px"});

	$('#drag').bind("draginit", function(){});

	$("#midpoint").bind("dropover",function(){
		ok(true, "midpoint called");

		$("#drop").bind("dropover", function(){
			ok(true, "drop called");
		});

		$('body').on("dropon", function(ev) {
			ok(false, 'parent dropon should not be called');
		});

		$('#drop').on("dropon", function(ev) {
			ok(true, 'dropon called');
			ev.stopPropagation();
		});

		$.Drop.compile();
	});
	stop();
	Syn.drag({to: "#drop"},"drag", function(){
		$('body').off('dropon');
		start();
	});
});



})
