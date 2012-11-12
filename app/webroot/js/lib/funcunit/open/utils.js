steal(function(){	
	if (typeof FuncUnit == 'undefined') {
		FuncUnit = {};
	}
	if(!FuncUnit.loader){
		FuncUnit.loader = {};
	}
}, 'funcunit/open/output', function(){
	// loaded into the commandline environment (shared by envjs, selenium)
	var totalFailed = 0,
		total = 0,
		browserFailed, browserTotal;

	// bind all events
	FuncUnit.bindEvents = function(browser){
		browser.bind("begin", function(data){
			FuncUnit.starttime = new Date().getTime();
			browserFailed = browserTotal = 0;
			FuncUnit.begin(FuncUnit.browserName);
		})
		.bind('testDone', function(data){
			browserFailed += data.failed;
			browserTotal += data.total;
			totalFailed += data.failed;
			total += data.total;
			FuncUnit.testDone(data.name, data.failed, data.total)
		})
		.bind('log', function(data){
			FuncUnit.log(data.result, data.message)
		})
		.bind('testStart', function(data){
			FuncUnit.testStart(data.name)
		})
		.bind('moduleStart', function(data){
			FuncUnit.moduleStart(data.name)
		})
		.bind('moduleDone', function(data){
			FuncUnit.moduleDone(data.name, data.failed, data.total)
		})
		.bind('done', function(data){
			FuncUnit.endtime = new Date().getTime();
			var duration = (FuncUnit.endtime - FuncUnit.starttime) / 1000;
			FuncUnit.done(totalFailed, total, duration);
			this.close();
		})
		.bind('coverage', function(data){
			FuncUnit.coverage(data)
		})
	
	}
	
	// if coverage is true, use this to change the URL
	var getPageUrl = function(page, coverage){
		if(!/https?:|file:/.test(page)){ // if theres no protocol, turn it into a filesystem urls
			var cwd = (new java.io.File (".")).getCanonicalPath()+"";
			cwd = cwd.replace(/\\/, '/');
			page = "file:///"+cwd+"/"+page;
		}
		
		//convert spaces to %20.
		var newPage = /https?:/.test(page) ? page: page.replace(/ /g,"%20");
		
		if(coverage){
			newPage = newPage+"?coverage=true";
		}
		return newPage;
	}
	
	FuncUnit.opts = function(args){
		var page;
		if (!_args[0]) {
			print("Usage: js funcunit/open/selenium path/to/page.html");
			quit();
		}
		
		page = _args.shift();
		var opts = steal.opts(_args, {
			// true to run with coverage on
			coverage: 0,
			// the browser you want to open ("*iexplore")
			browser: 1,
			failOnError: parseInt(java.lang.System.getenv("ERRORLEV"), 10),
			// provide a filename to record test output in an xml format that jenkins can read
			out: 1,
			// use to provide your own selenium host server
			seleniumHost: 1,
			// use to provide your own selenium port
			seleniumPort: 1
		})
		
		opts.page = getPageUrl(page, opts.coverage)
		
		// output.js uses this to create the file
		FuncUnit.outputFile = opts.out;
		
		// turn on with the -e flag if you want to exit hard
		FuncUnit.failOnError = parseInt(java.lang.System.getenv("ERRORLEV"), 10);
		
		print("Opening "+opts.page);
		return opts;
	}
})