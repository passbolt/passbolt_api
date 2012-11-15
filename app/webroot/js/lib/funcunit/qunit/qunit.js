steal("funcunit/qunit/qunit-1.10.js", function(){
	// on ready because that is when the window is loaded AND when 
	// steal has finished
	steal.bind("ready", function(){
		QUnit.config.autorun = false;
		QUnit.config.reorder = false;
		QUnit.config.urlConfig.push('coverage', 'noautorun');
		QUnit.load();
	})
	if(QUnit.urlParams["coverage"]){
		steal("steal/instrument", function(){
			// default ignores
			var ignores = ["/jquery","/can","/funcunit","/steal","/documentjs","*/test","*_test.js","*funcunit.js"] 
			if(typeof FuncUnit !== "undefined" && FuncUnit.coverageIgnore){
				ignores = FuncUnit.coverageIgnore;
			}
			
			// overwrite with our own ignores
			steal.instrument.ignores = ignores;
			steal("funcunit/coverage", function(){
				var reportBuilt = false;
				QUnit.done(function(){
					if(!reportBuilt){
						reportBuilt = true;
						var data = steal.instrument.compileStats()
						QUnit.coverage(data);
					}
				})
			})
		})
	}
	
	var appendToBody = function(type, id){
			var el = document.createElement(type);
			el.setAttribute("id", id);
			document.body.appendChild( el );
		}, 
		id = function(id){
			return document.getElementById(id);
		}
	
		
	// TODO remove this once jquery patches http://bugs.jquery.com/ticket/10373
	var gCS = window.getComputedStyle;
	window.getComputedStyle = function(elem){
		if(elem.ownerDocument.defaultView && window.getComputedStyle !== elem.ownerDocument.defaultView.getComputedStyle) {
			return elem.ownerDocument.defaultView.getComputedStyle( elem, null );
		}
		try {
			return gCS(elem, null);
		} catch (ex) {
			// Here's to IE 8 and under:
			return elem.currentStyle;
		}
	}
	
	// set up page if it hasn't been
	if(!document.getElementsByTagName("link").length){
		steal("funcunit/qunit/qunit.css")
	}
	if(!id("qunit-header")){
		appendToBody("h1", "qunit-header");
	}
	if(!id("qunit-banner")){
		appendToBody("h2", "qunit-banner");
	}
	if(!id("qunit-testrunner-toolbar")){
		appendToBody("div", "qunit-testrunner-toolbar");
	}
	if(!id("qunit-userAgent")){
		appendToBody("h2", "qunit-userAgent");
	}
	if(!id("test-content")){
		appendToBody("div", "test-content");
	}
	if(!id("qunit-tests")){
		appendToBody("ol", "qunit-tests");
	}
	if(!id("qunit-test-area")){
		appendToBody("div", "qunit-test-area");
	}
	
	// backwards compatibility
	window.equals = window.equal;
	window.same = window.deepEqual;
	
}, 'funcunit/browser/events.js')