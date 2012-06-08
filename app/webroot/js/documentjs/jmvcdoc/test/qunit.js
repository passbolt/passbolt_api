steal.plugins('funcunit/qunit').then("//documentjs/jmvcdoc/models/search",function(){
	
module("search");

test("findOne by name", function(){
	stop();
	Doc.location = steal.root.join("jmvc/docs/");
	
	
	console.log(Doc.location)
	
	Doc.load(function(){
		
		
		var Class = Doc.findOne({
			name: "jQuery.Class"
		});
		ok(Class);
		ok(Class.name, "jQuery.Class")
		//var children = Class.children();
		
		equal(Class.children().length,2, "class has children")
				
		start();
	});
});

test("findOne all by name", function(){
	stop();
	Doc.location = steal.root.join("jmvc/docs/");
	Doc.findOne({ name: "jQuery.Class" }, function(data){
		ok(data);
		start();
	});
});

test("findAll by search", function(){
	stop();
	Doc.location = steal.root.join("jmvc/docs/");
	
	Doc.load(function(){
		
		
		var docs = Doc.findAll({
			search: "Class"
		});
		
		ok(docs.length, "we have things wiht class")
		
		for(var i =0; i < docs.length; i++){
			if(! /class/i.test( docs[i].name )) {
				ok(false, "Something doesn't have the name "+docs[i].name)
			}
		}
		
		//ok(Class);
		//ok(Class.name, "jQuery.Class")
		//var children = Class.children();
		start();
	});
})


})
