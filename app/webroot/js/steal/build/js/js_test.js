// load('steal/build/styles/test/styles_s.test.js')
/**
 * Tests compressing a very basic page and one that is using steal
 */
load('steal/rhino/rhino.js')
steal('steal', 'steal/test', function(s) {
	//STEALPRINT = false;
	s.test.module("steal/build/js")
	
	//STEALPRINT = false;

	s.test.test("makePackage", function(){
		load('steal/rhino/rhino.js');
		steal('steal/build/js',
			function(){
				var res = steal.build.js.makePackage(
				[
					{
						buildType : "js",
						id : "a.js",
						text: "a"
					},
					{
						buildType : "js",
						id : "b.js",
						text: "b"
					},
					{
						buildType : "css",
						id : "c.css",
						text: "c"
					}
				],
				{
					"package/1.js" : ["jquery/jquery.js"]
				},
				"package/css.css")
				
				s.test.equals(
					res.js,
					// tell what this file has
					'steal.has("a.js","b.js");'+
					// steal any packages this package depends on
					'steal({id:"package/1.js",waits:!0,has:["jquery/jquery.js"]});'+
					'steal({id:"package/css.css",waits:!0,has:["c.css"]});'+
					// clear pending for future steals
					'steal.pushPending();'+
					// the files and executed contexts
					'a;steal.executed("a.js");b;steal.executed("b.js");'+
					// pop the previous pending state into being so when this file completes, it's depeendencies will be executed
					'steal.popPending();'+
					'\n',
					"js works");
					
				s.test.equals(res.css.code,"c")
				
				s.test.clear();
			});
	});

});