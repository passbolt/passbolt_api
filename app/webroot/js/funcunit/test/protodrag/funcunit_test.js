module("myapp",{
	setup: function(){
		S.open("demo/myapp.html")
	}
})

test("Copy Test", function(){
	S("#typehere").type("javascript1mvc[left][left][left]\b", function(){
		equals(S("#seewhatyoutyped").text(), "typed javascriptmvc","typing");
	})
	S("#copy").click(function(){
		equals(S("#seewhatyoutyped").text(), "copied javascriptmvc","copy");
	})
})

test("Drag Test", function(){
	S("#drag").drag("#drop", function(){
		equals(S("#drop").text(), "Drags 1", 'drag worked correctly')
	})
})