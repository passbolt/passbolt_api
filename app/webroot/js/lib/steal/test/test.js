module("steal via system import");

QUnit.config.testTimeout = 30000;

(function(){

	var writeIframe = function(html){
		var iframe = document.createElement('iframe');
		window.removeMyself = function(){
			delete window.removeMyself;
			document.body.removeChild(iframe);
		};
		document.body.appendChild(iframe);
		iframe.contentWindow.document.open();
		iframe.contentWindow.document.write(html);
		iframe.contentWindow.document.close();
	};
	var makePassQUnitHTML = function(){
		return "<script>\
			window.QUnit = window.parent.QUnit;\
			window.removeMyself = window.parent.removeMyself;\
			</script>";

	};
	var makeStealHTML = function(url, src, code){
		return "<!doctype html>\
			<html>\
				<head>" + makePassQUnitHTML() +"\n"+
					"<base href='"+url+"'/>"+
				"</head>\
				<body>\
					<script "+src+"></script>"+
					(code ? "<script>\n"+code+"</script>" :"") +
				"</body></html>";

	};
	var makeIframe = function(src){
		var iframe = document.createElement('iframe');
		window.removeMyself = function(){
			delete window.removeMyself;
			document.body.removeChild(iframe);
		};
		document.body.appendChild(iframe);
		iframe.src = src;
	};

	asyncTest('steal basics', function(){
		System['import']('tests/module').then(function(m){
			equal(m.name,"module.js", "module returned" );
			equal(m.bar.name, "bar", "module.js was not able to get bar");
			start();
		}, function(err){
			ok(false, "steal not loaded");
			start();
		});
	});

	asyncTest("steal's normalize", function(){
		System['import']('tests/mod/mod').then(function(m){
			equal(m.name,"mod", "mod returned" );
			equal(m.module.bar.name, "bar", "module.js was able to get bar");
			equal(m.widget(), "widget", "got a function");
			start();
		}, function(){
			ok(false, "steal not loaded");
			start();
		});
	});

	asyncTest("steal's normalize with a plugin", function(){
		System.instantiate({
			name: "foo",
			metadata: {format: "steal"},
			source: 'steal("foo/bar!foo/bar", function(){})'
		}).then(function(result){
			equal(result.deps[0], "foo/bar/bar!foo/bar", "normalize fixed part before !");
			start();
		});
	});

	asyncTest("steal's normalize with plugin only the bang", function(){
		System.instantiate({
			name: "foobar",
			metadata: {format: "steal"},
			source: 'steal("./rdfa.stache!", function(){})'
		}).then(function(result){
			System.normalize(result.deps[0], "foo","http://abc.com").then(function(result){
				equal(result, "rdfa.stache!stache", "normalize fixed part before !");
				start();
			});
		});
	});

	asyncTest("ignoring an import by mapping to @empty", function(){
		System.map["map-empty/other"] = "@empty";
		System["import"]("map-empty/main").then(function(m) {
			var empty = System.get("@empty");
			equal(m.other, empty, "Other is an empty module because it was mapped to empty in the config");
		}, function(){
			ok(false, "Loaded a module that should have been ignored");
		}).then(start);
	});

	asyncTest("steal.dev.assert", function() {
		System["import"]("ext/dev").then(function(dev){
			throws(
				function() {
					dev.assert(false);
				},
				/Expected/,
				"throws an error with default message"
			);
			throws(
				function() {
					dev.assert(false, "custom message");
				},
				/custom message/,
				"throws an error with custom message"
			);
			start();
		});
	});


	module("steal via html");

	asyncTest("basics", function(){
		makeIframe("basics/basics.html");
	});

	asyncTest("basics with steal.config backwards compatability", function(){
		makeIframe("basics/basics-steal-config.html");
	});


	asyncTest("basics with generated html", function(){
		writeIframe(makeStealHTML(
			"basics/basics.html",
			'src="../../steal.js?basics" data-config="../config.js"'));
	});

	asyncTest("default config path", function(){
		writeIframe(makeStealHTML(
			"basics/basics.html",
			'src="../steal.js?basics"'));
	});

	asyncTest("default config path", function(){
		writeIframe(makeStealHTML(
			"basics/basics.html",
			'src="../steal/steal.js?basics"'));
	});

	asyncTest("inline", function(){
		makeIframe("basics/inline_basics.html");
	});

	asyncTest("default bower_components config path", function(){
		writeIframe(makeStealHTML(
			"basics/basics.html",
			'src="../bower_components/steal/steal.js?basics"'));
	});

	asyncTest("default bower_components without config still works", function(){
		makeIframe("basics/noconfig.html");
	});

	asyncTest("map works", function(){
		makeIframe("map/map.html");
	});

	asyncTest("read config", function(){
		writeIframe(makeStealHTML(
			"basics/basics.html",
			'src="../../steal.js?configed" data-config="../config.js"'));
	});

	asyncTest("compat - production bundle works", function(){
		makeIframe("production/prod.html");
	});

	asyncTest("production bundle specifying main works", function(){
		makeIframe("production/prod-main.html");
	});

	asyncTest("steal.production.js doesn't require setting env", function(){
		makeIframe("production/prod-env.html");
	});

	asyncTest("automatic loading of css plugin", function(){
		makeIframe("plugins/site.html");
	});

	asyncTest("product bundle with css", function(){
		makeIframe("production/prod-bar.html");
	});

	asyncTest("automatic loading of less plugin", function(){
		makeIframe("dep_plugins/site.html");
	});


	asyncTest("Using path's * qualifier", function(){
		writeIframe(makeStealHTML(
			"basics/basics.html",
			'src="../steal.js?../paths" data-config="../paths/config.js"'));
	});

	asyncTest("url paths in css work", function(){
		makeIframe("css_paths/site.html");
	});

	asyncTest("ext extension", function(){
		makeIframe("extensions/site.html");
	});

	asyncTest("forward slash extension", function(){
		makeIframe("forward_slash/site.html");
	});

	asyncTest("a steal object in the page before steal.js is loaded will be used for configuration",function(){
		makeIframe("configed/steal_object.html");
	});

	asyncTest("compat - product bundle works", function(){
		makeIframe("prod-bundlesPath/prod.html");
	});

	asyncTest("System.instantiate preventing production css bundle", function(){
		makeIframe("production/prod-inst.html");
	});

	asyncTest("Multi mains", function(){
		makeIframe("multi-main/dev.html");
	});

	asyncTest("@loader is current loader", function(){
		makeIframe("current-loader/dev.html");
	});
	asyncTest("@loader is current loader with es6", function(){
		makeIframe("current-loader/dev-es6.html");
	});
	asyncTest("less loads in the right spot", function(){
		makeIframe("less-imports/dev.html");
	});

	asyncTest("set options to less plugin", function(){
		makeIframe("less_options/site.html");
	});

	/*
	asyncTest("Loads traceur-runtime automatically", function(){
		makeIframe("traceur_runtime/dev.html");
	});
	*/

	asyncTest("allow truthy script options (#298)", function(){
		makeIframe("basics/truthy_script_options.html");
	});

	asyncTest("using babel as transpiler works", function(){
		makeIframe("babel/site.html");
	});

	asyncTest("inline code", function(){
		makeIframe("basics/inline_code.html");
	});

	asyncTest("can load a bundle with an amd module depending on a global", function(){
		makeIframe("prod_define/prod.html");
	});

	module("json extension");

	asyncTest("json extension", function(){
		makeIframe("json/dev.html");
	});

	module("npm");

	asyncTest("default-main", function(){
		makeIframe("npm/default-main.html");
	});

	asyncTest("alt-main", function(){
		makeIframe("npm/alt-main.html");
	});

	asyncTest("production", function(){
		makeIframe("npm/prod.html");
	});

	asyncTest("with bower", function(){
		makeIframe("npm/bower/index.html");
	});

	asyncTest("forward slash with npm", function(){
		makeIframe("npm-deep/dev.html");
	});

	module("Bower extension");

	asyncTest("Basics work", function(){
		makeIframe("bower/site.html");
	});
	asyncTest("Doesn't overwrite paths", function(){
		makeIframe("bower/with_paths/site.html");
	});
	asyncTest("Works in place of @config", function(){
		makeIframe("bower/as_config/site.html");
	});

	asyncTest("Loads config automatically", function(){
		makeIframe("bower/default-config.html");
	});

	asyncTest("with npm", function(){
		makeIframe("bower/npm/index.html");
	});

	if(window.Worker) {
		asyncTest("webworkers", function(){
			makeIframe("webworkers/dev.html");
		});
	}


})();
