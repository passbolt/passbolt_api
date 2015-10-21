var cloneSteal = function(System){
	var loader = System || this.System;
	return makeSteal(this.addSteal(loader.clone()));
};

var makeSteal = function(System){
	
	System.set('@loader', System.newModule({'default':System, __useDefault: true}));
		
	var configDeferred,
		devDeferred,
		appDeferred;

	var steal = function(){
		var args = arguments;
		var afterConfig = function(){
			var imports = [];
			var factory;
			each(args, function(arg){
				if(isString(arg)) {
					imports.push( steal.System['import']( normalize(arg) ) );
				} else if(typeof arg === "function") {
					factory = arg;
				}
			});
			
			var modules = Promise.all(imports);
			if(factory) {
				return modules.then(function(modules) {
			        return factory && factory.apply(null, modules);
			   });
			} else {
				return modules;
			}
		};
		if(System.env === "production") {
			return afterConfig();
		} else {
			// wait until the config has loaded
			return configDeferred.then(afterConfig,afterConfig);
		}
		
	};
	
	steal.System = System;
	steal.parseURI = parseURI;
	steal.joinURIs = joinURIs;
	steal.normalize = normalize;
