steal('funcunit', function(){

	module("error", {
		// runs before each test
		setup: function(){
		},
		// runs after each test
		teardown: function(){
		}
	});	
	
	test('error.* : Check mad common errors', function(){
		for(var type in mad.error){
			try{
				throw new mad.error[type]();
			}
			catch(e){
				equal(e.name, type);
			}
		}
	});

});
