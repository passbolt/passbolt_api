"format global";

QUnit.module("system-json plugin");

asyncTest("Basics works", function(){
	System.import("test/my.json").then(function(my){
		equal(my.name, "foo", "name is right");
	}).then(start);
});


QUnit.start();
