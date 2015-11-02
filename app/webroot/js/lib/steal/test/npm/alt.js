import $ from "jquery";

if (typeof window !== "undefined" && window.QUnit) {
	QUnit.equal(typeof $,"function", "got jQuery" );
	QUnit.ok(isAlt, "is alternate page");
	QUnit.start();
	removeMyself();
} else {
	console.log("$ ", $);
	console.log("isAlt", window.isAlt);
}

