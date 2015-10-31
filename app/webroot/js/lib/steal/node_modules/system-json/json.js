/*
  SystemJS JSON Format
  Provides the JSON module format definition.
*/
function _SYSTEM_addJSON(loader) {
	var jsonTest = /^[\s\n\r]*[\{\[]/;
	var jsonExt = /\.json$/i;
	var jsExt = /\.js$/i;
	var inNode = typeof window === "undefined";

	// Add the extension to _extensions so that it can be cloned.
	loader._extensions.push(_SYSTEM_addJSON);

	// if someone has a moduleName that is .json, make sure it loads a json file
	// no matter what paths might do
	var loaderLocate = loader.locate;
	loader.locate = function(load){
	  return loaderLocate.apply(this, arguments).then(function(address){
		if(jsonExt.test(load.name)) {
			return address.replace(jsExt, "");
		}

	    return address;
	  });
	};

	// If we are in a build we should convert to CommonJS instead.
	if(inNode) {
		var loaderTranslate = loader.translate;
		loader.translate = function(load){
			if(jsonExt.test(load.name)) {
				var parsed = parse(load);
				if(parsed) {
					return "def" + "ine([], function(){\n" +
						"\treturn " + load.source + "\n});";
				}
			}

			return loaderTranslate.call(this, load);
		};
		return;
	}

	var loaderInstantiate = loader.instantiate;
	loader.instantiate = function(load) {
		var loader = this,
			parsed;

		parsed = parse(load);
		if(parsed) {
			load.metadata.format = 'json';

			load.metadata.execute = function(){
				return parsed;
			};
		}

		return loaderInstantiate.call(loader, load);
	};

	return loader;

	// Attempt to parse a load as json.
	function parse(load){
		if ( (load.metadata.format === 'json' || !load.metadata.format) && jsonTest.test(load.source)  ) {
			try {
				return JSON.parse(load.source);
			} catch(e) {}
		}

	}
}

if (typeof System !== "undefined") {
	_SYSTEM_addJSON(System);
}
