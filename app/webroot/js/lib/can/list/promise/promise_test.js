steal("can/list/promise", "can/compute", "can/test", "steal-qunit", function () {

	QUnit.module("can/list/promise");

	test("list.isResolved", function () {

		var def = new can.Deferred();

		var l = new can.List(def);

		ok(!l.isResolved(), "deferred-list is not resolved");

		stop();

		l.done(function () {

			ok(l.isResolved(), "it's resolved!");
			deepEqual(l.attr(), ["one", 2], "has data");

			start();
		});

		def.resolve(["one", 2]);

	});

	test("list.isResolved in a compute", function () {

		var def = new can.Deferred();

		var l = new can.List(def);

		var c = can.compute(function () {
			return l.isResolved();
		});

		ok(!c(), "not resolved");

		var callbackCount = 0;

		c.bind("change", function (ev, newVal, oldVal) {
			callbackCount++;

			if (callbackCount === 1) {
				ok(newVal, "resolved");
				deepEqual(l.attr(), [1, 2]);
			} else if (callbackCount === 2) {
				ok(!newVal, "not resolved");
			} else if (callbackCount === 3) {
				ok(newVal, "resolved");
				deepEqual(l.attr(), ["a", "b"]);
				start();
			}
		});

		stop();

		def.resolve([1, 2]);

		setTimeout(function () {

			var def2 = new can.Deferred();
			l.replace(def2);

			setTimeout(function () {
				def2.resolve(["a", "b"]);
			}, 60);

		}, 60);

	});
	
	
	test("then and done are called with the list instance", function(){
		stop();
		
		var def = new can.Deferred();

		var l = new can.List(def);
		
		l.then(function(list){
			equal(list , l, "then is called back with the list argument");
		});
		l.done(function(list){
			equal(list, l, "done is called back with the list argument");
		});
		l.always(function(){
			start();
		});
		
		def.resolve([1, 2]);
	});
	
	test("rejecting adds a reason attr", function(){
		stop();
		var def = new can.Deferred();

		var l = new can.List(def);
		
		l.fail(function(reason){
			equal(reason, "failed!", "got fail reason");
		});
		
		l.bind("reason", function(ev, newVal){
			equal(newVal, "failed!", "event updated");
			start();
		});
		
		def.reject("failed!");
		
	});
	
	test("A list is treated like a promise",function(){
		stop();
		// Make sure this works with normal promises
		var def1 = new can.Deferred();
		var def2 = new can.Deferred(),
			def2promise = def2.promise();
		var def3 = def1.then(function(){
			return def2promise;
		});
		
		def3.then(function(value){
			var returningPromisesWorks = value === "def2";
			ok(returningPromisesWorks, "returning a promise works");
			
			def1 = new can.Deferred();
			def2 = new can.Deferred();
			
			var list = new can.List(def2);
			
			def3 = def1.then(function(){
				return list;
			});
			
			def3.then(function(list){
				equal(list.length, 2, "there are 2 items in the list, the outer deferred waited on the list's deferred");
				start();
			});
			setTimeout(function(){
				def1.resolve();
				setTimeout(function(){
					def2.resolve(["a","b"]);
				},10);
			},10);
		});
		
		setTimeout(function(){
			def1.resolve();
			setTimeout(function(){
				def2.resolve("def2");
			},10);
		},10);
		
	});
	
	
	

});
