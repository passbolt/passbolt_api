module('Helpers')

test('each', function(){
	var arr = [1,2,3];
	h.each(arr, function(i, val){
		equal(val, arr[i]);
	})
	var obj = {foo: 'bar', bar: 'baz'};
	h.each(obj, function(key, val){
		equal(val, obj[key]);
	})
})

test('uniquePush', function(){
	var arr = [1,2,3];
	h.uniquePush(arr, 1);
	h.uniquePush(arr, 4);
	equal(arr.join(), "1,2,3,4");
})

test('isString', function(){
	equal(h.isString('foo'), true);
	equal(h.isString({}), false);
})

test('isFn', function(){
	equal(h.isFn(function(){}), true);
	equal(h.isFn("foo"), false);
})

test('endsInSlashRegex', function(){
	ok(h.endsInSlashRegex.test('foo/bar/'));
	equal(h.endsInSlashRegex.test('foo'), false);
})

test('createElement', function(){
	var el = h.createElement('div');
	ok((typeof el.nodeType !== 'undefined'))
	equal(el.nodeName, 'DIV');
})

test('scriptTag', function(){
	var el = h.scriptTag();
	equal(el.nodeName, 'SCRIPT')
})

test('head', function(){
	var el = h.head();
	equal(el.nodeName, 'HEAD');
	equal(el, h.head(), 'head helper always returns same element');
})



test('before', 3, function(){
	var fn = function(foo){
		return foo;
	}
	var beforeFn = h.before(fn, function(){
		ok(true);
	})
	var beforeWithChangeArgsFn = h.before(fn, function(foo){
		return ['bar'];
	}, true)
	equal(beforeFn('foo'), 'foo');
	equal(beforeWithChangeArgsFn('foo'), 'bar');
})

test('after', 6, function(){
	var fn = function(){
		ok(true);
		return 'bar';
	}
	var afterFn = h.after(fn, function(foo){
		equal(foo, 'foo');
		return foo;
	})
	var afterWithChangeArgsFn = h.after(fn, function(foo){
		equal(foo, 'bar');
		return foo;
	}, true)
	equal(afterFn('foo'), 'bar');
	equal(afterWithChangeArgsFn('foo'), 'bar');
})