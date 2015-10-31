import 'dep_plugins/main.less!';

if(typeof window !== "undefined" && window.QUnit) {
	QUnit.equal(document.getElementById("test-element").clientWidth, 200, "element width set by css");

	QUnit.start();
	removeMyself();
} else if(typeof document != "undefined") {
	console.log("width", document.getElementById("test-element").clientWidth);
}
