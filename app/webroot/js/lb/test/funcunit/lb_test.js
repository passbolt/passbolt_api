steal("funcunit", function(){
	module("lb test", { 
		setup: function(){
			S.open("//lb/lb.html");
		}
	});
	
	test("Copy Test", function(){
		equals(S("h1").text(), "Welcome to JavaScriptMVC 3.2!","welcome text");
	});
})