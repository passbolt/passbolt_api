load("steal/rhino/rhino.js");
steal("steal/build", "steal/build/scripts", "steal/build/styles", function() {
	steal.build("steal/test/myapp/index.html", {
		to : "steal/test/myapp"
	});
});
