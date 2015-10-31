System.config({
	paths: {
		// "steal/dev/*" : "../dev/*.js",
	},
	bundle: ["foo"],
	ext : {
		crazy : "extensions/text"
	},
	lessOptions: {
		dumpLineNumbers: "comments", // default false
		strictMath: true, // default false
	}
});

System.ext.txt = "extensions/text";
