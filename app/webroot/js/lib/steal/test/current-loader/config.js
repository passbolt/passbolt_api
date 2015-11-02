var MySystem = require('@loader');

if(typeof window !== "undefined" && window.QUnit) {

	QUnit.ok(MySystem == System,  "got back the current loader");

	QUnit.start();
	removeMyself();
} else {
	console.log("Systems", MySystem == System);
}