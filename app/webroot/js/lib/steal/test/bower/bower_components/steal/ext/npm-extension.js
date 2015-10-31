"format cjs";

var utils = require("./npm-utils");
exports.includeInBuild = true;

exports.addExtension = function(System){
	/**
	 * Normalize has to deal with a "tricky" situation.  There are module names like
	 * "css" -> "css" normalize like normal
	 * "./qunit" //-> "qunit"  ... could go to steal-qunit#qunit, but then everything would?
	 *
	 * isRoot?
	 *   "can-slider" //-> "path/to/main"
	 *
	 * else
	 *
	 *   "can-slider" //-> "can-slider#path/to/main"
	 */
	var oldNormalize = System.normalize;
	System.normalize = function(name, parentName, parentAddress){
		// If this is a relative module name and the parent is not an npm module
		// we can skip all of this logic.
		if(parentName && utils.path.isRelative(name) &&
		  !utils.moduleName.isNpm(parentName)) {
			return oldNormalize.call(this, name, parentName, parentAddress);
		}

		// Get the current package
		var refPkg = utils.pkg.findByModuleNameOrAddress(this, parentName, parentAddress);

		// this isn't in a package, so ignore
		if(!refPkg) {
			return oldNormalize.call(this, name, parentName, parentAddress);
		}

		// Using the current package, get info about what it is probably asking for
		var parsedModuleName = utils.moduleName.parseFromPackage(this, refPkg, name, parentName);

		// Look for the dependency package specified by the current package
		var depPkg = utils.pkg.findDep(this, refPkg, parsedModuleName.packageName);

		// This really shouldn't happen, but lets find a package.
		if (!depPkg) {
			depPkg = utils.pkg.findByName(this, parsedModuleName.packageName);
		}
		// It could be something like `fs` so check in globals
		if(!depPkg) {
			var browserPackageName = this.globalBrowser[parsedModuleName.packageName];
			if(browserPackageName) {
				parsedModuleName.packageName = browserPackageName;
				depPkg = utils.pkg.findByName(this, parsedModuleName.packageName);
			}
		}
		// It could be the root main.
		if(!depPkg && refPkg === this.npmPaths.__default && name === refPkg.main &&
		  utils.pkg.hasDirectoriesLib(refPkg)) {
			parsedModuleName.version = refPkg.version;
			parsedModuleName.packageName = refPkg.name;
			parsedModuleName.modulePath = utils.pkg.main(refPkg);
			return oldNormalize.call(this, utils.moduleName.create(parsedModuleName), parentName, parentAddress);
		}
		if( depPkg ) {
			parsedModuleName.version = depPkg.version;
			// add the main path
			if(!parsedModuleName.modulePath) {
				parsedModuleName.modulePath = utils.pkg.main(depPkg);
			}
			var moduleName = utils.moduleName.create(parsedModuleName);
			// Apply mappings, if they exist in the refPkg
			if(refPkg.system && refPkg.system.map &&
			   typeof refPkg.system.map[moduleName] === "string") {
				moduleName = refPkg.system.map[moduleName];
			}
			return oldNormalize.call(this, moduleName, parentName, parentAddress);
		} else {
			if(depPkg === this.npmPaths.__default) {
				// if the current package, we can't? have the
				// module name look like foo@bar#./zed
				var localName = parsedModuleName.modulePath ?
					parsedModuleName.modulePath+(parsedModuleName.plugin? parsedModuleName.plugin: "") :
					utils.pkg.main(depPkg);
				return oldNormalize.call(this, localName, parentName, parentAddress);
			}
			if(refPkg.browser && refPkg.browser[name]) {
				return oldNormalize.call(this, refPkg.browser[name], parentName, parentAddress);
			}
			return oldNormalize.call(this, name, parentName, parentAddress);
		}

	};


	var oldLocate = System.locate;
	System.locate = function(load){

		var parsedModuleName = utils.moduleName.parse(load.name),
			loader = this;
		// @ is not the first character
		if(parsedModuleName.version && this.npm && !loader.paths[load.name]) {
			var pkg = this.npm[parsedModuleName.packageName];
			if(pkg) {
				return oldLocate.call(this, load).then(function(address){

					var root = utils.pkg.rootDir(pkg, pkg === loader.npmPaths.__default);


					if(parsedModuleName.modulePath) {
						return utils.path.joinURIs( utils.path.addEndingSlash(root),
							parsedModuleName.plugin ? parsedModuleName.modulePath : utils.path.addJS(parsedModuleName.modulePath) );
					}

					return address;
				});
			}
		}
		return oldLocate.call(this, load);
	};

	// Given a moduleName convert it into a npm-style moduleName if it belongs
	// to a package.
	var convertName = function(loader, name){
		var pkg = utils.pkg.findByName(loader, name.split("/")[0]);
		if(pkg) {
			var parsed = utils.moduleName.parse(name, pkg.name);
			parsed.version = pkg.version;
			if(!parsed.modulePath) {
				parsed.modulePath = utils.pkg.main(pkg);
			}
			return utils.moduleName.create(parsed);
		}
		return name;
	};

	var configSpecial = {
		map: function(map){
			var newMap = {}, val;
			for(var name in map) {
				val = map[name];
				newMap[convertName(this, name)] = typeof val === "object"
					? configSpecial.map(val)
					: convertName(this, val);
			}
			return newMap;
		},
		meta: function(map){
			var newMap = {};
			for(var name in map){
				newMap[convertName(this, name)] = map[name];
			}
			return newMap;
		},
		paths: function(paths){
			var newPaths = {};
			for(var name in paths) {
				newPaths[convertName(this, name)] = paths[name];
			}
			return newPaths;
		}
	};


	var oldConfig = System.config;
	System.config = function(cfg){
		var loader = this;
		for(var name in cfg) {
			if(configSpecial[name]) {
				cfg[name] = configSpecial[name].call(loader, cfg[name]);
			}
		}
		oldConfig.apply(loader, arguments);
	};
};
