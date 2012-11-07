steal('funcunit/qunit','./vector.js').then(function(){
	
	module("jquery/lang/vector")

	test("Init", function(){
		var v = new $.Vector(1,4, 2, 3);
		deepEqual([1,4,2,3], v.array, 'Array initialized properly');
	});

	test(".app", function(){
		var v = new $.Vector(0, 1, 2, 3),
			newV = v.app(function(val, i) {
				return val - i;
			});
		deepEqual(newV.array, [0, 0, 0, 0], 'Applied function to new vector');
	});

	test(".plus", function(){
		var v = new $.Vector(3, 4);
		deepEqual(v.plus(1, 2, 3).array, [4, 6, 3], 'Vector values added');
		deepEqual(v.plus(new $.Vector(2, 1)).array, [5, 5], 'Vector values added');
	});

	test(".minus", function(){
		var v = new $.Vector(3, 4);
		deepEqual(v.minus(1, 2, 3).array, [2, 2, -3], 'Vector values subtracted');
		deepEqual(v.minus(new $.Vector(2, 1)).array, [1, 3], 'Vector values subtracted');
	});
});