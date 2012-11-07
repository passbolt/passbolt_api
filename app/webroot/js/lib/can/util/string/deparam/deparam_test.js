steal('funcunit/qunit','./deparam', function(qunit, can) {
	
module('can/util/string/deparam')

/** /
test("Basic deparam",function(){
	
	var data = can.deparam("a=b");
	equals(data.a,"b")
	
	var data = can.deparam("a=b&c=d");
	equals(data.a,"b")
	equals(data.c,"d")
})
/**/
test("Nested deparam",function(){
	
	var data = can.deparam("a[b]=1&a[c]=2");
	equals(data.a.b,1)
	equals(data.a.c,2)
	
	var data = can.deparam("a[]=1&a[]=2");
	equals(data.a[0],1)
	equals(data.a[1],2)
	
	var data = can.deparam("a[b][]=1&a[b][]=2");
	equals(data.a.b[0],1)
	equals(data.a.b[1],2)
	
	var data = can.deparam("a[0]=1&a[1]=2");
	equals(data.a[0],1)
	equals(data.a[1],2)
});


/** /
test("deparam an array", function(){
	var data = can.deparam("a[0]=1&a[1]=2");
	
	ok(can.isArray(data.a), "is array")
	
	equals(data.a[0],1)
	equals(data.a[1],2)
})
    
test("deparam object with spaces", function(){
   var data = can.deparam("a+b=c+d&+e+f+=+j+h+");
    
    equals(data["a b"], "c d")
    equals(data[" e f "], " j h ")
})
/**/
    
})
