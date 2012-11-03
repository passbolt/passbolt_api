steal('steal/build/pluginify', function() {
	var inexcludes = function(excludes, src) {
			for(var i = 0; i < excludes.length; i++) {
				if(src.indexOf(excludes[i]) !== -1) {
					return true;
				}
			}
			return false;
		},
		getDependencies = function(file, options, callback) {
			steal.build.open("steal/rhino/empty.html", {
				startFile : file,
				skipCallbacks: true
			}, function(opener){
				var ret = [];
				opener.each(function(stl, text) {
					var src = stl.src.toString();
					if(!inexcludes(options.exclude || [], src)) {
						ret.push(src);
					}
				});
				callback(ret);
			}, null);
		};

	steal.build.extract = function(plugins, options) {
		var ops = steal.extend({ global : 'jQuery' }, options);
		steal.File(ops.out).mkdirs();
		print('Extracting plugin files');
		for(var file in plugins) {
			var content = "";

			getDependencies(file, ops, function(steals) {
				console.log(steals);
//				if(steals.length > 1) {
//					content += "// Dependencies:\n//\n";
//				}
//				steals.forEach(function(stl) {
//					if(stl.rootSrc !== file) {
//						content += "//    - " + (plugins[stl.rootSrc] || stl.rootSrc) + "\n";
//					}
//				});
//				if(steals.length > 1) {
//					content += "\n";
//				}
//				content += steal.build.pluginify.content({ rootSrc : file }, ops);
//				new steal.File(options.out + plugins[file]).save(content);
//				print('  > ' + file + ' -> ' + plugins[file]);
			});
		}
	}
});
