
System.import("lodash").then(function(_) {

	if(typeof window !== "undefined" && window.QUnit) {
		QUnit.equal(_.myModule, "lodash", "Grabbed the correct lodash");

		QUnit.start();
		removeMyself();
	} else {
		console.log("lodash loaded", _);
	}

});
