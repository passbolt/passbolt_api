
if (typeof window !== "undefined" && window.QUnit) {
	var systemInstantiate = System.instantiate;

	System.instantiate = function(load) {
		if (load.name.indexOf("main.less") !== -1) {
			var hasLineNumber = load.source.indexOf("line 1") !== -1,
				hasStrictMath = load.source.indexOf("100%") !== -1;

			QUnit.ok(hasLineNumber, "less set to dump line numbers");
			QUnit.ok(hasStrictMath, "less set to process only maths inside un-necessary parenthesis");
			QUnit.start();
			removeMyself();
		}
		return systemInstantiate.apply(this, arguments);
	};

	steal("less_options/main.less!");
}
