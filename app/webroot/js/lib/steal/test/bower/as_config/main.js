
System.import("jquerty").then(function($) {

	if(typeof window !== "undefined" && window.QUnit) {
		QUnit.equal($, "jquerty", "Grabbed the correct module");

		QUnit.start();
		removeMyself();
	} else {
		console.log("jquerty loaded", $);
	}

});
