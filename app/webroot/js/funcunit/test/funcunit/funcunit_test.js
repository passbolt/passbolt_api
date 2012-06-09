module("funcunit - jQuery API",{
	setup: function() {
		var self = this;
		S.open("//funcunit/test/myapp.html", function(){
			self.pageIsLoaded = true;
		})
	}
})

test("qUnit module setup works async", function(){
	ok(this.pageIsLoaded, "page is loaded set before")
})

test("Iframe access", function(){
	equals(S("h2",0).text(), "Goodbye World", "text of iframe")
})

test("typing alt and shift characters", function(){
	S('#typehere').type("@", function(){
		equals(S('#typehere').val(), "@", "types weird chars" );
	})
})

test("html with function", 1, function(){
	S("#clickToChange").click()
		.html(function(html){
			return html == "changed"
		})
		S("#clickToChange").html("changed","wait actually waits")
	
})
test("Html with value", 1, function(){
	S("#clickToChange").click().html("changed","wait actually waits")
})

test("Wait", function(){
	var before,
		after
	setTimeout(function(){
		before = true;
	},2)
	setTimeout(function(){
		after = true
	},1000)
	S.wait(20,function(){
		ok(before, 'after 2 ms')
		ok(!after, 'before 1000ms')
		
	})
})

test("hasClass", function(){
	var fast
	
	S("#hasClass").click();
	setTimeout(function(){
		fast = true
	},50)
	
	S("#hasClass").hasClass("someClass",true, function(){
		ok(fast,"waited until it has a class exists")
	});
	S("#hasClass").hasClass("someOtherClass",false, function(){
		ok(fast,"waited until it has a class exists")
	});
	// S("#doesnotexist").hasClass("someOtherClass", false, "element doesn't exist, this should fail");
})

test("Exists", function(){
	var fast;
	
	S("#exists").click();
	setTimeout(function(){
		fast = true
	},50)
	S("#exists p").exists(function(){
		ok(fast,"waited until it exists")
	});
	
})

test("Trigger", function(){
	S("#trigger").trigger('myCustomEvent');
	S("#trigger p").text("I was triggered");
})
test("Confirm", function(){
	S("#confirm").click();
	S.confirm(true);
	S("#confirm p").text("I was confirmed");
	S("#confirm").click();
	S.confirm(false);
})
test("Accessing the window", function(){
	ok(S(S.win).width()> 20, "I can get the window's width")
})
test("Accessing the document", function(){
	ok(S(S.win.document).width()> 20, "I can get the document's width")
})


test("two getters in a row", function(){
	equal(S("h1").text(), "Hello World")
	equal(S("h1").text(), "Hello World")
})


test("then", function(){
	S("#exists").exists().then(function(){
		equals(this.length, 1, "this is correct")
	});
})


test("branch", function(){
	S.branch(function(){
		return (S("#exists").size() > 0);
	}, function(){
		ok(true, "found exists")
	}, function(){
		return (S("#notexists").size() > 0);
	}, function(){
		ok(false, "found notexists")
	});
	
	
	S.branch(function(){
		return (S("#notexists").size() > 0);
	}, function(){
		ok(false, "found notexists")
	}, function(){
		return (S("#exists").size() > 0);
	}, function(){
		ok(true, "found exists")
	});
	
})

test("invisible", function(){
	S(".hidden").invisible("Invisible works");
})
