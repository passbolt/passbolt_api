steal('can/util', 'can/view/modifiers', function(can) {

// this only applied to jQuery libs
if(!window.jQuery){
	return;
}

module("can/view/modifiers");

test("modifier with a deferred", function(){
	can.$("#qunit-test-area").html("");
	stop();
	
	var foo = can.Deferred();
	can.$("#qunit-test-area").html("//can/view/test/qunit/deferred.ejs", foo );
	setTimeout(function(){
		foo.resolve({
			foo: "FOO"
		});
		start();
		equals(can.$("#qunit-test-area").html(), "FOO", "worked!");
	},100);

});

/*test("non-HTML content in hookups", function(){
  $("#qunit-test-area").html("<textarea></textarea>");
  can.render.hookup(function(){});
  $("#qunit-test-area textarea").val("asdf");
  equals($("#qunit-test-area textarea").val(), "asdf");
});*/

test("html takes promise", function(){
	var d = new can.Deferred();
	can.$("#qunit-test-area").html(d);
	stop();
	d.done(function(){
		equals(can.$("#qunit-test-area").html(), "Hello World", "deferred is working");
		start();
	})
	setTimeout(function(){
		d.resolve("Hello World")
	},10)
});

test("val set with a template within a hookup within another template", function(){
	can.view("//can/view/test/qunit/hookupvalcall.ejs",{});
});

test("jQuery.fn.hookup", function(){
	can.$("#qunit-test-area").html("");
	var els = $(can.view.render("//can/view/test/qunit/hookup.ejs",{})).hookup();
	can.$("#qunit-test-area").html(els); //makes sure no error happens
});

test("hookups don't break script execution (issue #130)", function(){
	// this simulates a pending hookup (hasn't been run yet)
	can.view.hook(function() {});
	// this simulates HTML with script tags being loaded (probably legacy code)
	can.$("#qunit-test-area").html("<script>can.$('#qunit-test-area').html('OK')</script>");
	equal(can.$("#qunit-test-area").html(),'OK');
	can.$("#qunit-test-area").html("");
});

})();
