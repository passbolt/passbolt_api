steal('can/control/view','can/view/micro','funcunit/qunit')  //load qunit
 .then(function(){
	
	module("can/control/view");
	
	test("this.view", function(){
		
		can.Control.extend("can.Control.View.Test.Qunit",{
			init: function() {
				this.element.html(this.view('init'))
			}
		})
		can.view.ext = ".micro";
		can.$("#qunit-test-area").append("<div id='cont_view'/>");
		
		new can.Control.View.Test.Qunit( can.$('#cont_view') );
		
		ok(/Hello World/i.test(can.$('#cont_view').text()),"view rendered")
	});
	
	test("test.suffix.doubling", function(){
		
		can.Control.extend("can.Control.View.Test.Qunit",{
			init: function() {
				this.element.html(this.view('init.micro'))
			}
		})
		
		can.view.ext = ".ejs"; // Reset view extension to default
		equal(".ejs", can.view.ext); 
		
		can.$("#qunit-test-area").append("<div id='suffix_test_cont_view'/>");
		
		new can.Control.View.Test.Qunit(can.$('#suffix_test_cont_view') );
		
		ok(/Hello World/i.test(can.$('#suffix_test_cont_view').text()),"view rendered")
	});
	
	test("complex paths nested inside a controller directory", function(){
		can.Control.extend("Myproject.Controllers.Foo.Bar");
		
		//var path = jQuery.Controller._calculatePosition(Myproject.Controllers.Foo.Bar, "init.ejs", "init")
		//equals(path, "//myproject/views/foo/bar/init.ejs", "view path is correct")
		
		can.Control.extend("Myproject.Controllers.FooBar");
		path = can.Control._calculatePosition(Myproject.Controllers.FooBar, "init.ejs", "init")
		equals(path, "//myproject/views/foo_bar/init.ejs", "view path is correct")
	})
});

