var foo = require("foo");
var bar = require("bar");

if (typeof window !== "undefined" && window.QUnit) {
	QUnit.equal(foo(), "foo", "got foo");
	QUnit.equal(bar(), "bar", "got bar");

	QUnit.start();
	removeMyself();
} else {
	console.log("foobar", foo, bar);
}
