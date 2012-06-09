module("funcunit - jQuery API",{
	setup: function() {
		S.open("//funcunit/test/confirm.html")
	}
})

test("confirm overridden", function(){
	S('#confirm').click()
	S('#confirm').text("I was confirmed");
});

test("alert overridden", function(){
	S('#alert').click()
	S('#alert').text("I was alert");
});