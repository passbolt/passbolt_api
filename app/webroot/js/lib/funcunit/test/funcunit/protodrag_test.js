module("funcunit-prototype / scriptaculous drag",{
	setup: function() {
		S.open("//funcunit/test/protodrag/myapp.html");
	}
})


test("Drag", function(){
	
	S("#drag").drag("#drop")
	S("#drop").text("Drags 1", 'drag worked correctly')
})