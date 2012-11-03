;
(function( steal ) {

	var extend = function( d, s ) {
		for ( var p in s ) d[p] = s[p];
		return d;
	};


	
	var copy = function( jFile1, jFile2 ) {
		var fin = new java.io.FileInputStream(jFile1);
		var fout = new java.io.FileOutputStream(jFile2);

		// Transfer bytes from in to out
		var data = java.lang.reflect.Array.newInstance(java.lang.Byte.TYPE, 1024);
		var len = 0;
		while ((len = fin.read(data)) > 0 ) {
			fout.write(data, 0, len);
		}
		fin.close();
		fout.close();
	}
	var addDir = function( dirObj, out, replacePath ) {
		var files = dirObj.listFiles();
		var tmpBuf = java.lang.reflect.Array.newInstance(java.lang.Byte.TYPE, 1024);

		for ( var i = 0; i < files.length; i++ ) {
			if ( files[i].isDirectory() ) {
				addDir(files[i], out, replacePath);
				continue;
			}
			var inarr = new java.io.FileInputStream(files[i].getAbsolutePath());
			var zipPath = files[i].getPath().replace(replacePath, "").replace("\\", "/")
			if (/\.git|\.zip/.test(zipPath) ) continue;
			print(zipPath)
			out.putNextEntry(new java.util.zip.ZipEntry(zipPath));
			var len;
			while ((len = inarr.read(tmpBuf)) > 0 ) {
				out.write(tmpBuf, 0, len);
			}
			out.closeEntry();
			inarr.close();
		}
	}
	extend(steal.URI.prototype, {
		mkdir: function() {
			var out = new java.io.File(''+this)
			out.mkdir();
		},
		mkdirs: function() {
			var out = new java.io.File(''+this)
			out.mkdirs();
		},
		exists: function() {
			var exists = (new java.io.File(''+this)).exists();
			return exists;
		},
		copyTo: function( dest, ignore ) {
			var me = new java.io.File(''+this)
			var you = new java.io.File(dest);
			if ( me.isDirectory() ) {
				var children = me.list();
				for ( var i = 0; i < children.length; i++ ) {
					var newMe = new java.io.File(me, children[i]);
					var newYou = new java.io.File(you, children[i]);
					if ( ignore && ignore.indexOf("" + newYou.getName()) != -1 ) {
						continue;
					}
					if ( newMe.isDirectory() ) {
						newYou.mkdir();
						new steal.URI("" + newMe.path).copyTo("" + newYou.path, ignore)
					} else {
						copy(newMe, newYou)
					}
				}
				return this;
			}
			copy(me, you)
			return this;
		},
		moveTo: function(dest){
			return new java.io.File(''+this).renameTo(new java.io.File(dest));
		},
		setExecutable: function(){
			var me = new java.io.File(''+this)
			me.setExecutable(true);
			return this;
		},
		save: function( src, encoding ) {
			var fout = new java.io.FileOutputStream(new java.io.File(''+this));

			var out = new java.io.OutputStreamWriter(fout, "UTF-8");
			var s = new java.lang.String(src || "");

			var text = new java.lang.String((s).getBytes(), encoding || "UTF-8");
			out.write(text, 0, text.length());
			out.flush();
			out.close();
		},
		download_from: function( address ) {
			var input =
			new java.io.BufferedInputStream(
			new java.net.URL(address).openStream());

			bout = new java.io.BufferedOutputStream(
			new java.io.FileOutputStream(''+this), 1024);
			var data = java.lang.reflect.Array.newInstance(java.lang.Byte.TYPE, 1024);
			var num_read = 0;
			while ((num_read = input.read(data, 0, 1024)) >= 0 ) {
				bout.write(data, 0, num_read);
			}
			bout.close();
		},
		basename: function() {
			return (''+this).match(/\/?([^\/]*)\/?$/)[1];
		},
		remove: function() {
			var file = new java.io.File(''+this);
			file["delete"]();
		},
		isFile: function() {
			var file = new java.io.File(''+this);
			return file.isFile();
		},
		removeDir: function() {
			var me = new java.io.File(''+this)
			if ( me.exists() ) {
				var files = me.listFiles();
				for ( var i = 0; i < files.length; i++ ) {
					if ( files[i].isDirectory() ) {
						new steal.File(files[i]).removeDir();
					} else {
						files[i]["delete"]();
					}
				}
			}
			me["delete"]()
		},
		zipDir: function( name, replacePath ) {
			var dirObj = new java.io.File(''+this);
			var out = new java.util.zip.ZipOutputStream(new java.io.FileOutputStream(name));
			addDir(dirObj, out, replacePath);
			out.close();
		},
		contents: function( func, current ) {
			
			var me = new java.io.File(''+this),
				listOfFiles = me.listFiles();
				
			if ( listOfFiles == null ) {
				//print("there is nothing in " + this.path)
				return;
			}
			for ( var i = 0; i < listOfFiles.length; i++ ) {
				func(listOfFiles[i].getName(), listOfFiles[i].isFile() ? "file" : "directory", current)
			}
			return listOfFiles;
		},
		/**
		 * Returns the path to the root jmvc folder
		 */
		pathToRoot: function( isFile ) {
			var root = steal.URI.getRoot(),
				rootFolders = root.split(/\/|\\/),
				targetDir = rootFolders[rootFolders.length-1]
				i = 0,
				adjustedPath = (targetDir? (''+this).replace(new RegExp(".*"+targetDir+"\/?"),""): 
					''+this),
				myFolders = adjustedPath.split(/\/|\\/);

			//for each .. in loc folders, replace with steal folder
			if ( myFolders[i] == ".." ) {
				while ( myFolders[i] == ".." ) {
					myFolders[i] = rootFolders.pop();
					i++;
				}
			} else {
				for ( i = 0; i < myFolders.length - 1; i++ ) {
					myFolders[i] = ".."
				}
			}
			myFolders.pop();

			if (!isFile ) {
				myFolders.push('..')
			}

			return myFolders.join("/")
		}
	});

	/**
	 * If there's a CMD system variable (like "documentjs/document.bat"), 
	 * assumes the root is the folder one below the scripts folder.
	 * 
	 * Otherwise, assumes the current directory IS the root jmvc folder (framework)
	 * 
	 */
	steal.URI.getRoot = function() {
		var cwd = steal.URI.cwd(),
			cmd = ""+java.lang.System.getProperty("cmd"),
			root = cwd,
			relativeRoot;
		
		if(cmd) {
			relativeRoot = cmd.replace(/\/?[^\/]*\/[^\/]*$/, "")
			root = cwd+'/'+relativeRoot;
		} 
		return root;
	}
	steal.URI.cwdURL = function() {
		return new java.io.File("").toURL().toString();
	}
	steal.URI.cwd = function() {
		return String(new java.io.File('').getAbsoluteFile().toString());
	}
	
	var isArray = function( arr ) {
		return Object.prototype.toString.call(arr) === "[object Array]"
	}
	
	/**
	 * Converts args or a string into options
	 * @param {Object} args
	 * @param {Object} options something like 
	 * {
	 * name : {
	 * 	shortcut : "-n",
	 * 	args: ["first","second"]
	 * },
	 * other : 1
	 * }
	 */
	steal.opts = function( args, options ) {
		if ( typeof args == 'string' ) {
			args = args.split(' ')
		}
		if (!isArray(args) ) {
			return args
		}

		var opts = {};
		//normalizes options
		(function() {
			var name, val, helper
			for ( name in options ) {
				val = options[name];
				if ( isArray(val) || typeof val == 'number' ) {
					options[name] = {
						args: val
					};
				}
				options[name].name = name;
				//move helper
				helper = options[name].helper || name.substr(0, 1);

				options[helper] = options[name]
			}
		})();
		var latest, def;
		for ( var i = 0; i < args.length; i++ ) {
			if ( args[i].indexOf('-') == 0 && (def = options[args[i].substr(1)]) ) {
				latest = def.name;
				opts[latest] = true;
				//opts[latest] = []
			} else {
				if ( opts[latest] === true ) {
					opts[latest] = args[i]
				} else {
					if (!isArray(opts[latest]) ) {
						opts[latest] = [opts[latest]]
					}
					opts[latest].push(args[i])
				}

			}
		}

		return opts;
	}
	
	// a way to turn off printing (mostly for testing purposes)
	steal.print = function(){

		if(typeof STEALPRINT == "undefined" || STEALPRINT !== false){
			print.apply(null, arguments)
		}
	}
	

})(steal);
