if(typeof window === "undefined" || window.noConfig !== true)  {

	steal.config({
		paths: {
			"steal/*" : "../*.js",
			"@traceur": "../bower_components/traceur/traceur.js",
			"less": "../less-1.7.0.js",
			"pathed/pathed": "basics/pathed.js"
		},
		map: {
			"mapd/mapd": "map/mapped"
		}
	});

} else {
	throw "fake loading error";
}
