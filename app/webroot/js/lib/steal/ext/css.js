if( steal.config('env') === 'production' ) {
	exports.fetch = function(load) {
		// return a thenable for fetching (as per specification)
		// alternatively return new Promise(function(resolve, reject) { ... })
		var cssFile = load.address;

		var link = document.createElement('link');
		link.rel = 'stylesheet';
		link.href = cssFile;

		document.head.appendChild(link);
		return "";
	};
} else {
	exports.instantiate = function(load) {
		var loader = this;

		load.metadata.deps = [];
		load.metadata.execute = function(){
			var source = load.source+"/*# sourceURL="+load.address+" */";
			source = source.replace(/url\(['"]?([^'"\)]*)['"]?\)/g, function( whole, part ) {
				return "url(" + steal.joinURIs( load.address, part) + ")";
			});

			if(load.source && typeof document !== "undefined") {
				var doc = document.head ? document : document.getElementsByTagName ?
					document : document.documentElement;

				var head = doc.head || doc.getElementsByTagName('head')[0],
					style = document.createElement('style');

				if(!head) {
					head = document.createElement("head");
					doc.insertBefore(head, doc.firstChild);
				}


				// make source load relative to the current page

				style.type = 'text/css';

				if (style.styleSheet){
					style.styleSheet.cssText = source;
				} else {
					style.appendChild(document.createTextNode(source));
				}
				head.appendChild(style);

				if(loader.has("live-reload")) {
					var cssReload = loader.import("live-reload", { name: "$css" });
					Promise.resolve(cssReload).then(function(reload){
						loader.import(load.name).then(function(){
							reload.once(load.name, function(){
								head.removeChild(style);
							});
						});
					});
				}
			}

			return System.newModule({source: source});
		};
		load.metadata.format = "css";
	};

}

exports.buildType = "css";
exports.includeInBuild = true;
