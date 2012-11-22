module('Interactive')

test('Module should ignore itself as a dependency', function(){
	var uri    = "foo/bar.js";
	URI.cur    = uri;
	var module = Module.make(function(){});
	module.options.src = uri;
	module.executed = h.after(function(){
		ok(true)
	}, module.executed)
	interactives[uri]  = module;
	module.execute();

})