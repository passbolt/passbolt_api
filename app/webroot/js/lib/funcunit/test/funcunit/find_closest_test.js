module("funcunit find closest",{
	setup: function() {
		S.open("//funcunit/test/findclosest.html")
	}
});

test("closest", function(){
	S("a:contains('Holler')").closest("#foo").click(function(){
		ok(this.hasClass("iWasClicked"),"we clicked #foo")
	})
	S("a:contains('Holler')").closest(".baz").click(function(){
		ok(this.hasClass("iWasClicked"),"we clicked .baz")
	})
	
})

test("find with traversers", function(){
	S(":contains('Holler')")
		.closest("#foo")
		.find(".combo")
		.hasClass("combo", true)
		.click();
		
	S(".combo:eq(0)").hasClass("iWasClicked", true, "we clicked the first combo")
	S(".combo:eq(1)").hasClass("iWasClicked", false, "we did not click the 2nd combo")
})

test("find this", function(){
	S("#foo").visible(function(){
		// this does a sync and async query
		var foo = S('#drag').text();
		// this does an async query, but the selector is now #drag
		// have to wrap it with S to force another async query
		S(this).find(".combo").exists("Combo exists");
	})
})

test("nested find", 2, function(){
	S(".baz").exists(function() { 
		S(this).find("#foo").exists(".foo found");
		S(this).find(".another").exists(".another found"); 
	})
})