import $ from "jquery";

if (typeof window !== "undefined" && window.QUnit) {
	QUnit.equal(typeof $,"function", "got jQuery" );

	QUnit.start();
	removeMyself();
} else {
	console.log("$ ", $);
}

