steal('funcunit/qunit', './string', function(qunit, can){
	
module("can/util/string")

test("can.sub", function(){
	equals(can.sub("a{b}",{b: "c"}),"ac")
	
	var foo = {b: "c"};
	
	equals(can.sub("a{b}",foo,true),"ac");
	ok(!foo.b,"b's value was removed")
});

test("can.sub double", function(){
	equals(can.sub("{b} {d}",[{b: "c", d: "e"}]),"c e");
})

test("String.underscore", function(){
	equals(can.underscore("Foo.Bar.ZarDar"),"foo.bar.zar_dar")
})


test("can.getObject", function(){
	var obj = can.getObject("foo", [{a: 1}, {foo: 'bar'}]);
	
	equals(obj,'bar', 'got bar')
	
	
	// test null data
	
	var obj = can.getObject("foo", [{a: 1}, {foo: 0}]);
	
	equals(obj,0, 'got 0 (falsey stuff)')
});

/*
test("$.String.niceName", function(){
	var str = "some_underscored_string";
	var niceStr = $.String.niceName(str);
	equals(niceStr, 'Some Underscored String', 'got correct niceName');
})*/

	
}).then('./deparam/deparam_test');
