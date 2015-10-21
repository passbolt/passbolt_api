/*global Recipe*/
steal("can/map/backup", "can/model", "can/test", "steal-qunit", function () {
	QUnit.module('can/map/backup', {
		setup: function () {
			can.Map.extend('Recipe');
		}
	});
	test('backing up', function () {
		var recipe = new Recipe({
			name: 'cheese'
		});
		ok(!recipe.isDirty(), 'not backedup, but clean');
		recipe.backup();
		ok(!recipe.isDirty(), 'backedup, but clean');
		recipe.attr('name', 'blah');
		ok(recipe.isDirty(), 'dirty');
		recipe.restore();
		ok(!recipe.isDirty(), 'restored, clean');
		equal(recipe.name, 'cheese', 'name back');
	});
	test('backup / restore with associations', function () {
		can.Map('Instruction');
		can.Map('Cookbook');
		can.Map('Recipe', {
			attributes: {
				instructions: 'Instruction.models',
				cookbook: 'Cookbook.model'
			}
		}, {});
		var recipe = new Recipe({
			name: 'cheese burger',
			instructions: [{
				description: 'heat meat'
			}, {
				description: 'add cheese'
			}],
			cookbook: {
				title: 'Justin\'s Grillin Times'
			}
		});
		//test basic is dirty
		ok(!recipe.isDirty(), 'not backedup, but clean');
		recipe.backup();
		ok(!recipe.isDirty(), 'backedup, but clean');
		recipe.attr('name', 'blah');
		ok(recipe.isDirty(), 'dirty');
		recipe.restore();
		ok(!recipe.isDirty(), 'restored, clean');
		equal(recipe.name, 'cheese burger', 'name back');
		// test belongs too
		ok(!recipe.cookbook.isDirty(), 'cookbook not backedup, but clean');
		recipe.cookbook.backup();
		recipe.cookbook.attr('title', 'Brian\'s Burgers');
		ok(!recipe.isDirty(), 'recipe itself is clean');
		ok(recipe.isDirty(true), 'recipe is dirty if checking associations');
		recipe.cookbook.restore();
		ok(!recipe.isDirty(true), 'recipe is now clean with checking associations');
		equal(recipe.cookbook.title, 'Justin\'s Grillin Times', 'cookbook title back');
		//try belongs to recursive restore
		recipe.cookbook.attr('title', 'Brian\'s Burgers');
		recipe.restore();
		ok(recipe.isDirty(true), 'recipe is dirty if checking associations, after a restore');
		recipe.restore(true);
		ok(!recipe.isDirty(true), 'cleaned all of recipe and its associations');
	});
	
	test('backup restore nested observables', function () {
		var observe = new can.Map({
			nested: {
				test: 'property'
			}
		});
		equal(observe.attr('nested')
			.attr('test'), 'property', 'Nested object got converted');
		observe.backup();
		
		observe.attr('nested')
			.attr('test', 'changed property');
			
		equal(observe.attr('nested')
			.attr('test'), 'changed property', 'Nested property changed');
			
		ok(observe.isDirty(true), 'Observe is dirty');
		observe.restore(true);
		equal(observe.attr('nested')
			.attr('test'), 'property', 'Nested object got restored');
	});
	
	test('backup removes properties that were added (#607)', function () {
		var map = new can.Map({});
		map.backup();
		map.attr('foo', 'bar');
		ok(map.isDirty(), 'the map with an additional property is dirty');
		map.restore();
		ok(!map.attr('foo'), 'there is no foo property');
	});

	test('isDirty wrapped in a compute should trigger changes #1417', function() {
		expect(2);
		var recipe = new Recipe({
			name: 'bread'
		});

		recipe.backup();

		var c = can.compute(function() {
			return recipe.isDirty();
		});

		ok(!c(), 'isDirty is false');

		c.bind('change', function() {
			ok(c(), 'isDirty is true and a change has occurred');
		});

		recipe.attr('name', 'cheese');
	});
});
