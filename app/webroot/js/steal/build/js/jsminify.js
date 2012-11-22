steal('steal','steal/parse',function(steal, parse){
	
	var js = steal.build.js;
	
	var stealDevTest = /steal\.dev/;
	// removes  dev comments from text
	js.clean = function( text, file ) {
		var parsedTxt = String(java.lang.String(text)
			.replaceAll("(?s)\/\/!steal-remove-start(.*?)\/\/!steal-remove-end", ""));
		
		// the next part is slow, try to skip if possible
		// if theres not a standalone steal.dev, skip

		if(! stealDevTest.test(parsedTxt) ) {
			return parsedTxt;
		}	
		
		var positions = [],
		   	p,
		    tokens, 
			i, 
			position;

		try{
			p = parse(parsedTxt);
		} catch(e){
			print("Parsing problem");
			print(e);
			return parsedTxt;
		}

		while (tokens = p.until(["steal", ".", "dev", ".", "log", "("], ["steal", ".", "dev", ".", "warn", "("])) {
			var end = p.partner("(");
			positions.push({
				start: tokens[0].from,
				end: end.to
			})
		}
		// go through in reverse order
		for (i = positions.length - 1; i >= 0; i--) {
			position = positions[i];
			parsedTxt = parsedTxt.substring(0, position.start) + parsedTxt.substring(position.end)
		}
		return parsedTxt;
	};

	/**
	 * Minifies JavaScript
	 * @param {Source} source - the JS source code
	 * @param {Object} [options] - options to configure the minification:
	 *   - quiet - should the compression happen w/o errors
	 *   - compressor - which minification engine, defaults to localClosure
	 *   - currentLineMap - a map of lines to JS files, used for error reporting when minifying
	 *     several files at once. EX:
	 * 
	 *         {0: "foo.js", 100: "bar.js"}
	 */
	js.minify = function(source, options){		
		// return source;
		// get the compressor
		options = options || {};
		var compressor = js.minifiers[options.compressor || "localClosure"]()
		
		if(source){
			// return source; //""+compressor( source, true, options.currentLineMap )
			return ""+compressor( source, true, options.currentLineMap )
		} else {
			return  compressor
		}
	}

	//various minifiers
	js.minifiers = {
		// needs shrinksafe.jar at steal/build/javascripts/shrinksafe.jar
		shrinksafe: function() {
			steal.print("steal.compress - Using ShrinkSafe");
			// importPackages/Class doesn't really work
			var URLClassLoader = Packages.java.net.URLClassLoader,
				URL = java.net.URL,
				File = java.io.File,
				ss = new File("steal/build/javascripts/shrinksafe.jar"),
				ssurl = ss.toURL(),
				urls = java.lang.reflect.Array.newInstance(URL, 1);
			urls[0] = new URL(ssurl);

			var clazzLoader = new URLClassLoader(urls),
				mthds = clazzLoader.loadClass("org.dojotoolkit.shrinksafe.Compressor").getDeclaredMethods(),
				rawCompress = null;

			//iterate through methods to find the one we are looking for
			for ( var i = 0; i < mthds.length; i++ ) {
				var meth = mthds[i];
				if ( meth.toString().match(/compressScript\(java.lang.String,int,int,boolean\)/) ) {
					rawCompress = meth;
				}
			}
			return function( src ) {
				var zero = new java.lang.Integer(0),
					one = new java.lang.Integer(1),
					tru = new java.lang.Boolean(false),
					script = new java.lang.String(src);
				return rawCompress.invoke(null, script, zero, one, tru);
			};
		},
		closureService: function() {
			steal.print("steal.compress - Using Google Closure Service");

			return function( src ) {
				var xhr = new XMLHttpRequest();
				xhr.open("POST", "http://closure-compiler.appspot.com/compile", false);
				xhr.setRequestHeader["Content-Type"] = "application/x-www-form-urlencoded";
				var params = "js_code=" + encodeURIComponent(src) + "&compilation_level=WHITESPACE_ONLY" + "&output_format=text&output_info=compiled_code";
				xhr.send(params);
				return "" + xhr.responseText;
			};
		},
		uglify: function() {
			steal.print("steal.compress - Using Uglify");
			return function( src, quiet ) {
				var rnd = Math.floor(Math.random() * 1000000 + 1),
					origFileName = "tmp" + rnd + ".js",
					origFile = new steal.URI(origFileName);

				origFile.save(src);


				var outBaos = new java.io.ByteArrayOutputStream(),
					output = new java.io.PrintStream(outBaos);
					
				runCommand("node", "steal/build/js/uglify/bin/uglifyjs", origFileName,
					{ output: output }
				);
			
				origFile.remove();

				return outBaos.toString();
			};
		},
		localClosure: function() {
			//was unable to use SS import method, so create a temp file
			//steal.print("steal.compress - Using Google Closure app");
			return function( src, quiet, currentLineMap ) {
				var rnd = Math.floor(Math.random() * 1000000 + 1),
					filename = "tmp" + rnd + ".js",
					tmpFile = new steal.URI(filename);

				tmpFile.save(src);

				var outBaos = new java.io.ByteArrayOutputStream(),
					output = new java.io.PrintStream(outBaos),
					options = {
						err: '',
						output: output
					};
				if ( quiet ) {
					runCommand("java", "-jar", "steal/build/js/compiler.jar", "--compilation_level", "SIMPLE_OPTIMIZATIONS", 
						"--warning_level", "QUIET", "--js", filename, options);
				} else {
					runCommand("java", "-jar", "steal/build/js/compiler.jar", "--compilation_level", "SIMPLE_OPTIMIZATIONS", 
						"--js", filename, options);
				}
				// print(options.err);
				// if there's an error, go through the lines and find the right location
				if( /ERROR/.test(options.err) ){
					if (!currentLineMap) {
						throw options
					}
					else {
						print("HOLLER")
						var errMatch;
						while (errMatch = /\:(\d+)\:\s(.*)/g.exec(options.err)) {
							
							var lineNbr = parseInt(errMatch[1], 10), 
								realLine,
								error = errMatch[2];
								
							var lastNum, lastId; 
							print(lineNbr);
							for( var lineNum in currentLineMap ) {
								if( lineNbr < parseInt( lineNum) ){
									break;
								}
								// print("checked "+lineNum+" "+currentLineMap[lineNum])
								lastNum = parseInt(lineNum);
								lastId = currentLineMap[lineNum];
							}
							
							realLine = lineNbr - lastNum;
							
							steal.print('ERROR in ' + lastId + ' at line ' + realLine + ': ' + error + '\n');
							
							
							var text = readFile(lastId), 
								split = text.split(/\n/), 
								start = realLine - 2, 
								end = realLine + 2;
							if (start < 0) 
								start = 0;
							if (end > split.length - 1) 
								end = split.length - 1;
							steal.print(split.slice(start, end).join('\n') + '\n')
						}
					}
				}
				tmpFile.remove();
				return ""+outBaos.toString();
			};
		},
		yui: function() {
			// needs yuicompressor.jar at steal/build/js/yuicompressor.jar
			steal.print("steal.compress - Using YUI compressor");

			return function( src ) {
				var rnd = Math.floor(Math.random() * 1000000 + 1),
					filename = "tmp" + rnd + ".js",
					tmpFile = new steal.URI(filename);

				tmpFile.save(src);

				var outBaos = new java.io.ByteArrayOutputStream(),
					output = new java.io.PrintStream(outBaos);
					
				runCommand(
					"java", 
					"-jar", 
					"steal/build/js/yuicompressor.jar", 
					"--charset",
					"utf-8",
					filename, 
					{ output: output }
				);
			
				tmpFile.remove();

				return outBaos.toString();
			};
		}
	};
	
})
