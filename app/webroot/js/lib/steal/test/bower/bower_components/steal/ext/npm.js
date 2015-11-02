"format cjs";

// TODO: cleanup removing package.json
var utils = require('./npm-utils');
var crawl = require('./npm-crawl');


// Add @loader, for SystemJS
if(!System.has("@loader")) {
	System.set('@loader', System.newModule({'default':System, __useDefault: true}));
}




// SYSTEMJS PLUGIN EXPORTS =================

/**
 * @function translate
 * @description Convert the package.json file into a System.config call.
 * @signature `translate(load)`
 * @param {Object} load Load object
 * @return {Promise} a promise to resolve with the load's new source.
 */
exports.translate = function(load){
	var loader = this;

	// This could be an empty string if the fetch failed.
	if(load.source == "") {
		return "define([]);";
	}

	var context = {
		packages: [],
		loader: this,
		// places we
		paths: {},
		versions: {}
	};
	var pkg = {origFileUrl: load.address, fileUrl: load.address};

	crawl.processPkgSource(context, pkg, load.source);

	return crawl.deps(context, pkg, true).then(function(){
		// clean up packages so everything is unique
		var names = {};
		var packages = [];
		utils.forEach(context.packages, function(pkg, index){
			if(!packages[pkg.name+"@"+pkg.version]) {
				if(pkg.browser){
					delete pkg.browser.transform;
				}
				packages.push({
					name: pkg.name,
					version: pkg.version,
					fileUrl: pkg.fileUrl,
					main: pkg.main,
					system: convertSystem(context, pkg, pkg.system, index === 0 ),
					globalBrowser: convertBrowser( pkg, pkg.globalBrowser ),
					browser: convertBrowser( pkg,  pkg.browser )
				});
				packages[pkg.name+"@"+pkg.version] = true;
			}
		});
		var configDependencies = ['@loader','npm-extension'].concat(configDeps.call(loader, pkg));
		var pkgMain = utils.pkg.hasDirectoriesLib(pkg) ?
			convertName(context, pkg, false, true, pkg.name+"/"+utils.pkg.main(pkg)) :
			utils.pkg.main(pkg);

		return "define("+JSON.stringify(configDependencies)+", function(loader, npmExtension){\n" +
			"npmExtension.addExtension(loader);\n"+
		    (pkg.main ? "if(!loader.main){ loader.main = "+JSON.stringify(pkgMain)+"; }\n" : "") +
			"loader._npmExtensions = [].slice.call(arguments, 2);\n" +
			"("+translateConfig.toString()+")(loader, "+JSON.stringify(packages, null, " ")+");\n" +
		"});";
	});
};

// Translate helpers ===============
// Given all the package.json data, these helpers help convert it to a source.
function convertSystem(context, pkg, system, root) {
	if(!system) {
		return system;
	}
	if(system.meta) {
		system.meta = convertPropertyNames(context, pkg, system.meta, root);
	}
	if(system.map) {
		system.map = convertPropertyNamesAndValues(context, pkg, system.map, root);
	}
	if(system.paths) {
		system.paths = convertPropertyNames(context, pkg, system.paths, root);
	}
	// needed for builds
	if(system.buildConfig) {
		system.buildConfig = convertSystem(context, pkg, system.buildConfig, root);
	}
	return system;
}

// converts only the property name
function convertPropertyNames (context, pkg, map , root) {
	if(!map) {
		return map;
	}
	var clone = {};
	for(var property in map ) {
		clone[convertName(context, pkg, map, root, property)] = map[property];
		// do root paths b/c we don't know if they are going to be included with the package name or not.
		if(root) {
			clone[convertName(context, pkg, map, false, property)] = map[property];
		}
	}
	return clone;
}

// converts both property name and value
function convertPropertyNamesAndValues (context, pkg, map , root) {
	if(!map) {
		return map;
	}
	var clone = {}, val;
	for(var property in map ) {
		val = map[property];
		clone[convertName(context, pkg, map, root, property)] = typeof val === "object"
			? convertPropertyNamesAndValues(context, pkg, val, root)
			: convertName(context, pkg, map, root, val);
	}
	return clone;
}

