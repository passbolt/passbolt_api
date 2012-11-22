steal('can/construct', 'can/util/json.js').then('./favorites.js',function(){

	var data,
		// a map of names to deferreds
		findOneDeferreds = {};
	
	$.ajaxSetup({
		converters: {
			"json addFavorites": function(data){
				data.isFavorite = Favorites.isFavorite(data)
				return data;
			}
		}
	});
	
	can.Construct("Doc",{
		location : null,
		dataDeferred : can.Deferred(),
		load: function( success ) {
			// see if we have latest in localStorage
			
			if(window.localStorage && window.JMVCDOC_TIMESTAMP){
				var json = window.localStorage["jmvcDoc"+JMVCDOC_TIMESTAMP]
				if(json){
					var data = can.parseJSON(json);
					this._data = data;
					success(data);
					var d =can.Deferred();
					d.resolve(data);
					Doc.dataDeferred.resolve()
					return d;
				} else {
					//clear everything that starts with jmvcDoc, try to remove the old data ...
					i = 0;
					while (i < localStorage.length) {
						var prop = localStorage.key(i);
						if (prop.indexOf("jmvcDoc") == 0) {
							localStorage.removeItem(prop)
						}
						else {
							i++;
						}
					}
				}
				
			}
			var d = can.ajax({
				url:  ( this.location || DOCS_LOCATION) + "searchData.json" ,
				success: $.proxy(function(data){
					this.setData(data)
					success && success.apply(this, arguments)
				}, this),
				jsonpCallback: "C",
				dataType: "jsonp",
				cache: true
			})
			d.then(function(){
				Doc.dataDeferred.resolve()
			})
			return d;;
	
		},
		setData: function( data ) {
			this._data = data;
			var prop, doc, parents, i, len, parent;
			// go through and add children ...
			for(prop in this._data){
				doc = this._data[prop];
				parents = doc.parents || [];
				len = parents.length;
				for(var i =0; i < len; i++){
					parent = data[parents[i]];
					
					if(!parent.childDocs){
						parent.childDocs = []
					}
					// this 'should' take up less mem (but not in what's saved)
					parent.childDocs.push(doc.name);
				}
			}
			if(window.localStorage && window.JMVCDOC_TIMESTAMP){
				setTimeout(function(){
					window.localStorage["jmvcDoc"+JMVCDOC_TIMESTAMP] = can.toJSON(data)
				},1000)
				
			}
			return arguments;
		},
		findOne: function(params, success, error){
			if(success){
				if(window.localStorage && window.JMVCDOC_TIMESTAMP){
					var json = window.localStorage["jmvcDoc"+params.name]
					if(json){
						var data = can.parseJSON(json);
						if(data.timestamp == JMVCDOC_TIMESTAMP){
							success(data)
							return;
						}
					}
				}
				var def = findOneDeferreds[params.name]
				// check if we are already requesting
				if(def) {
					def.done(success);
					def.fail(error);
					return def;
				} else {
					def = findOneDeferreds[params.name] = can.Deferred();
					def.fail(error);
					def.done(function(data){
						if(window.localStorage && window.JMVCDOC_TIMESTAMP){
							data.timestamp = JMVCDOC_TIMESTAMP;
							setTimeout(function(){
								window.localStorage["jmvcDoc"+params.name] = can.toJSON(data)
								delete findOneDeferreds[params.name];
							},10)
						}
						success(data)
					});
					can.ajax({
						url: ( this.location || DOCS_LOCATION) + params.name.replace(/ /g, "_")
							.replace(/&#46;/g, ".") + ".json",
						error: function(){
							def.reject.apply(def, arguments)
						},
						dataType: "script"
					});
					
					return def;
				}
			}
			
			var res;
			if(params.name){
				res = this._data[params.name]
			}

			if( res ) {
				return new this(res);
			}
		},
		foundOne : function(data){
			data.isFavorite = Favorites.isFavorite(data)
			
			// look up and resolve deferred ...
			var def = findOneDeferreds[data.name];
			def.resolve(data);
		},
		/**
		 * Used for search
		 * @param {Object} params
		 */
		findAll: function(params){
			var valWasEmpty, level = 2;
			var val = params.search.toLowerCase();
	
			if (!val || val === "*" ) {
				val = "home"; // return the core stuff
				valWasEmpty = true;
			}
	
			if (val == "favorites") {
				return Favorites.findAll()
			}

			var current = this.searchData();
			
			for ( var i = 0; i < level; i++ ) {
				if ( val.length <= i || !current ) break;
				var letter = val.substring(i, i + 1);
				current = current[letter];
			}
			
			var list = [];
			if ( current && val.length > level ) {
				//make sure everything in current is ok
				var lookedup = this.lookup(current.list);
				for ( var i = 0; i < lookedup.length; i++ ) {
					if (this.matches(lookedup[i], val, valWasEmpty)) {
						list.push(lookedup[i])
					}
				}
			} else if ( current ) {
				list = this.lookup(current.list);
			}
			return list.sort(Search.sortFn);
		},
		searchData : function(){
			//returns the search data ...
			
			if(this._searchData){
				return this._searchData;
			}
			
			if(window.localStorage && window.JMVCDOC_TIMESTAMP){
				var json = window.localStorage["jmvcDoc"+window.JMVCDOC_TIMESTAMP]
				if(json){
					return this._searchData = can.parseJSON(json);
				}
			}
			
			//create searchData
			var searchData = this._searchData = {};
			var parts,c,
				addTagToSearchData = function( data, tag ) {
		
					var letter, l, depth = 3,
						current = searchData;
		
					for ( l = 0; l < depth; l++ ) {
						letter = tag.substring(l, l + 1);
						if (!current[letter] ) {
							current[letter] = {};
							current[letter].list = [];
						}
						if ( can.inArray(data, current[letter].list) == -1 ) {
							current[letter].list.push(data);
						}
						current = current[letter];
					}
				};
			
			for(var fullName in this._data){
				c = this._data[fullName]
				
				parts = fullName.split(".");
				for ( p = 0; p < parts.length; p++ ) {
					part = parts[p].toLowerCase();
					if ( part == "jquery" ){
						continue;
					}
					addTagToSearchData(fullName, part)
				}
				//now add tags if there are tags
				if ( c.tags ) {
					for ( var t = 0; t < c.tags.length; t++ ){
						addTagToSearchData(fullName, c.tags[t]);
					}
				}
			}	
			
			return this._searchData;
		},
		matches: function( who, val, valWasEmpty ) {
			if (!valWasEmpty && who.name.toLowerCase().indexOf(val) > -1 ) return true;
			if ( who.tags ) {
				for ( var t = 0; t < who.tags.length; t++ ) {
					if ( who.tags[t].toLowerCase().indexOf(val) > -1 ) return true;
				}
			}
			return false;
		},
		lookup: function( names ) {
			var res = [];
			for ( var i = 0; i < names.length; i++ ) {
				this._data[names[i]] && res.push(this._data[names[i]])
			}
			return res;
		}
	},{
		init : function(attrs){
			can.extend(this,attrs);
		},
		
		children : function(){
			var data = this.constructor._data;
			//get the child docs and their order ...
			return $.map(this.childDocs || [], function(docName){
				return new Doc( data[docName] );
			}).sort(Search.sortFn)
		}
	});
	if(! steal.isRhino ){
		Doc.load(function(){});
	}

can.Construct('Search', {
	sortFn: function( a, b ) {
		var aHasOrder = a.order !== undefined,
			bHasOrder = b.order !== undefined
		if(aHasOrder && bHasOrder){
			return a.order - b.order;
		} 
		if( aHasOrder ){
			return -1;
		}
		if(bHasOrder){
			return 1;
		}
		
		
		//if equal, then prototype, prototype properties go first
		var aname = (a.title && a.name.indexOf(".") == -1 ? a.title : a.name).replace(".prototype", ".zzzaprototype").replace(".static", ".zzzbstatic").toLowerCase();
		var bname = (b.title && b.name.indexOf(".") == -1 ? b.title : b.name).replace(".prototype", ".zzzaprototype").replace(".static", ".zzzbstatic").toLowerCase();


		if ( aname < bname ) return -1
		else aname > bname
		return 1
		return 0;
	},
	sortJustStrings: function( aname, bname ) {
		var aname = aname.replace(".prototype", ".000AAAprototype").replace(".static", ".111BBBstatic");
		var bname = bname.replace(".prototype", ".000AAAprototype").replace(".static", ".111BBBstatic");


		if ( aname < bname ) return -1
		else aname > bname
		return 1
		return 0;
	}
}, {})
	
	window.c = Doc.foundOne;
});
