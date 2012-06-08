steal(function(){	
	if (typeof FuncUnit == 'undefined') {
		FuncUnit = {};
	}
	if(!FuncUnit.loader){
		FuncUnit.loader = {};
	}
}, 
	'funcunit/commandline/events.js', function(){
	
	/**
	 * 2 ways to include settings.js:
	 * 1. Manually before funcunit.js 
	 * 2. FuncUnit.load will try to load settings.js if there hasn't been one loaded
	 */
	FuncUnit._loadSettingsFile = function(page){
		var backupFunc = FuncUnit;
		load('funcunit/settings.js')
		steal.extend(FuncUnit, backupFunc)
		load('funcunit/commandline/output/output.js')
	}
	
	// if coverage is true, use this to change the URL
	// page can be a .html or a .js file
	FuncUnit._getPageUrl = function(page, coverage, ignores){
		var isHtml = true,
			testFile = page;
			
		if(!/\.html$/.test(page)){
			isHtml = false;
			page = "funcunit/dashboard/frame/qunit.html";
			if(FuncUnit.jmvcRoot){
				page = FuncUnit.jmvcRoot + "/" + page;
			}
		}
		if(!/https?:|file:/.test(page)){ // if theres no protocol, turn it into a filesystem urls
			var cwd = (new java.io.File (".")).getCanonicalPath();
			page = "file://"+cwd+"/"+page;
		}
		
		//convert spaces to %20.
		var newPage = /https?:/.test(page) ? page: page.replace(/ /g,"%20");
		
		if(coverage){
			if(!(ignores && ignores.length)){
				ignores = ["true"];
			}
			newPage = newPage+"?steal[instrument]=";
			newPage += ignores.join(",");
		}
		if(!isHtml){
			newPage += "&test="+testFile;
		}
		return newPage;
	}
})