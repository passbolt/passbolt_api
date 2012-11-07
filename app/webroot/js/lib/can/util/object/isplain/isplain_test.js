asyncTest("isPlainObject", function() {
	expect(15);

	var iframe;

	// The use case that we want to match
	ok(can.isPlainObject({}), "{}");

	// Not objects shouldn't be matched
	ok(!can.isPlainObject(""), "string");
	ok(!can.isPlainObject(0) && !can.isPlainObject(1), "number");
	ok(!can.isPlainObject(true) && !can.isPlainObject(false), "boolean");
	ok(!can.isPlainObject(null), "null");
	ok(!can.isPlainObject(undefined), "undefined");

	// Arrays shouldn't be matched
	ok(!can.isPlainObject([]), "array");

	// Instantiated objects shouldn't be matched
	ok(!can.isPlainObject(new Date()), "new Date");

	var fnplain = function(){};

	// Functions shouldn't be matched
	ok(!can.isPlainObject(fnplain), "fn");

	/** @constructor */
	var fn = function() {};

	// Again, instantiated objects shouldn't be matched
	ok(!can.isPlainObject(new fn()), "new fn (no methods)");

	// Makes the function a little more realistic
	// (and harder to detect, incidentally)
	fn.prototype["someMethod"] = function(){};

	// Again, instantiated objects shouldn't be matched
	ok(!can.isPlainObject(new fn()), "new fn");

	// DOM Element
	ok(!can.isPlainObject(document.createElement("div")), "DOM Element");

	// Window
	ok(!can.isPlainObject(window), "window");

	try {
		can.isPlainObject( window.location );
		ok( true, "Does not throw exceptions on host objects");
	} catch ( e ) {
		ok( false, "Does not throw exceptions on host objects -- FAIL");
	}

	try {
		iframe = document.createElement("iframe");
		document.body.appendChild(iframe);

		window.iframeDone = function(otherObject){
			// Objects from other windows should be matched
			ok(can.isPlainObject(new otherObject()), "new otherObject");
			document.body.removeChild( iframe );
			start();
		};

		var doc = iframe.contentDocument || iframe.contentWindow.document;
		doc.open();
		doc.write("<body onload='window.parent.iframeDone(Object);'>");
		doc.close();
	} catch(e) {
		document.body.removeChild( iframe );

		ok(true, "new otherObject - iframes not supported");
		start();
	}
});
