steal.config({
	meta: {
		"jquerty": {
			exports: "jQuerty"
		}
	}
});
System.define("jquerty","var jQuerty = {name: 'jQuerty'}")
define("bar", ["jquerty"],function(jquerty){
	return {
		name: "bar",
		jquerty: jquerty
	};
});
define("foo",["bar"], function(bar){
	if(typeof window !== "undefined" && window.QUnit) {
		QUnit.ok(bar, "got basics/module");
		QUnit.equal(bar.name, "bar", "module name is right");
		
		QUnit.equal(bar.jquerty.name, "jQuerty", "got global");
		
		
		QUnit.start();
		removeMyself();
		return {};
	} else {
		console.log("basics loaded", bar);
	}
});



