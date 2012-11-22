/**
 * @function st.id
 * 
 * Given a resource id passed to `steal( resourceID, currentWorkingId )`, this function converts it to the 
 * final, unique id. This function can be overwritten 
 * to change how unique ids are defined, for example, to be more AMD-like.
 * 
 * The following are the default rules.
 * 
 * Given an ID:
 * 
 *  1. Check the id has an extension like _.js_ or _.customext_. If it doesn't:
 *      1. Check if the id is relative, meaning it starts with _../_ or _./_. If it is not, add 
 *         "/" plus everything after the last "/". So `foo/bar` becomes `foo/bar/bar`
 *      2. Add .js to the id.
 *  2. Check if the id is relative, meaning it starts with _../_ or _./_. If it is relative,
 *     set the id to the id joined from the currentWorkingId.
 *  3. Check the 
 * 
 * 
 * `st.id()`
 */
// returns the "rootSrc" id, something that looks like requireJS
// for a given id/path, what is the "REAL" id that should be used
// this is where substituation can happen
st.id = function( id, currentWorkingId, type ) {
	// id should be like
	var uri = URI(id);
	uri = uri.addJS().normalize(currentWorkingId ? new URI(currentWorkingId) : null)
	// check foo/bar
	if (!type ) {
		type = "js"
	}
	if ( type == "js" ) {
		// if it ends with .js remove it ...
		// if it ends
	}
	// check map config
	var map = config.attr().map || {};
	// always run past 
	h.each(map, function( loc, maps ) {
		// is the current working id matching loc
		if ( h.matchesId(loc, currentWorkingId) ) {
			// run maps
			h.each(maps, function( part, replaceWith ) {
				if (("" + uri).indexOf(part) == 0 ) {
					uri = URI(("" + uri).replace(part, replaceWith))
				}
			})
		}
	})
	
	return uri;
}

st.amdToId = function(id, currentWorkingId, type){
	var uri = URI(id);
	uri = uri.normalize(currentWorkingId ? new URI(currentWorkingId) : null)
	// check foo/bar
	if (!type ) {
		type = "js"
	}
	if ( type == "js" ) {
		// if it ends with .js remove it ...
		// if it ends
	}
	// check map config
	var map = config.attr().map || {};
	// always run past 
	h.each(map, function( loc, maps ) {
		// is the current working id matching loc
		if ( h.matchesId(loc, currentWorkingId) ) {
			// run maps
			h.each(maps, function( part, replaceWith ) {
				if (("" + uri).indexOf(part) == 0 ) {
					uri = URI(("" + uri).replace(part, replaceWith))
				}
			})
		}
	})
	return uri;
}

// for a given ID, where should I find this resource
/**
 * @function st.idToUri
 *
 * `steal.idToUri( id, noJoin )` takes an id and returns a URI that
 * is the location of the file. It uses the paths option of  [config].
 * Passing true for `noJoin` does not join from the root URI.
 */
st.idToUri = function( id, noJoin ) {
	// this is normalize
	var paths = config.attr().paths || {},
		path;
	// always run past 
	h.each(paths, function( part, replaceWith ) {
		path = ""+id;
		// if path ends in / only check first part of id
		if((h.endsInSlashRegex.test(part) && path.indexOf(part) == 0) ||
			// or check if its a full match only
			path === part){
			id = URI(path.replace(part, replaceWith));
		}
	})

	return noJoin ? id : config.attr().root.join(id)
}

// for a given AMD id this will return an URI object
/**
 * @function st.amdIdToUri
 *
 * `steal.amdIdToUri( id, noJoin )` takes and AMD id and returns a URI that
 * is the location of the file. It uses the paths options of [config].
 * Passing true for `noJoin` does not join from that URI.
 */
st.amdIdToUri = function( id, noJoin ){
	// this is normalize
	var paths = config.attr().paths || {},
		path;
	// always run past 
	h.each(paths, function( part, replaceWith ) {
		path = ""+id;
		// if path ends in / only check first part of id
		if((h.endsInSlashRegex.test(part) && path.indexOf(part) == 0) ||
			// or check if its a full match only
			path === part){
			id = URI(path.replace(part, replaceWith));
		}
	})
	if( /(^|\/)[^\/\.]+$/.test(id) ){
		id= URI(id+".js")
	}
	return id //noJoin ? id : config().root.join(id)
}

// ## AMD ##
var modules = {

};


// AMD is not available for now. If you want to use AMD features with
// steal you can by setting the `amd` param to true:
//
//     steal({
//       amd: true
//     })
//
// This will expose `define` and `require` functions which can be used
// to load AMD modules

if(config.attr('amd') === true){

	// convert resources to modules ...
	// a function is a module definition piece
	// you steal(moduleId1, moduleId2, function(module1, module2){});
	/**
 	 * @function window.define
 	 *
 	 * AMD compatible `define` function. It is available only if steal's
 	 * `amd` param is set to true:
 	 *
 	 *     <script type="text/javascript">
 	 *       steal = {
	 *         amd : true
 	 *       }
 	 *     <script />
 	 *     <script type="text/javascript" src="steal/steal.js"></script>
 	 *
 	 */
	h.win.define = function( moduleId, dependencies, method ) {
		if(typeof moduleId == 'function'){
			modules[URI.cur+""] = moduleId();
		} else if(!method && dependencies){
			if(typeof dependencies == "function"){
				modules[moduleId] = dependencies();
			} else {
				modules[moduleId] = dependencies;
			}
			
		} else if (dependencies && method && !dependencies.length ) {
			modules[moduleId] = method();
		} else {
			st.apply(null, h.map(dependencies, function(dependency){
				dependency = typeof dependency === "string" ? {
					id: dependency
				} : dependency;
				dependency.toId = st.amdToId;
				
				dependency.idToUri = st.amdIdToUri;
				return dependency;
			}).concat(method) )
		}
		
	}
	/**
 	 * @function window.require
 	 *
 	 * AMD compatible require function. It is available only if steal's
 	 * `amd` param is set to true:
 	 *
 	 *     <script type="text/javascript">
 	 *       steal = {
	 *         amd : true
 	 *       }
 	 *     <script />
 	 *     <script type="text/javascript" src="steal/steal.js"></script>
 	 *
 	 */
	h.win.require = function(dependencies, method){
		var depends = h.map(dependencies, function(dependency){
				dependency = typeof dependency === "string" ? {
					id: dependency
				} : dependency;
				dependency.toId = st.amdToId;
				
				dependency.idToUri = st.amdIdToUri;
				return dependency;
			}).concat([method]);
		st.apply(null, depends )
	}
	h.win.define.amd = {
		jQuery: true
	}
	
	// expose steal as AMD module
	define("steal", [], function() {
		return st;
	});

	define("require", function(){
		return require;
	})

}