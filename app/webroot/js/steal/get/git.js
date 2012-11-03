steal('steal',function(s){
/**
 * Gets the sha of the most recent commit
 *
 * @param {Object} info the github repos information
 * @return {String} the sha of the most recent commit (ex: 'ba7013dfaee2e503069f594ec271ec9795edb16c')
 */
var lastCommitSha = function(inf){
	var commitsText = readUrl("https://api.github.com/repos/" + 
								inf.user + "/" + 
								inf.repo + "/" + 
								"commits?sha=" + inf.branch);

	eval("var commits = " + commitsText);
	return commits[0].commit.tree.sha;
},
/**
 * Gets the API url for the tree at the sha specified
 *
 * @param {Object} info the github repos information
 * @param {String} sha the sha of the tree
 * @param {Boolean} recursive whether we want to get the tree recursively (i.e. all tress within this tree)
 * @return {String} the API url of the tree (ex: 'https://api.github.com/repos/jupiterjs/funcunit/git/trees/ba7013dfaee2e503069f594ec271ec9795edb16c')
 */
getTreeUrl = function(inf, sha, recursive){
	sha = !sha ? lastCommitSha(inf) : sha;
	var url = "https://api.github.com/repos/" + 
				inf.user + "/" + 
				inf.repo + "/" + 
				"git/trees/" + sha;
	if(recursive) {
		url += "?recursive=1";
	}

	return url;
},
/**
 * Gets the API url for the folder(tree) at the sha specified
 * Traverse the whole repo tree until we find the matching
 * folder path 
 *
 * @param {Object} info the github repos information
 * @return {String} the API url of the folder (ex: 'https://api.github.com/repos/jupiterjs/funcunit/git/trees/ba7013dfaee2e503069f594ec271ec9795edb16c')
 */
getFolderUrl = function(inf) {
	var repoTreeText = readUrl(getTreeUrl(inf, inf.branch, true)),
		repoTree,
		folderPath = inf.resource.replace(/\/$/, ""),
		sha;

	eval("var t = " + repoTreeText);
	repoTree = t.tree;
	for(file in repoTree) {
		//print('-f ' + repoTree[file].path);
		if(folderPath === repoTree[file].path) {
			sha = repoTree[file].sha;
			break;
		}
	}
	return getTreeUrl(inf, sha);
},
github = s.get.git = {
		/**
		 * Generate a map of names to urls
		 *
		 * @param {String} content tree data from the repo/folder
		 * @param {String} rawUrl the API url for the repo/folder
		 * @param {String} originalUrl the original url for the repo/folder
		 * @return {Object} mapping of names to urls
		 */
		ls : function(content, rawUrl, originalUrl){
			var info = github.info(originalUrl),
				tree,
				item,
				data = {}
			
			//print("item -- "+info.base)
			
			eval("var t = " + content);
			tree = t.tree;
			// use gitHub's commit API
			for(var i = 0; i < tree.length; i++) {
				item = tree[i]
				//print('item: ' + item.path)
				if(item.path.indexOf(".git") === 0){
					
				} else if ( item.type == "blob" ) {
					data[item.path] = info.base + item.path;
				}
				else if ( item.type == "tree" ) {
					data[item.path+"/"] = info.base + item.path+"/";
				}
			}
			
			return data;
			
		},
		/**
		 * Return the raw place to get the repo/folder contents
		 * or doanload a file
		 *
		 * - root folder will return the API url for the root tree
		 * - folder will return the API url for folder's tree
		 * - file will return the raw download url
		 *
		 * @param {String} url url to convert
		 * @return {String} raw place to doanload contents or file
		 */
		raw : function(url){
			var info = github.info(url);
			if(info.resource == "/"){ // root level folder
				return getTreeUrl(info)
			} else if( /\/$/.test(url) ) { // a folder
				return getFolderUrl(info)
			} else { // download url
				return "https://raw."+info.domain+"/"+info.user+"/"+info.repo+"/"+info.branch+"/"+info.resource
			}
		},
		/**
		 * Helper to get info for a github repo
		 *
		 * Resulting info object has the following properties:
		 *
		 * - `protocol`: protocol of the url 
		 * - `domain`: domain of the repo
		 * - `user`: github user 
		 * - `repo`: repo's name 
		 * - `branch`: branch from url or master 
		 * - `resource`: path within the current repo 
		 * - `base`: base url for the repo 
		 *
		 * @param {String} url url of the repo
		 * @return {Object} containing the repo info
		 */
		info : function(url){
			var split = url.split("/"),
				data = {},
				branch;
			data.protcol = split.shift();
			split.shift();
			data.domain = split.shift();
			data.user = split.shift();
			data.repo = split.shift();
			
			branch = split.shift();
			if(branch === 'tree' || branch === 'raw' || branch === 'blob'){
				branch = split.shift();
			}
			data.branch = branch || 'master';
			
			data.resource = split.join('/').replace(/\?.*/,"") || "/";
			data.base = "https://"+data.domain+"/"+data.user+"/"+data.repo+"/tree/"+data.branch+"/"+( data.resource == "/" ? "" : data.resource)
			return data;
		},
		getTreeUrl : getTreeUrl,
		getFolderUrl : getFolderUrl
	};
})()