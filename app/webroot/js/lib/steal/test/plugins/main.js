import 'plugins/main.css!';
import './steal-module';

if(typeof window !== "undefined" && window.QUnit) {
	QUnit.equal(document.getElementById("test-element").clientWidth, 200, "element width set by css");
	QUnit.equal(document.getElementById("test-element2").clientWidth, 300, "element width set by css");
	QUnit.start();
	removeMyself();
} else {
	console.log("width", document.getElementById("test-element").clientWidth);
}
