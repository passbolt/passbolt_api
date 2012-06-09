steal.plugins('funcunit').then(function(){

module("Jmvcdoc.Nav", { 
	setup: function(){
		S.open("//documentjs/jmvcdoc/nav/nav.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Jmvcdoc.Nav Demo","demo text");
});


});