
System.import("lodash").then(function(_) {

	if(typeof window !== "undefined" && window.QUnit) {
		QUnit.ok(_, "Got lodash");

		QUnit.equal(typeof _, "function", "Lodash is a function");

		QUnit.start();
		removeMyself();
	} else {
		console.log("lodash loaded", _);
	}

});
