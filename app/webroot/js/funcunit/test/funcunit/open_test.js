module("funcunit-open")

test("URL Test", function(){
	var path = FuncUnit.getAbsolutePath("http://foo.com")
	equals(path, "http://foo.com", "paths match");
	
	path = FuncUnit.getAbsolutePath("//myapp/mypage.html");
	var root = steal.root;
	if(!root.protocol()){
		root = steal.File(root.joinFrom(steal.pageUrl().dir(), true));
	}
	equals(path, root.join("myapp/mypage.html"), "paths match");
})



test("Back to back opens", function(){
	S.open("//funcunit/test/myotherapp.html");
	S.open("//funcunit/test/myapp.html");

	S("#changelink").click(function(){
		equals(S("#changelink").text(), "Changed","href javascript run")
	})
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