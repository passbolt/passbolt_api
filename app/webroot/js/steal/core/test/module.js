module('Module')

var Module = moduleManager(st, [], {}, new ConfigManager)


test('Module.make always returns same module for the same id', function(){
	var res = Module.make({id: 'jquery'});
	equal(res, Module.make({id: 'jquery'}))
})

test('loaded, run and completed are deferreds', function(){
	var res = Module.make({id: 'jquery'})
	ok(TH.isDeferred(res.loaded))
	ok(TH.isDeferred(res.run))
	ok(TH.isDeferred(res.completed))
})

test('module options will be extended if called twice for the same id', function(){
	var res = Module.make({id: 'jquery'})
	var res2 = Module.make({id: 'jquery', foo: 'bar'})
	equal(res.options.foo, 'bar')
})

test('callback functions for deferreds should be called', 2, function(){
	var res = Module.make({id: 'jquery'})
	var callbacks = ['completed', 'loaded'];
	for(var i = 0; i < callbacks.length; i++){
		res[callbacks[i]].then(function(){
			ok(true)
		})
	}
	res.complete();
	res.load();
})

test('calling execute should call deferred functions and st.require.', 3, function(){
	var res = Module.make({id: 'jquery'})
	var callbacks = ['completed', 'loaded'];
	var stealRequire = st.require;
	st.require = function(){
		ok(true)
	}
	for(var i = 0; i < callbacks.length; i++){
		res[callbacks[i]].then(function(){
			ok(true)
		})
	}
	res.execute();
	st.require = stealRequire;
})

test('correct load functions should be called for every type', function(){
	var originalTypeFns = {};
	var types = st.config().types;
	var typeLoadersCalled = [];
	var load = [
		'jquery.js',
		function(){},
		'foo.text',
		'foo.css'
	]
	var assertRequire = function(type){
		return function(){
			typeLoadersCalled.push(type);
		}
	}
	var assertFns = {};
	for(var type in types){
		originalTypeFns[type] = types[type].require;
		assertFns[type] = assertRequire(type);
	}
	st.config({types: assertFns})
	for(var i = 0; i < load.length; i++){
		var r = Module.make(load[i]);
		r.execute();
	}
	equal(typeLoadersCalled.join(), "js,fn,text,css");
	st.config({types: originalTypeFns})
})