import { foo } from "forward_slash/folder/";
import { bar } from "forward_slash/folder/foo/"
import { baz } from "forward_slash/folder/foo/bar/"

if (typeof window !== "undefined" && window.QUnit) {
	QUnit.equal(foo, "bar", "value set in folder module");
	QUnit.equal(bar, "baz", "value set in folder/foo module");
	QUnit.equal(baz, "end", "value set in folder/foo/bar module");

	QUnit.start();
	removeMyself();
} else {
	console.log("foo ", foo);
	console.log("bar ", bar);
	console.log("baz ", baz);
}
