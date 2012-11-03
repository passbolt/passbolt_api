
if(!steal.build){
	steal.build = {};	
}
steal('steal','steal/build/js','steal/build/css',function( steal ) {

/**
 * 
 * ### shared
 */
	
// { "//foo.js" : {} }

	// recursively goes through steals and their dependencies.
	
	
		
		/**
		 * 
		 * 
		 */
		var apps = steal.build.apps = function( list, options ) {
			
			options = steal.opts(options || {}, {
				//folder to build to, defaults to the folder the page is in
				to: 1
			});
			
			// set the compressor globally
			//steal.build.compressor = steal.build.builders.scripts.compressors[options.compressor || "localClosure"]();
			//set defaults
			options.to = options.to || "packages/"
			// check if path exists
			var dest = steal.File(options.to);
			if(!dest.exists()){
				var dir = dest.dir();
				dest.mkdir();
			}
			var options = {
				appFiles : [],
				files : {}
			}
			// opens each app and add its dependencies to options
			steal.build.apps.open(list, options, function(options){
				apps.makePackages(options);
			})
			
		};
		
		
		// only add files to files, but recurse through fns
	steal.extend(steal.build.apps, {
		/**
		 * Opens se
		 * @param {Array} appNames
		 * @param {Object} options An object that has an appFiles array, and a files object.
		 * 
		 *     {appFiles : [], files: {}}
		 *     
		 * It can also have other properties like:
		 * 
		 *   - newPage - open in a new page or do not
		 * 
		 * @param {Function} callback(options)
		 */
		open : function(appNames,options, callback){
			options = options || {};
			options.appFiles =  options.appFiles || [];
			options.files = options.files || {};
			
			var APPS = this,
			
			i = 0,
			// calls make for each app
			// then calls makePackages once done
			callNext = function(){
				if( i< appNames.length ) {
					i++;
					APPS._open(appNames[i-1],options, callNext );
				} else {
					callback(options);
				}
			};
			callNext();
		},
		// opens one app, adds dependencies
		_open : function(appName, options, callback){
			//= Configure what we are going to load from APP name
			
			// if we have html, get  the app-name
			var html = 'steal/rhino/blank.html',
				data = {env: 'development'};
			if(appName.indexOf('.html') > 2){
				html = appName;
				appName = null;
			} else if(/\.js$/.test(appName)){
				// set as start file
				data = {startFile: appName};
				appName = appName.substr(0, appName.length-3);
			} else {
				data = {
					startFile: appName + "/" + steal.File(appName).basename() + ".js"
				}
			}
			steal.print("Opening " + ( appName || html) );
			
			// use last steal to load page
			if(options.newPage === false && steal.build.apps.lastSteal){
				// move steal back
				var curSteal = window.steal,
					newSteal = window.steal= this.lastSteal;
				// listen to when this is done
				window.steal.one("end", function(rootSteal){
					steal.print("  adding dependencies");
					
					options.appFiles.push(  apps.addDependencies(rootSteal, options.files, appName )  );
					
					// set back steal
					window.steal = curSteal;
					callback(options, {
						steal : newSteal,
						rootSteal: rootSteal,
						firstSteal: steal.build.open.firstSteal(rootSteal)
					});
				})
				
				// steal file
				window.steal(data.startFile);
			} else {
				steal.build.open(html, data, function(opener){
					steal.print("  adding dependencies");
					var appFile = apps.addDependencies(opener.rootSteal, options, appName );
					options.appFiles.push(  appFile  );
					steal.print(" ")
					callback(options, opener);
				})
			}
		},
		/**
		 * Gets a steal instance and recursively sets up a __files__ object with 
		 * __file__ objects for each steal that represents a resource (not a function).
		 * 
		 * A __file__ object is a recursive mapping of a steal 
		 * instances's options and dependencies.  Different apps have different
		 * steal instances for the same resource.  This _should_ be merging those attributes
		 * and maintaining a collection of the apps the file exists on.
		 * 
		 *     {
		 *       // the apps this steal is on
		 *       appNames: [], 
		 *       dependencyFileNames: [ "jquery/class/class.js" ], 
		 *       packaged: false,
		 *       stealOpts: steal.options
		 *     }
		 * 
		 * A __files__ object maps each file's location to a file.
		 * 
		 *     {
		 *       "jquery/controller/controller.js" : file1,
		 *       "jquery/class/class.js" : file2
		 *     }
		 * 
		 * @param {steal} steel a steal instance
		 * @param {Object} files the files mapping that gets filled out
		 * @param {String} appName the appName
		 * @return {file} the root dependency file for this application
		 */
		addDependencies: function( resource, options, appName ) {
			var id = resource.options.id,
				buildType = resource.options.buildType, 
				file = maker(options.files, id || appName, function(){
					//clean and minifify everything right away ...
					var source = '';
					if( id && resource.options.buildType != 'fn' ) {
						// some might not have source yet
						steal.print("  + "+id );
						var source = resource.options.text ||  readFile( steal.idToUri( resource.options.id , true ) );
					}
					resource.options.text = resource.options.text || source
					
					// this becomes data
					return {
						// todo, might need to merge options
						// what if we should not 'steal' it?
						stealOpts: resource.options,
						appNames: [],
						dependencyFileNames: [],
						packaged: false
					}
					
				});

			// don't add the same appName more than once
			if(file.appNames.indexOf(appName) == -1){
				file.appNames.push(appName);
			}
			
			resource.needsDependencies.forEach(function(dependency){
				// TODO: check status
				if (dependency && dependency.dependencies && 
					// don't follow functions
				     dependency.options.buildType != 'fn' && 
					 !dependency.options.ignore) {
					file.dependencyFileNames.push(dependency.options.id)
					 
					apps.addDependencies(dependency, options, appName);
				}
			});
				
			resource.dependencies.forEach(function(dependency){
				// TODO: check status
				if (dependency && dependency.dependencies && 
					// don't follow functions
				     dependency.options.buildType != 'fn' && 
					 !dependency.options.ignore) {
					file.dependencyFileNames.push(dependency.options.id)
					 
					apps.addDependencies(dependency, options, appName);
				}
			});
			
			return file;
		},
		
		order: function( options ) {
			var order = 0

			function visit( f ) {
				if ( f.order === undefined ) {
					f.dependencyFileNames.forEach(function(fileName){
						visit( options.files[fileName] )
					})
					f.order = (order++);
				}
			}
			options.appFiles.forEach(function(file){
				visit(file)
			});
		},
		/**
		 * @hide
		 * 
		 * Goes through the files, makes a __shared__ array of 
		 * __sharedSets__. Each
		 * sharedSet is a collection of __sharings__.  It then
		 * takes the last __sharedSet__, finds the __sharing__
		 * with the largest totalSize, and returns that
		 * __sharing__.
		 * 
		 * A __sharing__ is a collection of files that are shared between some
		 * set of applications.  A 2-order sharing might look like:
		 * 
		 *     {totalSize: 1231, files: [file1, file2], appNames: ['foo','bar']}
		 * 
		 * A sharedSet is collection of sharings that are all shared the 
		 * same number of times (order).  For example, a sharedSet might have all
		 * 4-order 'sharings', that is files that are shared between 
		 * 4 applications.  A 2 order sharedSet might look like:
		 * 
		 *     {
		 *       'foo,bar' : {totalSize: 1231, files: [], appNames: ['foo','bar']}
		 * 	     'bar,car': : {totalSize: 31231, files: [], appNames: ['bar','car']}
		 *     }
		 * 
		 * The __shared__ array is an collection of sharedSets ordered by the
		 * order-number (the number of times a file is shared by an application).
		 * 
		 * ## How it works
		 * 
		 * getMostShared is designed to be called until all files have been
		 * marked packaged.  Thus, it changes the files by marking files 
		 * as packaged.
		 * 
		 * @param {Object} files - the files object.  
		 * @return {sharing} The sharing object:
		 * 
		 *     {
		 *       // apps that need this
		 *       appNames : ['cookbook','mxui/grid','mxui/data/list'],
		 *       files : [{file1}, {file2}]
		 *     }
		 */
		getMostShared: function( files ) {
			
			// create an array of sharedSets
			// A shared set is 
			// a collection of 
			var shared = []; // count
			
			
			// go through each file
			// find the 'most' shared one
			// package that
			for ( var fileName in files ) {
				
				var file = files[fileName];
				
				
				if ( file.packaged ) {
					continue;
				}
				// shared is like:
				// [
				//    1: {
				//       'foo' : 
				//    },
				//    2 : {
				//       'foo,bar' : {totalSize: 1231, files: [], appNames: ['foo','bar']}
				//       'bar,car': 
				//    }
				//  get an object to represent combinations
				var sharedSet = maker(shared, file.appNames.length, {}),
					
					// a name for the combo
					appsName = file.appNames.sort().join(),
					// a pack is data for a specific appNames combo
					sharing = maker(sharedSet, appsName, function(){
						return {
							totalSize: 0,
							files: [],
							appNames: file.appNames
						}
					});
				
				sharing.files.push(file);
				sharing.totalSize += file.stealOpts.text.length;
			}
			
			if (!shared.length ) {
				return null;
			}
			// get the highest shared number
			var mostShared = shared.pop(),
				mostSize = 0,
				most;
				
			// go through each app combo, get the one that has
			// the bigest size

			for ( var apps in mostShared ) {
				if ( mostShared[apps].totalSize > mostSize ) {
					most = mostShared[apps];
					mostSize = most.totalSize;
				}
			}
			//mark files as packaged
			most.files.forEach(function(f){
				f.packaged = true;
			});
			// order the files by when they should be included
			most.files = most.files.sort(function( f1, f2 ) {
				return f1.order - f2.order;
			});
			return most;
		},
		/**
		 * Creates packages that can be downloaded.
		 * 
		 * Recursively uses getMostShared to pull out
		 * the largest __sharing__.  It 
		 * makes a package of the sharing and marks
		 * the apps that need that sharing.
		 * 
		 * The apps that need the sharing
		 * 
		 * packages are mostly dummy things.  
		 * 
		 * a production file might steal multiple packages.
		 * 
		 * say package A and package B
		 * 
		 * say package A has jQuery
		 * 
		 * so, the production file has code like:
		 * 
		 * steal('jquery')
		 * 
		 * It needs to know to not load jQuery
		 * 
		 * this is where 'has' comes into place
		 * 
		 * steal({id: 'packageA', has: 'jquery'})
		 * 
		 * This wires up steal to wait until package A is finished for jQuery.
		 * 
		 * So, we need to know all the packages and app needs, and all the things in that package.
		 * 
		 * @param {appFiles} appFiles
		 * @param {files} files
		 */
		makePackages: function(options) {
			
			steal.print("Making packages")
			
			//add an order number so we can sort them nicely
			apps.order(options);

			// will be set to the biggest group
			var sharing,
				/*
				 * Packages that an app should have
				 * {
				 *   'cookbook' : ['packages/0.js']
				 * } 
				 */
				appsPackages = {},
				/*
				 * Files a package has
				 * {
				 *   'packages/0.js' : ['jquery/jquery.js']
				 * }
				 * this is used to mark all of these
				 * things as loading, so steal doesn't try to load them
				 * b/c the package is loading
				 */
				packagesFiles = {};

			// make an array for each appName that will contain the packages
			// it needs to load
			options.appFiles.forEach(function(file){
				appsPackages[file.appNames[0]] = [];
			});
			
			// remove stealconfig.js temporarily.  It will be added to every production.js
			var stealconfig = options.files['stealconfig.js'];
			
			delete options.files['stealconfig.js'];

			//while there are files left to be packaged, get the most shared and largest package
			while ((sharing = apps.getMostShared(options.files))) {
				steal.print('\npackaging shared by ' + sharing.appNames.join(", "))

				
				var appsName = sharing.appNames[0],
				//  the name of the file we are making.  
				//    If there is only one app it's an app's production.js
				//    If there are multiple apps, it's a package
					packageName = sharing.appNames.length == 1 ? 
									appsName + "/production" : 
									"packages/" + sharing.appNames.join('-')
									  .replace(/\//g,'_') 
				
				// if there's multiple apps (it's a package), add this to appsPackages for each app
				if( sharing.appNames.length > 1) {
					sharing.appNames.forEach(function(appName){
						appsPackages[appName].push(packageName+".js") // we might need to do this 
						// if there is css
					})
				}
				
				
				
				// add the files to this package
				packagesFiles[packageName+".js"] =[];
				
				// what we will sent to js.makePackage
				// the files that will actually get packaged
				var filesForPackaging = []; 
				
				sharing.files.forEach(function(file){
					// add the files to the packagesFiles
					packagesFiles[packageName+".js"].push(file.stealOpts.id);
					
					filesForPackaging.push(file.stealOpts)
					steal.print("  " + file.order + ":" + file.stealOpts.id);
				});
				
				// create dependencies object
				var dependencies = {};
				// only add dependencies for the 'root' objects
				if( sharing.appNames.length == 1) {
					
					packagesFiles[packageName+".js"].unshift("stealconfig.js")
					filesForPackaging.unshift(stealconfig.stealOpts);
					
					// for the packages for this app
					appsPackages[appsName].forEach(function(packageName){
						// add this as a dependency
						dependencies[packageName] = packagesFiles[packageName].slice(0)
					});
					
					
				}
				
				//the source of the package
				//
				var pack = steal.build.js.makePackage(filesForPackaging, dependencies,packageName+ ".css", options.exclude)

				//save the file
				steal.print("saving " + packageName+".js");
				steal.File(packageName+".js").save( pack.js );

				if(pack.css){
					steal.print("saving " + packageName+".css");
					steal.File(packageName+".css").save( pack.css.code );
					// I need to tell things that 
					// have this dependency, that this dependency needs
					// me
				}
				//packageCount++;
			}

		}
	})
	
	// sets prop on root if it doesn't exist
	// root - the object
	// prop - the property to set some other object as
	// raw - the data you want to set or a function that returns the object
	// cb - a callback that gets called with the object
	var maker = function(root, prop, raw, cb){
		if(!root[prop]){
			root[prop] = ( typeof raw === 'object' ?
				steal.extend({},raw) :
				raw() );
		}
		cb && cb( root[prop] )
		return root[prop];
	}
})