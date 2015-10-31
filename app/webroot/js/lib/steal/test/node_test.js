var steal = require("../main");
var assert = require("assert");


var makeSteal = function(config){
	var localSteal =  steal.clone();
	localSteal.System.config(config || {});
	return localSteal;
};

describe("plugins", function(){
	
	it("are able to convert less", function(done){
		var steal = makeSteal({
			config: __dirname+"/config.js",
			main: "dep_plugins/main"
		});
		steal.startup().then(function(){
			
			assert.ok( /width: 200px/.test( steal.System._loader.modules["dep_plugins/main.less!$less"].module.default.source ) );
			done();
		},done);
		
	});

	it("able to load a config without an absolute path", function(done){
		var pwd = process.cwd();
		process.chdir(__dirname);

		var steal = makeSteal({
			config: "config.js",
			main: "basics/basics"
		});
		steal.startup()
			.then(check, check)
			.then(done, done);

		function check(e) {
			process.chdir(pwd);
			assert(!(e instanceof Error), "Did not receive an error");
		}
	});
	
});
