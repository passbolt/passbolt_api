steal('funcunit/qunit','jquery/event/default','jquery/event/pause', function() {

module("jquery/event/default_pause");


test("default and pause with delegate", function(){
	var order = [];
	stop();
	$("#qunit-test-area").html("<div id='foo_default_pause'><p id='bar_default_pause'>hello</p></div>")

	$("#foo_default_pause").delegate("#bar_default_pause","default.show", function(){
		order.push("default")
	});

	$("#foo_default_pause").delegate("#bar_default_pause","show", function(ev){
		order.push('show');
		ev.pause();
		setTimeout(function(){
			ev.resume();
			setTimeout(function(){
				start();
				same(order,['show','default'])
			},30)

		},50)
	});


	$("#bar_default_pause").trigger("show")

});

test("default and pause with live", function(){
	$("#qunit-test-area").html("<div id='foo_default_pause'>hello</div>")

	var order = [];
	stop();

	$("#foo_default_pause").live("default.show", function(){
		order.push("default")
	});
	$("#foo_default_pause").live("show", function(ev){
		order.push('show')
		ev.pause();
		setTimeout(function(){
			ev.resume();
			setTimeout(function(){
				start();
				same(order,['show','default'])
				$("#foo_default_pause").die("show");
				$("#foo_default_pause").die("default.show");
			},30)
		},50)
	});


	$("#foo_default_pause").trigger("show")

});


test("triggerAsync", function(){
	$("#qunit-test-area").html("<div id='foo_default_pause'>hello</div>")

	var order = [];
	stop();

	$("#foo_default_pause").live("default.show", function(){
		order.push("default")
	});

	$("#foo_default_pause").live("show", function(ev){
		order.push('show')
		ev.pause();
		setTimeout(function(){
			ev.resume();
			setTimeout(function(){
				start();
				$("#foo_default_pause").die()
				same(order,['show','default','async'])
			},30)
		},50)
	});


	$("#foo_default_pause").triggerAsync("show", function(){
		order.push("async")
	})
});

test("triggerAsync with prevented callback when ev.preventDefault() is called before event pause", function(){
	$("#qunit-test-area").html("<div id='foo_default_pause'>hello</div>")

	var order = [];
	stop();

	$("#foo_default_pause").live("default.show", function(){
		order.push("default")
	});

	$("#foo_default_pause").live("show", function(ev){
		order.push('show');
		ev.preventDefault();
		ev.pause();
		setTimeout(function(){
			ev.resume();
			setTimeout(function(){
				start();
				$("#foo_default_pause").die()
				same(order,['show','prevented'])
			},30)
		},50)
	});


	$("#foo_default_pause").triggerAsync("show", [5],function(){
		order.push("async")
	}, function(){
		order.push("prevented")
	})
});
test("triggerAsync with prevented callback when ev.preventDefault() is called after event pause", function(){
	$("#qunit-test-area").html("<div id='foo_default_pause'>hello</div>")

	var order = [];
	stop();

	$("#foo_default_pause").live("default.show", function(){
		order.push("default")
	});

	$("#foo_default_pause").live("show", function(ev){
		order.push('show');
		
		ev.pause();
		setTimeout(function(){
			ev.preventDefault();
			ev.resume();
			setTimeout(function(){
				start();
				$("#foo_default_pause").die()
				same(order,['show','prevented'])
			},30)
		},50)
	});


	$("#foo_default_pause").triggerAsync("show", [5],function(){
		order.push("async")
	}, function(){
		order.push("prevented")
	})
});

test("triggerAsync within another triggerAsync", function(){
	$("#qunit-test-area").html("<div id='foo_default_pause'>hello</div>")

	var order = [];
	stop();

	$("#foo_default_pause").live("default.show", function(){
		order.push("show default")
	});
	$("#foo_default_pause").live("default.hide", function(){
		order.push("hide default")
	});
	$("#foo_default_pause").live("hide", function(){
		order.push("hide")
	});
	$("#foo_default_pause").live("show", function(ev){
		order.push('show');
		ev.pause();
		$("#foo_default_pause").triggerAsync("hide",function(){
				order.push("hide async")
				ev.resume();
				setTimeout(function(){
					
					start();
					$("#foo_default_pause").die()
					same(order,['show','hide','hide default',"hide async","show default","show async"])
				},30)
				
			}, function(){
				order.push("hide prevented")
		})
	});


	$("#foo_default_pause").triggerAsync("show",function(){
		order.push("show async")
	}, function(){
		order.push("show prevented")
	})
});

test("triggerAsync within another triggerAsync with prevented callback", function(){
	$("#qunit-test-area").html("<div id='foo_default_pause'>hello</div>")

	var order = [];
	stop();

	$("#foo_default_pause").live("default.show", function(){
		order.push("show default")
	});
	$("#foo_default_pause").live("default.hide", function(){
		order.push("hide default")
	});
	$("#foo_default_pause").live("hide", function(){
		order.push("hide")
	});
	$("#foo_default_pause").live("show", function(ev){
		order.push('show');
		ev.preventDefault();
		ev.pause();
		$("#foo_default_pause").triggerAsync("hide",function(){
				order.push("hide async")
				ev.resume();
				setTimeout(function(){
					start();
					$("#foo_default_pause").die()
					same(order,['show','hide','hide default',"hide async","show prevented"])
				},30)
				
			}, function(){
				order.push("hide prevented")
		})
	});


	$("#foo_default_pause").triggerAsync("show",function(){
		order.push("show async")
	}, function(){
		order.push("show prevented")
	})
});
test("triggerAsync with nothing", function(){
	$("#fool").triggerAsync("show", function(){
		ok(true)
	})
});


});