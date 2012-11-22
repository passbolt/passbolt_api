module('Deferred');

test('doneFuncs and failFuncs should be empty arrays', function(){
	var def = Deferred();
	equal(def.doneFuncs.length, 0)
	equal(def.failFuncs.length, 0)
})

test('resolve should call all done functions', 2, function(){
	var def = Deferred();
	def.then(function(){
		ok(true)
	})
	def.then(function(){
		ok(true)
	})
	def.resolve();
})

test('reject should call all fail functions', 2, function(){
	var def = Deferred();
	def.then(function(){
		ok(true)
	}, function(){
		ok(true)
	})
	def.then(function(){
		ok(true)
	}, function(){
		ok(true)
	})
	def.reject();
})

test('functions mapped to always should be called both on resolve and reject', 2, function(){
	var def = Deferred();
	def.always(function(){
		ok(true)
	});
	def.resolve();
	var def = Deferred();
	def.always(function(){
		ok(true)
	});
	def.reject();

})

test('Deferred.when', 2, function(){
	var resolvedTimes = 0;
	var def = Deferred();
	def.then(function(){
		equal(resolvedTimes, 0);
		resolvedTimes++;
	})
	var waitingDef = Deferred.when(def);
	waitingDef.then(function(){
		equal(resolvedTimes, 1);
	})
	def.resolve();
})

test('deferred statuses', function(){
	var rejected = Deferred();
	equal(rejected.isRejected(), false);
	rejected.reject();
	ok(rejected.isRejected());
	var resolved = Deferred();
	equal(resolved.isResolved(), false);
	resolved.resolve();
	ok(resolved.isResolved());
})