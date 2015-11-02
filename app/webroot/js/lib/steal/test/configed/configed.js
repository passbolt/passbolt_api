steal(function(){
	
	if(typeof window !== "undefined" && window.QUnit) {
		QUnit.deepEqual(steal.config("bundle"),["foo"], "read back bundle");
		
		QUnit.start();
		removeMyself();
	} else {
		console.log("basics loaded", module);
	}
	
});
