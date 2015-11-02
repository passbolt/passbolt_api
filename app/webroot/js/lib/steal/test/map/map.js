steal("mapd", function(m){
	if(window.QUnit) {
		QUnit.ok(m, "got map");
		QUnit.equal(m.name, "map", "module name is right");
		QUnit.start();
		removeMyself();
	} else {
		console.log("basics loaded", m);
	}
});
