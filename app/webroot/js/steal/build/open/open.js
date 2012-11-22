steal('steal',function(s){
	// Methods for walking through steal and its dependencies
	
	// which steals have been touched in this cycle
	var touched = {},
		
		//recursively goes through dependencies
		// stl - a steal
		// CB - a callback for each steal
		// depth - true if it should be depth first search, defaults to breadth
		// includeFns - true if it should include functions in the iterator
		iterate = function(stl, CB, depth, includeFns){
			// load each dependency until
			var i =0,
				depends = stl.dependencies.slice(0); 

			// this goes through the scripts until it finds one that waits for 
			// everything before it to complete
			//console.log('OPEN', stl.options.id, "depends on", depends.map(function(stl){
			//	return stl.options.id+":"+stl.options.type
			//}).join(","))
			
			// if(includeFns){
				// if(!depends.length){
					// touch([stl], CB)
				// }
			// }
			while(i < depends.length){
				if(depends[i] === null || depends[i].waits){
					// once we found something like this ...
					// if(includeFns){
						// var steals = depends.splice(0,i+1),
							// curStl = steals[steals.length-1];
					// } else {
						// removes all steals before the wait
						var steals = depends.splice(0,i),
							// cur steal is the waiting dependency
							curStl = depends.shift();
					// }
					
					// load all these steals, and their dependencies
					loadset(steals, CB, depth, includeFns);

					if(curStl) { // curStl can be null
						if(depth){
							// load any dependencies
							loadset(curStl.dependencies, CB, depth, includeFns);
							// probably needs to change if depth
							touch([curStl], CB)
						} else {
							touch([curStl], CB);
							loadset(curStl.dependencies, CB, depth, includeFns);
						}
					}
					i=0;
				}else{
					i++;
				}
			}
			
			// if there's a remainder, load them
			if(depends.length){
				loadset(depends, CB, depth, includeFns);
			}
		  
		},
		// loads each steal 'in parallel', then 
		// loads their dependencies one after another
		loadset = function(steals, CB, depth, includeFns){
			// doing depth first
			if(depth){
				// do dependencies first
				eachSteal(steals, CB, depth, includeFns)
				
				// then mark
				touch(steals, CB);
			} else {
				touch(steals, CB);
				eachSteal(steals, CB, depth, includeFns)
			}
		},
		touch = function(steals, CB){
			for(var i =0; i < steals.length; i++){
				if(steals[i]){
					var uniqueId = steals[i].options.id;
					//print("  Touching "+uniqueId )
					if(!touched[uniqueId]){
						CB( steals[i] );
						touched[uniqueId] = true;
					}
				}
				
				
			}
		},
		eachSteal = function(steals, CB, depth, includeFns){
			for(var i =0; i < steals.length; i++){
				//print("  eachsteal ",name(steals[i]))
				iterate(steals[i], CB, depth, includeFns)
			}
		},
		name = function(s){
			return s.options.id;
		},
		window = (function() {
			return this;
		}).call(null, 0);
	/**
	 * @function open
	 * 
	 * Opens a page and returns helpers that can be used to extract steals and their 
	 * content
	 * 
	 * Opens a page by:
	 * 
	 *   - temporarily deleting the rhino steal
	 *   - opening the page with Envjs
	 *   - setting back rhino steal, saving envjs's steal as steal._steal;
	 * 
	 * 
	 * 
	 * 
	 * @param {String} url the html page to open
	 * @param {Object} [stealData] - data to configure steal with
	 * @param {Function} cb(opener) - an object with properties that makes extracting 
	 * the content for a certain tag slightly easier.
	 * 
	 *   - each(filter, depth, callback(options, stel)) - goes through steals loaded by this
	 *     application.  You can provide it a:
	 *     
	 *       - filter - a function to filter out some types of steal methods, 
	 *         it supports js and css.
	 *       - depth - if true, goes through with breadth first search, false is 
	 *         breadth. Defaults to breadth (how steal loads scripts)
	 *       - callback - a method that is called with each steal option
	 *       
	 *         opener.each(function(option){
	 *           console.log(option.text)
	 *         })
	 *         
	 *   - steal - the steal loaded by the app
	 *   - url - the html page opened
	 *   - rootSteal - the 'root' steal instance
	 *   - firstSteal - the first steal file
	 * @return {Object} an object with properties that makes extracting 
	 * the content for a certain tag slightly easier.
	 */
	s.build.open = function( url, stealData, cb, depth, includeFns ) {
		// save and remove the old steal
		var oldSteal = s,
			// new steal is the steal opened
			newSteal;
			
		// remove the current steal
		delete window.steal;
		
		// clean up window in case this is the second time Envjs has opened the page
		for(var n in window){
			// TODO make this part of steal namespace
			if(n !== "STEALPRINT"){
				delete window[n];
			}
		}
		// move params
		if ( typeof stealData == 'object') {
			window.steal = stealData;
		}else{
			cb = stealData;
		}
		// get envjs
		load('steal/rhino/env.js'); //reload every time
		
		
	
		// what gets called by steal.done
		// rootSteal the 'master' steal
		var doneCb = function(rootSteal){
			// get the 'base' steal (what was stolen)
			
			// clear timers
			Envjs.clear();
			
			// callback with the following
			cb({
				/**
				 * @hide
				 * Goes through each steal and gives its content.
				 * How will this work with packages?
				 * 
				 * @param {Function} [filter] the tag to get
				 * @param {Boolean} [depth] the tag to get
				 * @param {Object} func a function to call back with the element and its content
				 */
				each: function( filter, depth, func ) {
					// reset touched
					touched = {};
					// move params
					if ( !func ) {
						
						if( depth === undefined ) {
							depth = false;
							func = filter;
							filter = function(){return true;};
						} else if( typeof filter == 'boolean'){
							func = depth;
							depth = filter
							filter = function(){return true;};
						} else if(arguments.length == 2 && typeof filter == 'function' && typeof depth == 'boolean'){
							func = filter;
							filter = function(){return true;};
						} else {  // filter given, no depth
							func = depth;
							depth = false;
							
						}
					};
					
					// make this filter by type
					if(typeof filter == 'string'){
						var resource = filter;
						filter = function(stl){
							return stl.options.buildType === resource;
						}
					}
					var items = [];
					// iterate 
					iterate(rootSteal, function(resource){
						
						if( filter(resource) ) {
							resource.options.text = resource.options.text || loadScriptText(resource);
							func(resource.options, resource );
							items.push(resource.options);
						}
					}, depth, includeFns );
				},
				// the 
				steal: newSteal,
				url: url,
				rootSteal : rootSteal,
				firstSteal : s.build.open.firstSteal(rootSteal)
			})
		};
		
		Envjs(url, {
			scriptTypes: {
				"text/javascript": true,
				"text/envjs": true,
				"": true
			},
			fireLoad: true,
			logLevel: 2,
			afterScriptLoad: {
				// prevent $(document).ready from being called even though load is fired
				"jquery.1.7.1.js": function( script ) {
					window.jQuery && jQuery.holdReady(true);
				},
				"jquery.1.8.1.js": function( script ) {
					window.jQuery && jQuery.holdReady(true);
				},
				"steal.js": function(script){
					if(stealData.skipAll){
						window.steal.config({
							types: {
								"js" : function(options, success){
									var text;
									if(options.text){
										text = options.text;
									}else{
										text = readFile(options.id);
									}
									// check if steal is in this file
									var stealInFile = /steal\(/.test(text);
									if(stealInFile){
										// if so, load it
										eval(text)
									} else {
										// skip this file
									}
									success()
								},
								"fn": function (options, success) {
									// skip all functions
									success();
								}
							}
						})
					}
					// a flag to tell steal we're in "build" mode
					// this is used to completely ignore files with the "ignore" flag set
					window.steal.isBuilding = true;
					// if there's timers (like in less) we'll never reach next line 
					// unless we bind to done here and kill timers
					window.steal.one('done', doneCb);
					newSteal = window.steal;
				}
			},
			dontPrintUserAgent: true
		});
		
		// set back steal
		
		window.steal = oldSteal;
		// TODO: is this needed anymore
		window.steal._steal = newSteal;

		Envjs.wait();
	};
	steal.build.open.firstSteal =function(rootSteal){
		var stel;
		for(var i =0; i < rootSteal.dependencies.length; i++){
			stel = rootSteal.dependencies[i];
			
			if(stel && stel.options.buildType != 'fn' && stel.options.id != 'steal/dev/dev.js' && stel.options.id != 'stealconfig.js'){
				return stel;
			}	
		}
	};
	
	var loadScriptText = function( stl ) {
		var options = stl.options;
		if(options.fn){
			return stl.orig.toString();
		}
		if(options._skip){ // if we skip this script, we don't care about its contents
			return "";
		}
		
		if(options.text){
			return options.text;
		}
		
		// src is relative to the page, we need it relative
		// to the filesystem
		var src = options.src+"",
			text = "",
			base = "" + window.location,
			url = src.match(/([^\?#]*)/)[1];


		
		url = Envjs.uri(url, base);
		
		if ( url.match(/^file\:/) ) {
			url = url.replace("file:/", "");
			text = readFile("/" + url);
		}

		if ( url.match(/^http\:/) ) {
			text = readUrl(url);
		}
		return text;
	};
})