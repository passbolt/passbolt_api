steal.config({
	paths: {
		// "steal/dev/*" : "../dev/*.js",
		"@traceur": "../bower_components/traceur/traceur.js",
	},
	bundle: ["foo"],
	ext : {
		crazy : "extensions/text"
	}
});

System.ext.txt = "extensions/text";
