module("funcunit-syn integration")


test("Type and slow Click", function(){
	S.open("//funcunit/test/myapp.html");
	S("#typehere").type("javascriptmvc")
	S("#seewhatyoutyped").text("typed javascriptmvc","typing");
	
	S("#copy").click();
	S("#seewhatyoutyped").text("copied javascriptmvc","copy");
	S("#seewhatyouchanged").text("changed javascriptmvc","change");
})

test("ctrl test", function(){
	S.open("//funcunit/test/myapp.html");
	S("#typehere").type("abc[ctrl]ac[ctrl-up]", function(){
		equals(S("#typehere").val(), "abc");
	})
})

test("clipboard", function(){
	S.open("//funcunit/test/myapp.html");
	S("#typehere").type("abc[ctrl]ac[ctrl-up][right][ctrl]v[ctrl-up]", function(){
		equals(S("#typehere").val(), "abcabc");
	})
})

test("Type and clear", function(){
	S.open("//funcunit/test/myapp.html");
	S("#typehere").type("javascriptmvc").type("")
	S("#seewhatyoutyped").text("typed ","clear works");
})

test("Nested actions", function(){
	S.open("//funcunit/test/myapp.html");
	
	S("#typehere").exists(function(){
		this.type("[ctrl]a\b[ctrl-up]javascriptmvc")
		S("#seewhatyoutyped").text("typed javascriptmvc","typing");
		S("#copy").click();
		S("#seewhatyoutyped").text("copied javascriptmvc","copy");
	})
})

test("Move To", function(){
	S.open("//funcunit/test/drag/drag.html");
	S("#start").move("#end")
	S("#typer").type("javascriptmvc")
	S("#typer").val("javascriptmvc","move test worked correctly");

})

test("Drag To", function(){
	S.open("//funcunit/test/drag/drag.html");
	S("#drag").drag("#drop")
	S("#clicker").click();
	S(".status").text("dragged", 'drag worked correctly')

})

test("RightClick", function(){
	if(/Opera/.test(navigator.userAgent)){
		return;
	}
	S.open("//funcunit/test/myapp.html", null, 10000);
	S("#rightclick").rightClick()
	S(".rightclickResult").text("Right Clicked", "rightclick worked")

})


test('Data',function(){
	S.open("//funcunit/test/myapp.html");
	
	S('#testData').wait(function(){
		return S.win.$(this).data('idval') === 1000;
	}, "Data value matched");
})