function convertName (context, pkg, map, root, name) {
	var parsed = utils.moduleName.parse(name, pkg.name),
		depPkg, requestedVersion;
	if( name.indexOf("#") >= 0 ) {

		if(parsed.packageName === pkg.name) {
			parsed.version = pkg.version;
		} else {
			// Get the requested version's actual version.
			requestedVersion = crawl.getDependencyMap(context.loader, pkg, root)[parsed.packageName].version;
			depPkg = context.versions[parsed.packageName][requestedVersion];
			parsed.version = depPkg.version;
		}
		return utils.moduleName.create(parsed);

	} else {
		if(root && name.substr(0,2) === "./" ) {
			return name.substr(2);
		} else {
			// this is for a module within the package
			if (name.substr(0,2) === "./" ) {
				return utils.moduleName.create({
					packageName: pkg.name,
					modulePath: name,
					version: pkg.version,
					plugin: parsed.plugin
				});
			} else {
				// TODO: share code better!
				// SYSTEM.NAME
				if(  pkg.name === parsed.packageName || ( (pkg.system && pkg.system.name) === parsed.packageName) ) {
					depPkg = pkg;
				} else {
					var requestedProject = crawl.getDependencyMap(context.loader, pkg, root)[parsed.packageName];
					if(!requestedProject) {
						warn(name);
						return name;
					}
					requestedVersion = requestedProject.version;
					depPkg = crawl.matchedVersion(context, parsed.packageName, requestedVersion);
					// If we still didn't find one just use the first available version.
					if(!depPkg) {
						var versions = context.versions[parsed.packageName];
						depPkg = versions && versions[requestedVersion];
					}
				}
				// SYSTEM.NAME
				if(depPkg.system && depPkg.system.name) {
					parsed.packageName = depPkg.system.name;
				}

				parsed.version = depPkg.version;
				if(!parsed.modulePath) {
					parsed.modulePath = utils.pkg.main(depPkg);
				}
				return utils.moduleName.create(parsed);
			}

		}

	}
}


/**
 * Converts browser names into actual module names.
 *
 * Example:
 *
 * ```
 * {
 * 	 "foo": "browser-foo"
 *   "traceur#src/node/traceur": "./browser/traceur"
 *   "./foo" : "./foo-browser"
 * }
 * ```
 *
 * converted to:
 *
 * ```
 * {
 * 	 // any foo ... regardless of where
 *   "foo": "browser-foo"
 *   // this module ... ideally minus version
 *   "traceur#src/node/traceur": "transpile#./browser/traceur"
 *   "transpile#./foo" : "transpile#./foo-browser"
 * }
 * ```
 */
function convertBrowser(pkg, browser) {
	if(typeof browser === "string") {
		return browser;
	}
	var map = {};
	for(var fromName in browser) {
		convertBrowserProperty(map, pkg, fromName, browser[fromName]);
	}
	return map;
}


function convertBrowserProperty(map, pkg, fromName, toName) {
	var packageName = pkg.name;

	var fromParsed = utils.moduleName.parse(fromName, packageName),
		  toParsed = toName  ? utils.moduleName.parse(toName, packageName): "@empty";

	map[utils.moduleName.create(fromParsed)] = utils.moduleName.create(toParsed);
}

// Dependencies from a package.json file specified in `system.configDependencies`
function configDeps(pkg) {
	var deps = [];
	if(pkg.system && pkg.system.configDependencies) {
		deps = deps.concat(pkg.system.configDependencies);
	}
	if(this.configDependencies) {
		deps = deps.concat(this.configDependencies);
	}
	return deps;
}


var translateConfig = function(loader, packages){
	var g = loader.global;
	if(!g.process) {
		g.process = {
			cwd: function(){},
			env: {
				NODE_ENV: loader.env
			}
		};
	}

	if(!loader.npm) {
		loader.npm = {};
		loader.npmPaths = {};
		loader.globalBrowser = {};
	}
	loader.npmPaths.__default = packages[0];
	var lib = packages[0].system && packages[0].system.directories && packages[0].system.directories.lib;


	var setGlobalBrowser = function(globals, pkg){
		for(var name in globals) {
			loader.globalBrowser[name] = {
				pkg: pkg,
				moduleName: globals[name]
			};
		}
	};
	var setInNpm = function(name, pkg){
		if(!loader.npm[name]) {
			loader.npm[name] = pkg;
		}
		loader.npm[name+"@"+pkg.version] = pkg;
	};
	var forEach = function(arr, fn){
		var i = 0, len = arr.length;
		for(; i < len; i++) {
			fn.call(arr, arr[i]);
		}
	};
	forEach(packages, function(pkg){
		if(pkg.system) {
			// don't set system.main
			var main = pkg.system.main;
			delete pkg.system.main;
			loader.config(pkg.system);
			pkg.system.main = main;

		}
		if(pkg.globalBrowser) {
			setGlobalBrowser(pkg.globalBrowser, pkg);
		}
		var systemName = pkg.system && pkg.system.name;
		if(systemName) {
			setInNpm(systemName, pkg);
		} else {
			setInNpm(pkg.name, pkg);
		}
		if(!loader.npm[pkg.name]) {
			loader.npm[pkg.name] = pkg;
		}
		loader.npm[pkg.name+"@"+pkg.version] = pkg;
		var pkgAddress = pkg.fileUrl.replace(/\/package\.json.*/,"");
		loader.npmPaths[pkgAddress] = pkg;
	});
	forEach(loader._npmExtensions || [], function(ext){
		// If there is a systemConfig use that as configuration
		if(ext.systemConfig) {
			loader.config(ext.systemConfig);
		}
	});
};

var warn = (function(){
	var warned = {};
	return function(name){
		if(!warned[name]) {
			warned[name] = true;
			var warning = "WARN: Could not find " + name + " in node_modules. Ignoring.";
			if(typeof steal !== "undefined" && steal.dev && steal.dev.warn) steal.dev.warn(warning)
			else if(console.warn) console.warn(warning);
			else console.log(warning);
		}
	};
})();
