/**
 * searchData = {
  "fullName" : {
     type : "type"
     name: "name",
     title : "title",
     tags : "tags",
     hide:  1||0,
     parents : [../../..]
  }
}
 * @param {Object} options
 */

steal(function(){
	
	// Makes a JSON object for search data
	DocumentJS.searchData = function(objects, options){
		
		var searchData = {};

		addToSearchData(objects, searchData)
		
		return new DocumentJS.File(options.out + "/searchData.json").save(DocumentJS.out(searchData, false));
	}
	var addIDs = function(list){
		var count = 0;
		for ( var name in list ) {
			if (list.hasOwnProperty(name)) {
				if ( list[name].type == 'script' ) {
					continue;
				}
				list[name].id = count;
				count++;
			}
		}
	};
	// goes through list and adds to search data
	var addToSearchData = function( list, searchData ) {
		addIDs(list);
		var c, parts, part, p, fullName;
		for ( var name in list ) {
			if (list.hasOwnProperty(name)){
				c = list[name];
				if ( c.type == 'script' ) {
					continue;
				}
				//break up into parts
				fullName = c.name;
				searchData[fullName] = {
					name: c.name,
					type: c.type
				};
				
				if ( c.id !== undefined ) {
					searchData[fullName].id = c.id
				}
				if ( c.title ) {
					searchData[fullName].title = c.title
				}
				if ( c.tags ) {
					searchData[fullName].tags = c.tags
				}
				if ( c.hide ) {
					searchData[fullName].hide = c.hide
				}
				if ( c.parents ) {
					searchData[fullName].parents = c.parents
				}
				if ( c.order != null) {
					searchData[fullName].order = c.order;
				}
				//if ( c.parent ) {
				//	searchData[fullName].parent = c.parent
				//}
				//parts = fullName.split(".");
				/*for ( p = 0; p < parts.length; p++ ) {
					part = parts[p].toLowerCase();
					if ( part == "jquery" ){
						continue;
					}
					addTagToSearchData(fullName, part, searchData)
				}
				//now add tags if there are tags
				if ( c.tags ) {
					for ( var t = 0; t < c.tags.length; t++ ){
						addTagToSearchData(fullName, c.tags[t], searchData);
					}
				}*/
			}
		}
	},
		//adds a component to the search data
		addTagToSearchData = function( data, tag, searchData ) {

			var letter, l, depth = 2,
				current = searchData;

			for ( l = 0; l < depth; l++ ) {
				letter = tag.substring(l, l + 1);
				if (!current[letter] ) {
					current[letter] = {};
					current[letter].list = [];
				}
				if ( indexOf(current[letter].list, data) == -1 ) {
					current[letter].list.push(data);
				}
				current = current[letter];
			}
		},
		indexOf = function( array, item ) {
			var i = 0,
				length = array.length;
			for (; i < length; i++ ){
				if ( array[i] === item ){
					return i;
				}
			}
			return -1;
		}
	
DocumentJS.searchData.addToSearchData =addToSearchData;
	
	
})
