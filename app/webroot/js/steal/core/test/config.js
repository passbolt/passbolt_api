module('Config')

config = new ConfigManager({});
stealConfig = config.stealConfig;

test('steal.config should return default config object', function(){
	equal(stealConfig.env, 'development');
})

test('steal.config.startFile', function(){
	config.attr({'startFile': 'foo/bar.html'})
	equal(config.attr('startFile'), 'foo/bar.html');
	equal(config.attr('production'), 'foo/production.js');
})


test('steal.getScriptOptions', function(){
	var script = h.scriptTag(), scriptOpts;
	script.src = "http://localhost/app/steal.production.js?foobarapp,development";
	scriptOpts = st.getScriptOptions(script);
	equal(scriptOpts.env, "development");
	equal(scriptOpts.root, "http://localhost/app");
	equal(scriptOpts.startFile, "foobarapp/foobarapp.js");
	script.src = "http://localhost/app/steal.production.js?foobarapp";
	scriptOpts = st.getScriptOptions(script);
	equal(scriptOpts.env, "production");
	equal(scriptOpts.root, "http://localhost/app");
	equal(scriptOpts.startFile, "foobarapp/foobarapp.js");
	script.src = "http://localhost/app/steal.production.js?foobarapp.js";
	scriptOpts = st.getScriptOptions(script);
	equal(scriptOpts.env, "production");
	equal(scriptOpts.root, "http://localhost/app");
	equal(scriptOpts.startFile, "foobarapp.js");
})

/*asyncTest('steal.config.shim', 7, function(){
	config.attr({
		shim: {
			"mocks/foobar" : {
				init: function(){
					return "foobar"
				}
			},
			"mocks/global" : {
				exports: "gLobal"
			},
			"mocks/hasdeps" : {
				init : function(global, foobar){
					equal(global, 42, "Arguments passed to the shim's init functions are correct");
					equal(foobar, "foobar", "Arguments passed to the shim's init functions are correct");
					return this.hasDeps;
				},
				deps : ["mocks/global", "mocks/foobar"]
			},
			"mocks/arraydeps" : ["mocks/global", "mocks/foobar"]
		}
	})
	var moduleA = Module.make({id: 'mocks/foobar'});
	moduleA.completed.then(function(){
		equal(moduleA.value, "foobar")
		start();
	})
	moduleA.execute();
	var moduleB = Module.make({id: 'mocks/global'});
	moduleB.completed.then(function(){
		equal(moduleB.value, 42)
		start();
	})
	moduleB.execute();
	var moduleC = Module.make({id: 'mocks/hasdeps'});
	moduleC.completed.then(function(){
		ok(moduleC.value)
		start();
	})
	moduleC.execute();
	var moduleD = Module.make({id: 'mocks/hasdeps'});
	moduleD.completed.then(function(){
		equal(window.gLobal, 42, "Deps were loaded before module")
		equal(window.foobar, "baz", "Deps were loaded before module")
		start();
	})
	moduleD.execute();
})*/