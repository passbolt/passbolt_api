steal.plugins('funcunit').then(function(){

module("Jmvcdoc.Search", { 
	setup: function(){
		S.open("//documentjs/jmvcdoc/search/search.html");
	}
});

test("Text Test", function(){
	equals(S("h1").text(), "Jmvcdoc.Search Demo","demo text");
});


});