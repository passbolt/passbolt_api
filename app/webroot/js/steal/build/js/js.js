if(!steal.build){
	steal.build = {};	
}
steal('steal','steal/build/css',function( steal ) {
	
	var js = steal.build.js = {};
	/**
	 * Create package's content.
	 * 
	 * @param {Array} files like:
	 * 
	 *     [{rootSrc: "jquery/jquery.js", text: "var a;", baseType: "js"}]
	 * 
	 * @param {Object} dependencies like:
	 * 
	 *      {"package/package.js": ['jquery/jquery.js']}
	 *      
	 * essentially, things that depend on the things in the package will
	 * wait until the package has been loaded
	 * 
	 * @param {String} cssPackage the css package name, added as dependency if
	 * there is css in files.
	 * 
	 * @param {Array} exclude an array of files to exclude from the package
	 * 
	 * @return {Object} an object with the css and js 
	 * code that make up this package unminified
	 * 
	 *     {
	 *       js: "steal.loading('plugin1','plugin2', ... )"+
	 *           "steal({src: 'package/package.js', has: ['jquery/jquery.js']})"+
	 *           "plugin1 content"+
	 *           "steal.loaded('plugin1')",
	 *       css : "concated css content"
	 *     }
	 * 
	 */
	js.makePackage = function(files, dependencies, cssPackage, exclude){
		// put it somewhere ...
		// add to dependencies ...
		// seperate out css and js
		exclude = exclude || [];
		var jses = [],
			csses = [],
			lineMap = {},
			lineNum = 0,
			numLines = function(text){
				var matches = text.match(/\n/g);
				return matches? matches.length + 1 : 1
			};
				
		// if even one file has compress: false, we can't compress the whole package at once
		var canCompressPackage = true;
		files.forEach(function(file){
			if(file.minify === false){
				canCompressPackage = false;
			}
		});
		if(!canCompressPackage){
			files.forEach(function(file){
				if(file.buildType == 'js'){
					var source = steal.build.js.clean(file.text);
					if(file.minify !== false){
						try{
							source = steal.build.js.minify(source);
						} catch(error){
							print("ERROR minifying "+file.id+"\n"+error.err)
						}
						
					}
					file.text = source;
				}
			});
		}
		
		files.forEach(function(file){
			if ( file.packaged === false ) {

				steal.print('   not packaging ' + file.id);
				
				return;
			}
			
			// ignore
			if ( file.ignore || (exclude.indexOf(''+file.id) != -1)) {
				steal.print('   ignoring ' + file.id);
				return;
			}
			
			
			if(file.buildType == 'js'){
				jses.push(file)
			} else if(file.buildType == 'css'){
				csses.push(file)
			} else {
				//steal.print('no buildType!!')
			}
		})
		// add to dependencies
		if(csses.length && dependencies){
			dependencies[cssPackage] = csses.map(function(css){
				return css.id;
			})
		}
		
		// this now needs to handle css and such
		var loadingCalls = jses.map(function(file){
			return file.id;
		});
		//create the dependencies ...
		var dependencyCalls = [];
		for (var key in dependencies){
			dependencyCalls.push( 
				"steal({id: '"+key+"', waits: true, has: ['"+dependencies[key].join("','")+"']})"
			)
		}
		// make 'loading'
		var code = ["steal.has('"+loadingCalls.join("','")+"')"];
		// add dependencies
		code.push.apply(code,dependencyCalls);
		code.push("steal.pushPending()")
		
		lineNum += code.length
		// add js code
		jses.forEach(function(file){
			
			code.push( file.text, "steal.executed('"+file.id+"')" );
			lineMap[lineNum] = file.id+"";
			var linesCount = numLines(file.text)+1;
			lineNum += linesCount;
		});
		
		var jsCode = code.join(";\n") + ";\nsteal.popPending();\n";
		
		if(canCompressPackage){
			jsCode = steal.build.js.clean(jsCode);
			jsCode = steal.build.js.minify(jsCode,{currentLineMap: lineMap});
		}
		
		var csspackage = steal.build.css.makePackage(csses, cssPackage);
		
		return {
			js: jsCode,
			css: csspackage
		}
	}
}).then('./jsminify');
