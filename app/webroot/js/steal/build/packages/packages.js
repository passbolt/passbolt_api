// open all apps
// go through and mark everything in 'core' as packaged

// indicate which sub-packages each other app needs
// steal({ id: 'packagea', needs: 'abc.js'})

// TODO
//  - make it able to work with specific files

if(!steal.build){
	steal.build = {};	
}
steal('steal','steal/build/open','steal/build/apps','steal/get/json.js',function(s){

	var apps = steal.build.apps,
		build = steal.build, 
		packages =
	
	/**
	 * builds an app, and pulls out packages
	 * @param {Object} app
	 */
	steal.build.packages = function(app, buildOptions){
		
		// options for packaging
		var options = {
			// the files opened
			files : {},
			// each app's first file
			appFiles : [],
			// don't minify at first (will be faster)
			minify : false
		};
		buildOptions = buildOptions || {};
		buildOptions.depth = buildOptions.depth || Infinity;
		// open the core app
		apps._open(app, options, function(options, opener){
			
			// the folder are build files will go in
			var to = buildOptions.to || ""+s.URI(opener.firstSteal.options.id).dir(),
				appNamesToName = {},
				usedNames = {},
				// a helper function that translates between an 
				// app's name and where we are building it to
				appNamesToMake = function(appNames){
					
					//remove js if it's there
					appNames = appNames.map(function(appName){
						return appName.replace(".js","")
					});
					var expanded = appNames.join('-');
					// check map
					if(appNamesToName[expanded]){
						return appNamesToName[expanded];
					}
					// try with just the last part
					var shortened = appNames.map(function(l){
						return s.URI(l).filename()
					}).join('-')
					if(!usedNames[shortened]){
						usedNames[shortened] = true;
					return appNamesToName[expanded] = to + "/packages/"+shortened;
					} else {
						return appNamesToName[expanded] = to + "/packages/"+expanded.replace(/\//g,'_') ;
					}
				},
				filterCode = function(code, type) {
					return buildOptions.minify
						? build[type].minify(code)
						: code;
				};
			
			// make the packages folder
			s.URI(to+"/packages").mkdirs();
			
			// get packages loaded, packages need to be rootSrc style url
			var packs = opener.steal.packages(),
				// will house the master app's files (so we can build them later)
				masterFiles = [];
				
			// go through every file and mark it packaged
			for(var name in options.files){
				options.files[name].packaged = true;
				masterFiles.push(options.files[name])
			}
			
			// Make the packaged!
			// TODO: figure out how to not write it needs this
			
			// change options for loading packages
			// we don't want to change pages, use the current page
			options.newPage = false;
			
			// minify each file we load
			options.minify = false;
			
			// open packages and add their dependencies 
			apps.open(packs, options, function(options){
				
				// order files 
				apps.order(options);
				
				var sharing,
					// makes contains an hash of packageSrc to
					// the object that we will pass to steal.p.make
					// like:
					//  {
					//    package1 : {id: package1, needs: [shared1]}
					//  }
					// this is used so when the package is stolen,
					// it will load anything it needs before it
					makes = {},
					// mappings of packaged app name to packaging file
					// this is what overwrites the loading location for packages
					maps = {},
					// a list of shares, we go through the list twice
					// b/c it is easier to populate makes
					// once we have gone through each share.
					shares = [];
				
				
				
				
				s.print("Getting Packages");
				while(sharing = apps.getMostShared(options.files)){
					shares.push(sharing);
				};
				packages.flatten(shares, buildOptions.depth);
				
				
				s.print("\nMaking Packages");
				shares.forEach(function(sharing){
					// is it a 'end' package
					var isPackage = sharing.appNames.length == 1,
						packageName = appNamesToMake(sharing.appNames);
	
					// create package
					var pack = build.js.makePackage(sharing.files.map(function(f){
						return f.stealOpts;
					}), {}, packageName+".css", buildOptions.exclude),
						hasCSS = pack.css,
						has = [];
					
					
					// 
					if(isPackage){
						s.print("  Package: "+packageName+ (hasCSS ? " js/css" : "" ) )
					} else {
						s.print("  Shared Package: "+packageName+ (hasCSS ? " js/css" : "" ))
					}
					
					sharing.files.forEach(function(f){
						s.print("  + "+f.stealOpts.id)
						if(f.stealOpts.buildType == 'js'){
							has.push(f.stealOpts.id+'')
						}
					})
					s.print(" ")
					
					s.URI(packageName+".js").save( filterCode(pack.js, 'js') );
					
					// make this steal instance
					makes[packageName+".js"] = {
						id: packageName+".js",
						needs :[],
						has : has
					}
					// if we have css
					if(hasCSS){
						// write
						// tell the js it needs this css
						makes[packageName+".js"].needs.push(packageName+".css")
						// make the css
						makes[packageName+".css"] = {
							id: packageName+".css",
							has: pack.css.srcs
						};
						s.URI(packageName+".css").save( filterCode(pack.css.code, 'css') );
						sharing.hasCSS = true;
					}
					
					
					// add to maps
					if(isPackage){
						// this should be the real file
						maps[sharing.appNames[0]+".js"] = packageName+".js";
					}
				})
				// handle depth
				
				
				
				shares.forEach(function(sharing){
					var isPackage = sharing.appNames.length == 1,
						sharePackageName = appNamesToMake(sharing.appNames);
					
					if(!isPackage){
						// add this as a needs to master
						sharing.appNames.forEach(function(appName){
							var packageName = appNamesToMake([appName])
							makes[packageName+".js"].needs
								.push(sharePackageName+".js")
							
							// add css
							if(sharing.hasCSS){
								makes[packageName+".js"].needs
									.push(sharePackageName+".css")
							}
							// also needs css!
							
						})
					}
				});
				// write production with makes
				// and maps
				
				// sort masterFiles
				buildOptions.to = buildOptions.to || ""+s.URI(app).dir();
				var destJS = ''+steal.URI(buildOptions.to).join('production.js'),
					destCSS = ''+steal.URI(buildOptions.to).join('production.css');
				s.print("Making "+destJS);
				
				var pack = build.js.makePackage(
					masterFiles.map(function(f){return f.stealOpts}),
					{}, destCSS, buildOptions.exclude);
				// prepend maps and makes ...
				// make makes
				var makeCode = [],
					mapCode;
				for(name in makes) {
					makeCode.push("Resource.make(",
						s.toJSON(makes[name]),
						");")
				}
				mapCode = "steal.packages("+s.toJSON(maps)+");"
				s.URI(destJS).save( filterCode(mapCode+makeCode.join('\n')+"\n"+pack.js, 'js') );
				if(pack.css){
					s.print("       "+destCSS);
					s.URI(destCSS).save( filterCode(pack.css.code, 'css') );
				}
			});
		});
	};

	steal.extend(packages,{
		/**
		 * Flattens the list of shares until each script has a minimal depth
		 * @param {Object} shares
		 * @param {Object} depth
		 */
		flatten : function(shares, depth){
			// make waste object
			// mark the size
			while(packages.maxDepth(shares) > depth){
				var min = packages.min(shares);
				packages.merge(shares, min);
			}
		},
		/**
		 * Merges 2 shares contents.  Shares are expected to be in the order
		 * getMostShared removes them ... by lowest depenency first.
		 * We should merge into the 'lower' dependency.
		 * 
		 * @param {Object} shares
		 * @param {Object} min
		 *     
		 *     diff : {app1 : waste, app2 : waste, _waste: 0}, 
		 *     lower: i, - the 'lower' share whos contents will be merged into, and contents should run first
		 *     higher: j  - the 'higher' share
		 */
		merge : function(shares, min){
			var lower = shares[min.lower],
				upper = shares[min.higher],
				shortName = packages.shortName;
			
			s.print("\n  Flattening "+shortName(upper.appNames)+">"+
				shortName(lower.appNames)/*+"=" + min.diff._waste*/)
			for(var appName in min.diff){
				if(appName !== '_waste' && min.diff[appName]){
					s.print("  + "+min.diff[appName]+" "+shortName([appName]))
				}
			}
			
			// remove old one
			shares.splice(min.higher,1);
			
			// merge in files, lowers should run first
			lower.files = lower.files.concat(upper.files)
			
			// merge in apps
			var apps = packages.appsHash(lower);
			upper.appNames.forEach(function(appName){
				if(!apps[appName]){
					lower.appNames.push(appName);
				}
			})
			//lower.waste = min.diff;
		},
		/**
		 * Goes through and figures out which package has the greatest depth
		 */
		maxDepth: function(shares){
			var packageDepths = {},
				max = 0;
			shares.forEach(function(share){
				share.appNames.forEach(function(appName){
					packageDepths[appName] = (!packageDepths[appName] ? 1 : packageDepths[appName] +1 );
					max = Math.max(packageDepths[appName], max)
				});
			});
			return max;
		},
		/**
		 * Goes through every combination of shares and returns the one with the smallest difference.
		 * Shares can have a waste property that has how much waste the share currently has 
		 * accumulated.
		 * @param {Object} shares
		 * @return {min}
		 *     {
		 *       waste : 123213, // the amount of waste in the composite share
		 *       lower : share, // the more base share, whos conents should be run first
		 *       higher: share // the less base share, whos contents should run later
		 *     }
		 */
		min: function(shares){
			var min = {diff: {
				_waste: Infinity
			}};
			for(var i = 0; i < shares.length; i++){
				var shareA = shares[i];
				if( shareA.appNames.length == 1 ){
					continue;
				}
				for(var j = i+1; j < shares.length; j++){
					var shareB = shares[j],
						diff;
					
					if( shareB.appNames.length == 1 ){
						continue;
					}
					
					diff = packages.diff(shareA, shareB);
					
					if(diff._waste < min.diff._waste){
						min = {
							diff : diff,
							lower: i,
							higher: j
						}
					}
				}
			}
			return min.waste === Infinity ? null : min;
		},
		/**
		 * returns a hash of the app names for quick checking
		 */
		appsHash : function(shared){
			var apps = {};
			shared.appNames.forEach(function(name){
				apps[name] = true;
			})
			return apps
		},
		// return a difference between one share and another
		// essentially, which apps will have the waste incured by loading
		// b
		diff: function(sharedA, sharedB){
			
			// combine files ....
			var files = sharedA.files.concat(sharedB.files),
				apps = {},
				totalWaste = 0;
			
			files.forEach(function(file){
				file.appNames.forEach(function(appName){
					apps[appName] = 0;
				})
			});
			
			for(var appName in apps){
				files.forEach(function(file){
					// check file's appName
					if(file.appNames.indexOf(appName) == -1){
						apps[appName] += file.stealOpts.text.length
					}
				})
				totalWaste += apps[appName];
			}
			apps._waste = totalWaste;
			return apps;
		},
		shortName : function(appNames){
			return appNames.map(function(l){
						return s.URI(l).filename()
					}).join('-')
		}
	})
	var p = packages;
});
