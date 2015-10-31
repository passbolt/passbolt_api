steal("bar/foo/baz.js", function(foo){
	
	if(typeof window !== "undefined" && window.QUnit) {
		QUnit.equal(foo, "it works", "Loaded foo with weird path manipulation.");
		
		QUnit.start();
		removeMyself();
	} else {
		console.log("basics loaded", module);
	}
	
});
