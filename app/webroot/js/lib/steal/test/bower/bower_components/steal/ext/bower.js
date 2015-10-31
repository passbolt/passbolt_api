"format cjs";

// Add @loader, for SystemJS
if(!System.has("@loader")) {
	System.set('@loader', System.newModule({'default':System, __useDefault: true}));
}

// Don't bother loading these dependencies
var excludedDeps = {
	steal: true,
	systemjs: true,
	"system-bower": true
};

var utils = {
	pkg: {
		main: function(pkg){
			if(pkg.system && pkg.system.main) {
				return pkg.system.main;
			}

			var main = pkg.main;
			if(typeof main === "undefined") {
				return false;
			}
			if(typeof main === "string") {
				return main;
			}
			return main[0];
		}
	}
};

// Combines together dependencies and devDependencies (if bowerDev option is enabled)
var getDeps = function(loader, bower){
	var deps = {};
	var addDeps = function(dependencies){
		for(var name in dependencies) {
			if(!excludedDeps[name]) {
				deps[name] = dependencies[name];
			}
		}
	};
	addDeps(bower.dependencies || {});
	// Only get the devDependencies if this is the root bower and the 
	// `bowerDev` option is enabled
	if(loader.bowerDev && !loader._bowerMainLoaded) {
		addDeps(bower.devDependencies || {});
	}
	return deps;
};

// Get the directory where the main is located, including the bowerPath
var getMainDir = function(bowerPath, name, main){
	var parts = main.split('/');
	parts.pop();

	// Remove . if it starts with that
	if(parts[0] === '.') {
		parts.shift();
	}
	parts.unshift.apply(parts, [bowerPath, name]);
	return parts.join('/');
};

// Set paths for this dependency
var setPaths = function(config, bowerPath, name, main) {
	// Get the main directory, that is the directory including the 
	// bowerPath, the package name, and the path to the main file.
	var mainDir = bowerPath + "/" + name + "/";
	if(!config.paths[name] && main) {
		var mainDir = getMainDir(bowerPath, name, main);
	}

	// Replace bower_components in paths to the bowerPath
	if(bowerPath !== "bower_components") {
		var val;
		for(var path in config.paths) {
			val = config.paths[path];
			config.paths[path] = val.replace("bower_components", bowerPath);
		}
	}

	// Set the path to the `main` and the path to the wildcard.
	if(this._bowerMainLoaded) {
		// Add a .js if there is no extension
		if(main && main.indexOf(".") === -1) {
			main = main + ".js";
		}

		if(main) {
			config.paths[name] = [bowerPath, name, main].join('/');
		}
		config.paths[name + "/*"] = mainDir + "/*.js";
	}
};

var setMain = function(bower) {
	if(!this._bowerMainLoaded && !this.main && bower.main) {
		this.main = bower.main;
	}
};

/**
 * @function fetch
 * @description Implement fetch so that we can warn the user in case of a 404.
 * @signature `fetch(load)`
 * @param {Object} load Load object
 * @return {Promise} a promise to resolve with the load's source
 */
exports.fetch = function(load){
	var loader = this;
	return Promise.resolve(this.fetch(load)).then(null, function(msg){
		if(/Not Found/.test(msg)) {
			var packageName = /\/(.+?)\/bower\.json/.exec(load.name)[1];
			console.log("Unable to load the bower.json for", packageName);
		}
		return "";
	});
};

/**
 * @function translate
 * @description Convert the bower.json file into a System.config call.
 * @signature `translate(load)`
 * @param {Object} load Load object
 * @return {Promise} a promise to resolve with the load's new source.
 */
exports.translate = function(load){
	// This could be an empty string if the fetch failed.
	if(load.source == "") {
		return "define([]);";
	}

	var loader = this;
	var bowerPath = loader.bowerPath || "bower_components";

	// Get bower dependencies
	var bower = JSON.parse(load.source);
	var deps = getDeps(loader, bower);

	// Convert bowerIgnore
	var bowerIgnore = bower.system && bower.system.bowerIgnore;
	if(bowerIgnore && typeof bowerIgnore.length === "number") {
		var bowerMap = {};
		for(var i = 0, len = bowerIgnore.length; i < len; i++) {
			bowerMap[bowerIgnore[i]] = true;
		}
		bowerIgnore = bowerMap;
	}

	// Get the AMD dependencies
	var amdDeps = [];
	for(var dep in deps) {
		// add a check of bower.system.bowerIgnore before pushing dep
		if(!bowerIgnore || !bowerIgnore[dep]) {
			amdDeps.push(
				bowerPath + "/" + dep + "/bower.json!bower"
			);
		}
	}
	// Add in the loader so these will be buildable in parallel.
	amdDeps.unshift("@loader");

	// Creates the configuration object. If the library provides a `system`
	// object on its bower, use that as the base, otherwise we'll create our own.
	var name = bower.name.toLowerCase();
	var config = bower.system || {};
	config.paths = config.paths || {};

	// Set the paths to the wildcard and main modules.

	// Don't set any paths for the main bower.json
	setPaths.call(loader, config, bowerPath, name, utils.pkg.main(bower));
	setMain.call(loader, bower);
	loader._bowerMainLoaded = true;

	if(config.configDependencies) {
		amdDeps = amdDeps.concat(config.configDependencies);
	}

	return "define(" + JSON.stringify(amdDeps) + ", function(loader){\n" +
		"loader.config(" +JSON.stringify(config, null, " ") + ");" + "\n});";
};

