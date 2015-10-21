	steal.import = function(){
		var names = arguments;
		var loader = this.System;

		function afterConfig(){
			var imports = [];
			each(names, function(name){
				imports.push(loader.import(name));
			});
			if(imports.length > 1) {
				return Promise.all(imports);
			} else {
				return imports[0];
			}
		}

		if(!configDeferred) {
			steal.startup();
		}
		
		return configDeferred.then(afterConfig);
	};
	return steal;
