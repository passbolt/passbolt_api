steal('jquery').then(function(){

module("steal")

	var orig = steal.URI( steal.config().root+'' );
		src = function(src){
		return orig.join(src)
	},
	bId = function(id){
		return document.getElementById(id);
	},
	URI = steal.URI;

// TODO IE runs out of memory here. Check why
if(window !== window.parent && window.parent.QUnit && !$.browser.msie){
	var methods = ["module", "test", "start", "stop", "equals", "ok", "same", "equal", "expect"];
	for(var i=0; i<methods.length; i++){
		(function(method){
			window[method] = function(){
				window.parent[method].apply(this, arguments);
			}
		})(methods[i])
	}
}




// testing new steal API

// test("steal.config().root", function(){
// 	// this test is for IE7, where steal.config().root was a relative path (different from all other browsers) before the fix
// 	// the test verifies that :// is in the root...if its not, its probably broken
// 	ok(/\:\/\//.test(steal.config().root), "steal.config().root has :// in it")
// })

test("packages", function(){
	same(packagesStolen,["0","1","2", "uses"],"defined works right")
})

test("steal one js", function(){
	// doesn't this imply the next ...
	stop();

	steal("steal/test/files/steal.js", function(){
		start();
		equals(REQUIRED,"steal", "loaded the file")
	})
})

test("steal one function", function(){

	steal.config({root: "../../"})
	steal.URI.cur = URI("foo/bar.js");
	stop();
	steal(function(){
		start();
		ok(true, "function called")
	})
});


test("loading plugin from jmvcroot", function(){
	PLUGINLOADED = false;
	DEPENCENCYLOADED = false;
	stop();
	steal.config({root: "../../"})
	steal('steal/test/files/plugin',function(){
		equals(PLUGINLOADED, true)
		equals(DEPENCENCYLOADED, true)
	start();
	})
})

// unless the path has nothing on it, it should add a .js to the end and load the literal file
test("not using extension", function(){
	REQUIRED = false;
	stop();
	steal.config({root: "../../"})
	steal('./files/require',function(){
		equals(REQUIRED, true);
		start();
	})
})

test("loading file from jmvcroot", function(){
	REQUIRED = false;
	stop();
	steal.config({root: "../../"})
	steal('steal/test/files/require.js',function(){
		equals(REQUIRED, true)
		start();
	})
})

test("loading two files", function(){
	ORDER = [];
	stop();
	steal.config({root: "../../"})
	steal('./files/file1.js',function(){
		same(ORDER,[1,2,"then2","then1"])
		start();
	})
})

test("steal one file with different URI.root", function(){
	// doesn't this imply the next ...

	steal.config({root: "../"})
	REQUIRED = undefined;
	stop();

	// still loading relative to the page
	steal("./files/steal.js", function(){
		start();
		equals(REQUIRED,"steal", "loaded the file")
	})
})

test("loading same file twice", function(){
	ORDER = [];
	stop();
	steal.config({root: "../../"})
	steal('./files/duplicate.js', './files/duplicate.js',function(){
		same(ORDER,[1])
		start();
	})
})

test("loading same file twice with absolute paths", function(){
	ORDER = [];
	stop();
	steal.config({root: "../../"})
	steal('./files/loadDuplicate.js').then('//steal/test/files/duplicate.js',function(){
		same(ORDER,[1])
		start();
	})
})


test("steal one file with different cur", function(){
	// doesn't this imply the next ...
	steal.config({root: "../../"})
	steal.URI.cur = steal.URI("foo/bar.js");
	REQUIRED = undefined;
	stop();

	// still loading relative to the page
	steal("../steal/test/files/steal.js", function(){
		start();
		// next line is commented out b/c it won't actually re-run this file
		//equals(REQUIRED,"steal", "loaded the file")
	})
});

var URI = steal.URI;

module("uri");

test("to String", function(){
	equals(""+URI("abc"),"abc");
})

test("dir", function(){
	equals( ''+URI("http://a.com/d/e").dir(), "http://a.com/d");
	equals( ''+URI("d/e#fasdfa").dir(), "d");
	equals("/a/b/c", URI("/a/b/c/cookbook.html").dir(), "/a/b/c dir is correct.")
	equals("a/b/c", URI("a/b/c/cookbook.html").dir(), "a/b/c dir is correct.")
	equals("../a/b/c", URI("../a/b/c/cookbook.html").dir(), "../a/b/c dir is correct.")
	equals("http://127.0.0.1:3007/", URI("http://127.0.0.1:3007/cookbook.html").dir(), "http://127.0.0.1:3007 dir is correct.")
})

test("isCrossDomain", function(){
	ok( URI("http://foo.bar").isCrossDomain("http://abc.def"), "two different hosts" )
	ok( !URI("bar").isCrossDomain("http://abc.def"), "two different hosts" )
	ok(  URI("http://abc.def").isCrossDomain("bar"), "two different hosts" )

	ok( URI("http://abc.def").isCrossDomain()  )
})
test("join", function() {

	equals(''+URI("http://abc.com").join("/a/b/c"), "http://abc.com/a/b/c", "http://abc.com/a/b/c was joined successfuly.");


	equals(''+URI("http://abc.com/").join("/a/b/c"), "http://abc.com/a/b/c", "http://abc.com/a/b/c was joined successfuly.");

	equals(''+URI("http://abc.com/").join("a/b/c"), "http://abc.com/a/b/c", "http://abc.com/ + a/b/c was joined successfuly.");



	equals(''+URI("http://abc.com").join("a/b/c"), "http://abc.com/a/b/c", "http://abc.com/a/b/c was joined successfuly.");


	equals(''+URI("a/b/c").join("d/e"), "a/b/c/d/e", "a/b/c + d/e was joined successfuly.");


	equals(''+URI("a/b/c/").join("d/e"), "a/b/c/d/e", "a/b/c/ + d/e was joined successfuly.");


	equals(''+URI("a/b/c/").join("/d/e"), "/d/e", "/d/e was joined successfuly.");

	equals(''+URI("a/b/c").join("/d/e"), "/d/e", "/d/e was joined successfuly.");


	equals( ''+URI('/d/e').join('a/b.c'), "/d/e/a/b.c", "/d/e/a/b.c is correctly joined.");


	equals(''+URI('d/e').join('a/b.c'), "d/e/a/b.c", "d/e/a/b.c is correctly joined.");

	equals(''+URI('d/e/').join('a/b.c'), "d/e/a/b.c", "d/e/a/b.c is correctly joined.");

	equals(''+URI('http://abc.com').join('a/b.c'), "http://abc.com/a/b.c", "http://abc.com/a/b.c is correctly joined.");

	equals(''+URI('http://abc.com').join('/a/b.c'), "http://abc.com/a/b.c", "http://abc.com/a/b.c is correctly joined.");

	equals(''+URI('http://abc.com/').join('a/b.c'), "http://abc.com/a/b.c", "http://abc.com/a/b.c is correctly joined.");

	equals(''+URI('http://abc.com/').join('/a/b.c'), "http://abc.com/a/b.c", "http://abc.com/a/b.c is correctly joined.");

	equals(''+URI('../d/e').join('a/b.c'), "../d/e/a/b.c", "../d/e/a/b.c is correctly joined.");

	equals(''+URI('').join('a/b.c'), "a/b.c", "'' + a/b.c is correctly joined.");

	equals(''+URI('').join('/a/b.c'), "/a/b.c", "'' + /a/b.c is correctly joined.");

	equals(''+URI('cookbook/').join('../../up.js'), "../up.js", "up.js is correctly joined.")

});

test("pathTo", function() {
	//result = new steal.File("http://abc.com/d/e").toReferenceFromSameDomain("http://abc.com/d/e/f/g/h");
	equals(''+URI("http://abc.com/d/e/f/g/h").pathTo("http://abc.com/d/e"), "../../../", "../../../ is the correct reference from same domain result.");

	equals(''+URI("http://abc.com/d/e/f/g/h").pathTo("http://abc.com/d/e/x/y"), "../../../x/y", "../../../x/y is the correct reference from same domain result.");

	equals(''+URI("a/b/c/d/e").pathTo("a/b/c/x/y"), "../../x/y", "../../x/y is the correct reference from same domain result.");

	equals(''+URI("a/b/c/d/e").pathTo("a/b/c/d/e"), "", "'' is the correct reference from same domain result.");
})

test("normalize", function(){
	var start= URI.cur;
	// normalizes from cur file (cur file should be kept relative to root)
	URI.cur = URI("/a/b/");
	equals(URI("./c/d").normalize(), "/a/b/c/d", "/a/b/c/d was normalized successfuly.");


	URI.cur = URI("/a/b/c");
	equals(URI("//d/e").normalize(), "d/e", "d/e was normalized successfuly.");

	URI.cur = URI("/a/b/c");
	equals(URI("/d/e").normalize(), "/d/e", "/d/e was normalized successfuly.");

	URI.cur = URI("http://abc.com");
	equals(URI("./d/e").normalize(), "http://abc.com/d/e", "http://abc.com/d/e was normalized successfuly.");

	URI.cur = URI("http://abc.com");
	equals(URI("/d/e").normalize(), "http://abc.com/d/e", "http://abc.com/d/e was normalized successfuly.");

	URI.cur = start;
})

test("filename", function(){
	equals(URI('jquery').filename(),'jquery');
	equals(URI("http://abc.com/d/e").filename(),'e');
});


	test("rootSrc", function(){
		steal.config({root: "../abc/"})
		equals( steal.URI.cur+'' , "../../qunit.html", "cur changed right");
	})

	test("request async", function(){
		stop();
		var count = 0;
		steal.request({
			src : src('steal/test/files/something.txt?' + Math.random())  // add random to force IE to behave
		}, function(txt){
			equals(txt,  "Hello World", "world is hello")
			start();
			count++;
		})
		if(!/file/.test(location.protocol))
			equals(count, 0);
	});

	test("request async error", function(){
		stop();
		var count = 0;
		steal.request({
			src : src('steal/test/files/a.txt')
		}, function(txt){
			ok(false,  "I should not be here")
			start();
			count++;
		},function(){
			ok(true, "I got an error");
			start();
			count++;
		})
		if(!/file/.test(location.protocol))
			equals(count, 0);
	});

	test("request sync", function(){
		stop();
		var count = 0;
		steal.request({
			src : src('steal/test/files/something.txt'),
			async: false
		}, function(txt){
			equals(txt,  "Hello World", "world is hello")
			start();
			count++;
		})
		equals(count, 1);
	});



	/*test("require JS", function(){
		steal.config({root: "../../"})
		stop();
		steal({
			id: src('steal/test/files/require.js'),
			type: "js"
		}, function(){
			start();
			ok(REQUIRED, "loaded the file")
		})
	});*/

	test("require CSS", function(){
		steal.config({root: "../../"})
		stop();
		steal({
			id: src('steal/test/files/require.css'),
			type: "css"
		}, function(){
			setTimeout(function(){
				start();
				ok( bId('qunit-header').clientHeight > 65, "Client height changed to "+bId('qunit-header').clientHeight );
			},1000)


		})
	});

	test("require weirdType", function(){
		stop();

		steal.type("foo js", function(options, success, error){
			var parts = options.text.split(" ")
			options.text = parts[0]+"='"+parts[1]+"'";
			success();
		});

		steal({
			id: src('steal/test/files/require.foo'),
			type: "foo"
		}, function(){
			start();
			equals(REQUIRED,"FOO", "loaded the file")

		})
	});

	// this has to be done via a steal request instead of steal.require
	// because require won't add buildType.  Require just gets stuff
	// and that is how it should stay.
	//
	//test("buildType set", function(){
	//	stop();
	//
	//	steal.URI.root("../");
	//	steal.type("foo js", function(options, success, error){
	//		var parts = options.text.split(" ")
	//		options.text = parts[0]+"='"+parts[1]+"'";
	//		success();
	//		equals(options.buildType, "js", "build type set right");
	//		equals(options.type, "foo", "type set right");
	//	});
	//
	//	steal('test/files/require.foo',function(){
	//		start();
	//	})
	//});
	//
	test("AOP normal", function(){
		var order = [],
			before = function(){
				order.push(1)
			},
			after = function(){
				order.push(2)
			};
		before = steal._before(before , function(){
			order.push(0)
		});
		after = steal._after(after , function(){
			order.push(3)
		})
		before();
		after();
		same(order, [0,1,2,3])
	})

	test("AOP adjust", function(){
		var order = [],
			before = function(arg){
				equal(arg,"Changed","modified original");
				order.push(1)
			},
			after = function(){
				order.push(2);
				return "OrigRet"
			};
		before = steal._before(before , function(arg){
			order.push(0);
			equal(arg,"Orig","retrieved original");
			return ["Changed"]
		}, true);
		after = steal._after(after , function(ret){
			order.push(3)
			equal(ret,"OrigRet","retrieved original");
			return "ChangedRet"
		}, true)
		before("Orig");
		var res = after();
		equal(res,"ChangedRet","updated return");
		same(order, [0,1,2,3])
	})
	test("getScriptOptions", function() {

		// Create all the combinations of valid script types
		var steals = [
			"steal.js",
			"steal.production.js",
			"/steal.js",
			"/steal.production.js",
			"/path/to/steal/steal.js",
			"/path/to/steal/steal.production.js",
			"../../steal.js",
			"../../steal.production.js"
		], startFiles = [
			"",
			"foo",
			"bar.js",
		], modes = [
			"",
			"production",
			"development"
		], srcs = [];

		$.each( steals, function( i, stealType ) {

			var env;

			if ( stealType.indexOf("production") > -1 ) {
				env = "production";
			}

			srcs.push({
				src: stealType,
				rootUrl : undefined,
				startFile : undefined,
				env : env
			});

			$.each( startFiles, function( i, startFile ) {
				var test = {
					src : [stealType, startFile ].join("?"),
					rootUrl : undefined
				}, expectedStartFile;

				if ( startFile ) {
					if ( startFile.indexOf(".js") > -1 ) {
						expectedStartFile = startFile;
					} else {
						expectedStartFile = startFile + "/" + startFile + ".js";
					}
				} else {
					expectedStartFile = undefined;
				}


				test.startFile = expectedStartFile;
				test.env = env;

				srcs.push( test );

				$.each( modes, function( i, mode ) {
							var test;
					if ( startFile && mode ) {
						srcs.push({
							src : [stealType, [startFile, mode ].join() ].join("?"),
							rootUrl: undefined,
							startFile : expectedStartFile,
							env : mode || env
						});
					}
				});
			});
		});

		// Test em!
		$.each( srcs, function( i, src ) {

			var script = document.createElement('script'),
				options, uri;

			script.src = src.src;

			uri = URI( script.src );


			options = steal.getScriptOptions( script );

			equals( options.startFile, src.startFile, "Correct startFile on " + src.src );
			equals( options.env, src.env, "Correct environment on " + src.src );

		});

	})

test("css", function(){
	document.getElementById("qunit-test-area").innerHTML = ("<div id='makeBlue'>Blue</div><div id='makeGreen'>Green</div>");
	equals(document.getElementById("makeBlue").clientWidth, 100, "relative in loaded");
	equals(document.getElementById("makeGreen").clientWidth, 50, "relative up loaded");

	// we'd have to check the imports.
	if(!document.createStyleSheet){
		var els = document.getElementsByTagName('link'),
		count = 0;
		for(var i =0; i< els.length; i++){
			if(els[i].href.indexOf('one.css') > -1){
				count++;
			}
		}
		equals(count, 1, "only one one.css loaded")
	}

})

test("loadtwice", function(){
	same(ORDERNUM,['func'])
});

// this was breaking in safari/chrome
test("ready", function(){
	stop()
	$(document).ready(function(){
		start()
		ok(true,'ready was called')
	})

});

test("loading multiple css file from jmvcroot", function(){
	steal.config({root: orig})
	stop();

	$("#qunit-test-area").append("<div id='blue'>loading multiple css file from jmvcroot - Blue</div>" +
		"<div id='red'>loading multiple css file from jmvcroot - Red</div>");

	steal("steal/test/bluecss/blue.css", "steal/test/redcss/red.css").then(function(){
		setTimeout(function() {
			var within = function(val, expected){
				var val = parseInt(val, 10);
				ok(val >= (expected-1) && val <= (expected+1));
			}

			within($('#blue').css("width"), 777);
			within($('#red').css("width"), 888);

			// we'd have to check the imports.
			if(!document.createStyleSheet){
				var els = document.getElementsByTagName('link'),
				count = 0;
				for(var i =0; i< els.length; i++){
					if(els[i].href.indexOf('blue.css') > -1 || els[i].href.indexOf('red.css') > -1){
						count++;
					}
				}
				equals(count, 2, "blue.css and red.css loaded")
			}
			start();
		}, 1000);
	});
});

// the tests below disable the global qunit error handler, otherwise
// they would cause failed assertions

var oldOnError;

module("steal errors", {
	setup: function(){
		oldOnError = window.onerror;
		window.onerror = function(){};
	},
	teardown: function(){
		window.onerror = oldOnError;
	}
});

test("don't abort on error", function(){
	stop();
	expect(1);

	steal({id: "./does_not_exist1.js", abort: false}, function(){
		ok(true, "executed steal fn");
		start();
	});
});

// Following test always fails in IE8 and lower so we don't run it for these browsers
var shouldRun = (function(){
	if(steal.isRhino) { return false; }

	var d = document.createElement('div');
	d.innerHTML = "<!--[if lt IE 9]>ie<![endif]-->";
	return !(d.innerText === "ie");
})()
if(shouldRun){
	test("runs error callback", function(){
		stop();
		expect(2);

		steal({id: "./does_not_exist2.js",
			abort: false,
			error: function(){
				ok(true, "executed error callback");
			}
		}, function(){
			ok(true, "executed steal fn");
			start();
		});
	});
}


test("needs", function(){
	stop();
	steal.config({root: "../../"})
	steal({
		id: "steal/test/files/needs.js",
		needs: ["steal/test/files/needed.js"]
	});
});

test("idToUri", function(){
	steal.config({root: "../../"})
	steal.config({
		paths: {
			"jquery": "can/util/jquery/jquery.1.7.1.js"
		}
	})
	console.log(steal.idToUri( "jquery/test/test.js", false ) + "")
	equals(steal.idToUri( "jquery/test/test.js" ), "../../jquery/test/test.js")
	equals(steal.idToUri( "jquery" ), "../../can/util/jquery/jquery.1.7.1.js")
	steal.config({
		paths: {
			"jquery/" : "http://cdn.com/jquery/"
		}
	})
	equals(steal.idToUri( "jquery/test/test.js" ), "http://cdn.com/jquery/test/test.js")
	equals(steal.idToUri( "jquery" ), "../../can/util/jquery/jquery.1.7.1.js")
});

//test("needs options", function(){
//	stop();
//	steal.options.needs.needs = 'steal/test/files/needstype.js'
//
//	steal.URI.root("../../").then('steal/test/files/needs.needs',
//		function(){
//
//		equals(NEEDS,"FOO")
//		start();
//
//	});
//});


test("modules", function(){
	
	steal.config({root: "../../"})
	stop();
	steal("steal/test/modules/module1.js", function(module1){
		ok(module1.foo, true)
		start();
	})
	
});

test("inline steals work with needs and shims", 2, function(){
	stop();
	$('body').append('<iframe src="inline_steals/inline_steals.html"></iframe>')
})
asyncTest("load 32 stylesheets", 2, function() {
	steal.config({root: orig})
	function normalizeColor( color ) {
	  if ( color.indexOf("rgb") !== -1 ) {
		color = "#" + $.map( color.split(","), function( part ) {
		  return ("0" + parseInt( part.replace(/[^\d]+/g, ""), 10).toString(16)).slice(-2);
		}).join("");
	  } else if ( color.indexOf("#") === 0 && color.length == 4 ) {
		color = "#" + $.map( color.replace("#","").split(""), function( part ) {
		  return part + part;
		}).join("");
	  }
	  return color;
	};

	var files = [];

	for ( var i = 0; i <= 32; i++ ) {
		files.push( "steal/test/files/32/" + i + ".css")
	}

	steal.apply(steal, files).then(function() {
	  d32 = $("<div>", {
		"id" : "thirtytwo"
	  }).appendTo( "#qunit-test-area" );

	  $("<div>", {
		"class" : "div32",
		"text" : "32"
	  }).appendTo( d32 );

	  $("<div>", {
		"class" : "div11",
		"text" : "11"
	  }).appendTo( d32 );

	  setTimeout(function() {
		var div11 = $(".div11"),
			div32 = $(".div32"),
			color1 = normalizeColor( div11.css("color")),
			color2 = normalizeColor( div32.css("color"));

		QUnit.equals( color1, "#001111" );
		QUnit.equals( color2, "#003322" );
		start();
	  }, 500)
	});

  });


});
