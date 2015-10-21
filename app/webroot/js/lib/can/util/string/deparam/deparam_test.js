steal('can/util/string/deparam', function () {
	module('can/util/string/deparam');
	/** /
test("Basic deparam",function(){

	var data = can.deparam("a=b");
	equal(data.a,"b")

	var data = can.deparam("a=b&c=d");
	equal(data.a,"b")
	equal(data.c,"d")
})
/**/
	test('Nested deparam', function () {
		var data = can.deparam('a[b]=1&a[c]=2');
		equal(data.a.b, 1);
		equal(data.a.c, 2);
		data = can.deparam('a[]=1&a[]=2');
		equal(data.a[0], 1);
		equal(data.a[1], 2);
		data = can.deparam('a[b][]=1&a[b][]=2');
		equal(data.a.b[0], 1);
		equal(data.a.b[1], 2);
		data = can.deparam('a[0]=1&a[1]=2');
		equal(data.a[0], 1);
		equal(data.a[1], 2);
	});
	test('Remaining ampersand', function () {
		var data = can.deparam('a[b]=1&a[c]=2&');
		deepEqual(data, {
			a: {
				b: '1',
				c: '2'
			}
		});
	});
	/** /
test("deparam an array", function(){
	var data = can.deparam("a[0]=1&a[1]=2");

	ok(can.isArray(data.a), "is array")

	equal(data.a[0],1)
	equal(data.a[1],2)
})
    
test("deparam object with spaces", function(){
   var data = can.deparam("a+b=c+d&+e+f+=+j+h+");
    
    equal(data["a b"], "c d")
    equal(data[" e f "], " j h ")
})
/**/
});
