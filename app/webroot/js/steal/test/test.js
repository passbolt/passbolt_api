// this is test helpers for steal
steal('steal', function(steal){
	
var assertions = [],
	module = "";
steal.test =  {
	//clears every property fromt he window, returns steal (might return old stuff)
	clear: function() {
		var win = this.getWindow(),
			steal = steal;
		for(var n in win){
			if(n != "_S" && n != "STEALPRINT"){
				//this[n] = null;
				delete win[n];
			}
		}
		this.testNamespace();
		return steal;
	},
	// compares the file character counts, which is much faster than the string comparison
	compareFiles: function(expected, actual, msg){
		var actualJS = readFile(actual),
			expectedJS = readFile(expected);
		this.equals(actualJS.length, expectedJS.length, msg);
	},
	getWindow: function() {
		return (function(){return this}).call(null,0)
	},
	expect: function(num){
		var checkReady = function(){
			if(assertions.length >= num)
				return true;
	        return false;
	    }
	    while(!checkReady()){
	        java.lang.Thread.currentThread().sleep(300);
	    }
	},
	wait: function( name ) {
		var checkExists = function(name){
	        var parts = name.split(".");
	        var cur = this;
	        for(var i =0; i < parts.length; i++){
	            if(! cur[parts[i]] ){
	                return false;
	            }else
	                cur = cur[parts[i]];
	        }
	        return true;
	    }
	    while(!checkExists(name)){
	        java.lang.Thread.currentThread().sleep(300);
	    }
	},
	sleep: function( duration ){
        java.lang.Thread.currentThread().sleep(duration);		
	},
	print: function() {
		var win =this.getWindow();
		for(var n in win) print(n);
	},
	deleteDir: function( dir ) {
		dir = new java.io.File(dir)
		if (dir.isDirectory()) {
	        var children = dir.list();
	        for (var i=0; i<children.length; i++) {
	            var success = this.deleteDir(new java.io.File(dir, children[i]));
	            if (!success) return false;
	            
	        }
	    }
	
	    // The directory is now empty so delete it
	    return dir['delete']();
	},
	remove: function() {
		for(var i=0; i < arguments.length; i++){
			this.deleteDir(new java.io.File(arguments[i]) )
		}
	},
	testNamespace: function() {
		var win = this.getWindow();
		for(var n in win) {
			// add parser for coffeescript ... boo!
			if(n !== "_S" && n !== "STEALPRINT" && n !== "parser")
				throw "Namespace Pollution "+n;
		}
	},
	equals: function( a, b, message ) {
		if(a !== b)
			throw ""+a+"!="+b+":"+message
		else{
			assertions.push(message)
		}
	},
	ok: function( v, message ) {
		if(!v){
			throw "not "+v+" "+message
		}
		else{
			assertions.push(message)
		}
	},
	open: function( src , fireLoad ) {
		load("steal/rhino/env.js");
		if(typeof Envjs == 'undefined'){
			print("I DON'T GET IT")
		}
		Envjs(src, {
			scriptTypes: {
				"text/javascript": true,
				"text/envjs": true,
				"": true
			},
			fireLoad: true,
			logLevel: 2,
			afterScriptLoad: {
				// prevent $(document).ready from being called even though load is fired
				"jquery.js": function( script ) {
					window.jQuery && jQuery.readyWait++;
				},
				"steal.js": function(script){
					// a flag to tell steal we're in "build" mode
					// this is used to completely ignore files with the "ignore" flag set
					window.steal.isBuilding = true;
					// if there's timers (like in less) we'll never reach next line 
					// unless we bind to done here and kill timers
					window.steal.one('done', function(){
						Envjs.clear();
					});
					newSteal = window.steal;
				}
			},
			dontPrintUserAgent: true
		});
		
	},
	test : function(name, test){
		assertions = []
		test(steal.test);
		print("  -- "+name+" "+assertions.length)
	},
	module : function(name ){
		module = name;
		print("==========  "+name+"  =========")
	}
}
	return steal;
})