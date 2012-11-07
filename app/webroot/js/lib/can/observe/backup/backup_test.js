steal('can/util', 'can/observe/backup', function() {
	
module("can/observe/backup",{
	setup : function(){
		can.Observe.extend("Recipe")
	}
})

test("backing up", function(){
	var recipe = new Recipe({name: "cheese"});
	ok(!recipe.isDirty(), "not backedup, but clean")
	
	recipe.backup();
	ok(!recipe.isDirty(), "backedup, but clean");
	
	recipe.attr('name', 'blah')
	
	ok(recipe.isDirty(), "dirty");
	
	recipe.restore();
	
	ok(!recipe.isDirty(), "restored, clean");
	
	equals(recipe.name, "cheese" ,"name back");
	
});

test("backup / restore with associations", function(){
	can.Observe("Instruction");
	can.Observe("Cookbook");
	
	can.Observe("Recipe",{
		attributes : {
			instructions : "Instruction.models",
			cookbook: "Cookbook.model"
		}
	},{});
	
	

	
	var recipe = new Recipe({
		name: "cheese burger",
		instructions : [
			{
				description: "heat meat"
			},
			{
				description: "add cheese"
			}
		],
		cookbook: {
			title : "Justin's Grillin Times"
		}
	});
	
	//test basic is dirty
	
	ok(!recipe.isDirty(), "not backedup, but clean")
	
	recipe.backup();
	ok(!recipe.isDirty(), "backedup, but clean");
	
	recipe.attr('name', 'blah')
	
	ok(recipe.isDirty(), "dirty");
	
	recipe.restore();
	
	ok(!recipe.isDirty(), "restored, clean");
	
	equals(recipe.name, "cheese burger" ,"name back");
	
	// test belongs too
	
	ok(!recipe.cookbook.isDirty(), "cookbook not backedup, but clean");
	
	recipe.cookbook.backup();
	
	recipe.cookbook.attr("title","Brian's Burgers");
	
	ok(!recipe.isDirty(), "recipe itself is clean");
	
	ok(recipe.isDirty(true), "recipe is dirty if checking associations");
	
	recipe.cookbook.restore()
	
	ok(!recipe.isDirty(true), "recipe is now clean with checking associations");
	
	equals(recipe.cookbook.title, "Justin's Grillin Times" ,"cookbook title back");
	
	//try belongs to recursive restore
	
	recipe.cookbook.attr("title","Brian's Burgers");
	recipe.restore();
	ok(recipe.isDirty(true), "recipe is dirty if checking associations, after a restore");
	
	recipe.restore(true);
	ok(!recipe.isDirty(true), "cleaned all of recipe and its associations");
	
	
});

test("backup restore nested observables", function() {
	var observe = new can.Observe({
		nested : {
			test : 'property'
		}
	});

	equal(observe.attr('nested').attr('test'), 'property', 'Nested object got converted');
	observe.backup();
	observe.attr('nested').attr('test', 'changed property');
	equal(observe.attr('nested').attr('test'), 'changed property', 'Nested property changed');
	ok(observe.isDirty(true), 'Observe is dirty');
	observe.restore(true);
	equal(observe.attr('nested').attr('test'), 'property', 'Nested object got restored');
});

})()
