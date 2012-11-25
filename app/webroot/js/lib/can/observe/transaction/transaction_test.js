module('can/observe/transaction')

test("Basic Transaction",function(){
	stop();
	var obs = new can.Observe({
		first: "justin",
		last: "meyer"
	});
	var count = 0,
		ready = false;
	
	obs.bind("changed", function(ev, attr, how, newVal, oldVal){
		
		ok(ready, "event is ready");
		
		if(count == 0){
			equal(attr,"first")
			equal(newVal, "Justin")
		} else if(count == 1){
			equal(attr,"last")
			equal(newVal, "Meyer")
		} else {
			ok(false,"too many events")
		}
		count++;
	});	
	
	var end = can.transaction();
	
	obs.attr("first","Justin");
	setTimeout(function(){
		obs.attr("last","Meyer")
		ready = true;
		end();
		start();
	},30)
	
	
});


