import my from "json/my.json";

if (typeof window !== "undefined" && window.QUnit) {
	QUnit.equal(my.foo,"bar", "module returned" );

	QUnit.start();
	removeMyself();
} else {
	console.log("my ", my);
}
