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
					'steal.has("a.js","b.js");steal({id:"package/1.js",waits:!0,has:["jquery/jquery.js"]});steal({id:"package/css.css",waits:!0,has:["c.css"]});a;steal.executed("a.js");b;steal.executed("b.js");\n',
					"js works");
					
				s.test.equals(res.css.code,"c")
				
				s.test.clear();
			});
	});

});