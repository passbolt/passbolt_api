steal('can/construct/super', function(can) {

module("can/construct/super");

test("prototype super", function(){
	var A = can.Construct({
		init : function(arg){
			this.arg = arg+ 1;
		},
		add : function(num){
			return this.arg + num
		}
	})
	
	var B = A({
		init : function(arg){
			this._super(arg +2)
		},
		add : function(arg){
			return this._super(arg+1)
		}
	})
	
	var b = new B(1);
	equal(b.arg, 4)
	equal(b.add(2), 7)
});

test("static super", function(){
	var First = can.Construct({
		raise: function(num){
			return num;
		}
	},{});
	
	var Second = First({
		raise: function(num){
			return this._super(num)*num;
		}
	},{});
	
	equal(Second.raise(2), 4)
	
});

test("findAll super", function(){

	var Parent = can.Model({
		findAll: function(){
			equal(this.shortName, 'child');
			return new can.Deferred();
		},
		shortName : 'parent'
	},{});
	
	var Child = Parent({
		findAll: function(){
			return this._super();
		},
		shortName : 'child'
	},{});
	
	stop();
	expect(1);
	Child.findAll({});
	start();
});

/* Not sure I want to fix this yet.
test("Super in derived when parent doesn't have init", function(){
	can.Construct("Parent",{
	});
	
	Parent("Derived",{
		init : function(){
			this._super();
		}
	});

	try {
		new Derived();
		ok(true, "can call super in init safely")
	} catch (e) {
		ok(false, "Failed to call super in init with error: " + e)
	}
})*/

});