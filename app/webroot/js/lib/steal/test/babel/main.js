import module from "other";

if(typeof window !== "undefined" && window.QUnit) {
	QUnit.ok(module, "got basics/module");
	QUnit.equal(module, "bar", "module name is right");
	
	QUnit.start();
	removeMyself();
} else {
	console.log("basics loaded", module);
}
