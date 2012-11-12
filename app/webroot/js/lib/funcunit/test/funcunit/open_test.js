module("funcunit-open")

test('S.open accepts a window', function() {
	S.open(window);
	S('#tester').click();
	S("#tester").text("Changed", "Changed link")
	
	S.open(frames["myapp"]);
	S("#typehere").type("").type("javascriptmvc")
	S("#seewhatyoutyped").text("typed javascriptmvc","typing");
})



test("Back to back opens", function(){
	S.open("//funcunit/test/myotherapp.html");
	S.open("//funcunit/test/myapp.html");

	S("#changelink").click()
	S("#changelink").text("Changed","href javascript run")
})


test("Back to back opens with hash", function(){
	S.open("//funcunit/test/myapp.html?bar#foo");
	S("#changelink").click();
	S("#changelink").text("Changed","href javascript run");
	
	S.open("//funcunit/test/myapp.html?bar#foo2");
	S("#changelink").text("Change", "reload with hash works");
})

test('Testing win.confirm in multiple pages', function() {
	S.open('//funcunit/test/open/first.html');
	S('.next').click();

	S('.show-confirm').click();
	S.confirm(true);
	S('.results').text('confirmed!', "Confirm worked!");
})