steal("funcunit", function(){
	module("passbolt test", { 
		setup: function(){
			S.open("//passbolt/passbolt.html");
		}
	});
	
	test("Copy Test", function(){
		equals(S("h1").text(), "Welcome to JavaScriptMVC 3.2!","welcome text");
	});
})