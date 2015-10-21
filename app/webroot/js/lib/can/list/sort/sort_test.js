steal("can/list/sort", "can/test", "can/view/mustache", "can/view/stache", "can/model", "steal-qunit", function () {
	QUnit.module('can/list/sort');

	test('List events', (4*3), function () {
		var list = new can.List([{
			name: 'Justin'
		}, {
			name: 'Brian'
		}, {
			name: 'Austin'
		}, {
			name: 'Mihael'
		}]);
		list.attr('comparator','name');
		// events on a list
		// - move - item from one position to another
		//          due to changes in elements that change the sort order
		// - add (items added to a list)
		// - remove (items removed from a list)
		// - reset (all items removed from the list)
		// - change something happened
		// a move directly on this list
		list.bind('move', function (ev, item, newPos, oldPos) {
			ok(ev, '"move" event passed `ev`');
			equal(item.name, 'Zed', '"move" event passed correct `item`');
			equal(newPos, 3, '"move" event passed correct `newPos`');
			equal(oldPos, 0, '"move" event passed correct `oldPos`');
		});

		// a remove directly on this list
		list.bind('remove', function (ev, items, oldPos) {
			ok(ev, '"remove" event passed ev');
			equal(items.length, 1, '"remove" event passed correct # of `item`\'s');
			equal(items[0].name, 'Alexis', '"remove" event passed correct `item`');
			equal(oldPos, 0, '"remove" event passed correct `oldPos`');
		});

		list.bind('add', function (ev, items, index) {
			ok(ev, '"add" event passed ev');
			equal(items.length, 1, '"add" event passed correct # of items');
			equal(items[0].name, 'Alexis', '"add" event passed correct `item`');
			equal(index, 0, '"add" event passed correct `index`');
		});

		// Push: Should result in a "add" event
		list.push({
			name: 'Alexis'
		});

		// Splice: Should result in a "remove" event
		list.splice(0, 1);

		// Update: Should result in a "move" event
		list[0].attr('name', 'Zed');
	});

	test('Passing a comparator function to sort()', 1, function () {
		var list = new can.List([{
			priority: 4,
			name: 'low'
		}, {
			priority: 1,
			name: 'high'
		}, {
			priority: 2,
			name: 'middle'
		}, {
			priority: 3,
			name: 'mid'
		}]);
		list.sort(function (a, b) {
			// Sort functions always need to return the -1/0/1 integers
			if (a.priority < b.priority) {
				return -1;
			}
			return a.priority > b.priority ? 1 : 0;
		});
		equal(list[0].name, 'high');
	});

	test('Passing a comparator string to sort()', 1, function () {
		var list = new can.List([{
			priority: 4,
			name: 'low'
		}, {
			priority: 1,
			name: 'high'
		}, {
			priority: 2,
			name: 'middle'
		}, {
			priority: 3,
			name: 'mid'
		}]);
		list.sort('priority');
		equal(list[0].name, 'high');
	});

	test('Defining a comparator property', 1, function () {
		var list = new can.List([{
			priority: 4,
			name: 'low'
		}, {
			priority: 1,
			name: 'high'
		}, {
			priority: 2,
			name: 'middle'
		}, {
			priority: 3,
			name: 'mid'
		}]);
		list.attr('comparator','priority');
		equal(list[0].name, 'high');
	});

	test('Defining a comparator property that is a function of a can.Map', 4, function () {
		var list = new can.Map.List([
			new can.Map({
				text: 'Bbb',
				func: can.compute(function () {
					return 'bbb';
				})
			}),
			new can.Map({
				text: 'abb',
				func: can.compute(function () {
					return 'abb';
				})
			}),
			new can.Map({
				text: 'Aaa',
				func: can.compute(function () {
					return 'aaa';
				})
			}),
			new can.Map({
				text: 'baa',
				func: can.compute(function () {
					return 'baa';
				})
			})
		]);
		list.attr('comparator','func');

		equal(list.attr()[0].text, 'Aaa');
		equal(list.attr()[1].text, 'abb');
		equal(list.attr()[2].text, 'baa');
		equal(list.attr()[3].text, 'Bbb');
	});

	test('Sorts primitive items', function () {
		var list = new can.List(['z', 'y', 'x']);
		list.sort();

		equal(list[0], 'x', 'Moved string to correct index');
	});



	function renderedTests (templateEngine, helperType, renderer) {
		test('Insert pushed item at correct index with ' + templateEngine + ' using ' + helperType +' helper', function () {
			var el = document.createElement('div');

			var items = new can.List([{
				id: 'b'
			}]);
			items.attr('comparator', 'id');

			// Render the template and place inside the <div>
			el.appendChild(renderer({
				items: items
			}));

			var firstElText = el.getElementsByTagName('li')[0].innerText;

			/// Check that the template rendered an item
			equal(firstElText, 'b',
				'First LI is a "b"');

			// Add another item
			items.push({
				id: 'a'
			});

			// Get the text of the first <li> in the <div>
			firstElText = el.getElementsByTagName('li')[0].innerText;

			// Check that the template rendered that item at the correct index
			equal(firstElText, 'a',
				'An item pushed into the list is rendered at the correct position');

		});

		// TODO: Test that push and sort have the result in the same output

		test('Insert unshifted item at correct index with ' + templateEngine + ' using ' + helperType +' helper', function () {
			var el = document.createElement('div');

			var items = new can.List([
				{ id: 'a' },
				{ id: 'c' }
			]);
			items.attr('comparator', 'id');

			// Render the template and place inside the <div>
			el.appendChild(renderer({
				items: items
			}));

			var firstElText = el.getElementsByTagName('li')[0].innerText;

			/// Check that the template rendered an item
			equal(firstElText, 'a', 'First LI is a "a"');

			// Attempt to add an item to the beginning of the list
			items.unshift({
				id: 'b'
			});

			// Get the text of the first <li> in the <div>
			firstElText = el.getElementsByTagName('li')[1].innerText;

			// Check that the template rendered that item at the correct index
			equal(firstElText, 'b',
				'An item unshifted into the list is rendered at the correct position');

		});

		test('Insert spliced item at correct index with ' + templateEngine + ' using ' + helperType +' helper', function () {
			var el = document.createElement('div');

			var items = new can.List([
				{ id: 'b' },
				{ id: 'c' }
			]);
			items.attr('comparator','id');

			// Render the template and place inside the <div>
			el.appendChild(renderer({
				items: items
			}));

			var firstElText = el.getElementsByTagName('li')[0].innerText;

			// Check that the "b" is at the beginning of the list
			equal(firstElText, 'b',
				'First LI is a b');

			// Add a "1" to the middle of the list
			items.splice(1, 0, {
				id: 'a'
			});

			// Get the text of the first <li> in the <div>
			firstElText = el.getElementsByTagName('li')[0].innerText;

			// Check that the "a" was added to the beginning of the list despite
			// the splice
			equal(firstElText, 'a',
				'An item spliced into the list at the wrong position is rendered ' +
				'at the correct position');

		});

		// TODO: Test adding and removing items at the same time with .splice()

		test('Moves rendered item to correct index after "set" using ' + helperType +' helper', function () {
			var el = document.createElement('div');

			var items = new can.List([
				{ id: 'x' },
				{ id: 'y' },
				{ id: 'z' }
			]);
			items.attr('comparator', 'id');

			// Render the template and place inside the <div>
			el.appendChild(renderer({
				items: items
			}));

			var firstElText = el.getElementsByTagName('li')[0].innerText;

			// Check that the "x" is at the beginning of the list
			equal(firstElText, 'x', 'First LI is a "x"');

			// Change the ID of the last item so that it's sorted above the first item
			items.attr('2').attr('id', 'a');

			// Get the text of the first <li> in the <div>
			firstElText = el.getElementsByTagName('li')[0].innerText;

			// Check that the "a" was added to the beginning of the list despite
			// the splice
			equal(firstElText, 'a', 'The last item was moved to the first position ' +
				'after it\'s value was changed');

		});

		test('Move DOM items when list is sorted with  ' + templateEngine + ' using the ' + helperType +' helper', function () {
			var el = document.createElement('div');

			var items = new can.List([
				{ id: 4 },
				{ id: 1 },
				{ id: 6 },
				{ id: 3 },
				{ id: 2 },
				{ id: 8 },
				{ id: 0 },
				{ id: 5 },
				{ id: 6 },
				{ id: 9 },
			]);

			// Render the template and place inside the <div>
			el.appendChild(renderer({
				items: items
			}));

			var firstElText = el.getElementsByTagName('li')[0].innerText;

			// Check that the "4" is at the beginning of the list
			equal(firstElText, 4, 'First LI is a "4"');

			// Sort the list in-place
			items.attr('comparator' , 'id');

			firstElText = el.getElementsByTagName('li')[0].innerText;

			equal(firstElText, 0, 'The `0` was moved to beginning of the list' +
				'once sorted.');

		});

		/*test('Supress events during sort with ' + templateEngine + ' using the ' + helperType +' helper', function () {
			var el = document.createElement('div');

			var items = new can.List([
				{ id: 4 },
				{ id: 1 },
				{ id: 6 },
				{ id: 3 },
				{ id: 2 },
				{ id: 8 },
				{ id: 0 },
				{ id: 5 },
				{ id: 6 },
				{ id: 9 },
			]);

			// Render the template and place inside the <div>
			el.appendChild(renderer({
				items: items
			}));

			var firstElText = el.getElementsByTagName('li')[0].innerText;

			// Check that the "4" is at the beginning of the list
			equal(firstElText, 4, 'First LI is a "4"');

			// Sort the list in-place
			items.attr('comparator' , 'id');

			// Use the default comparator, but don't fire events
			items.sort(undefined, true);

			firstElText = el.getElementsByTagName('li')[0].innerText;

			// Check that the "4" is at the beginning of the list
			equal(firstElText, 4, 'The first LI has not changed as a result of the sort');

		});*/

		test('Push multiple items with ' + templateEngine + ' using the ' + helperType +' helper (#1509)', function () {
			var el = document.createElement('div');

			var items = new can.List();
			items.attr('comparator' , 'id');

			// Render the template and place inside the <div>
			el.appendChild(renderer({
				items: items
			}));

			items.bind('add', function (ev, items) {
				equal(items.length, 1, 'One single item was added');
			});

			items.push([
				{ id: 4 },
				{ id: 1 },
				{ id: 6 }
			]);

			var liLength = el.getElementsByTagName('li').length;

			equal(liLength, 3, 'The correct number of items have been rendered');

		});

	}

	var blockHelperTemplate = '<ul>{{#items}}<li>{{id}}</li>{{/items}}';
	var eachHelperTemplate = '<ul>{{#each items}}<li>{{id}}</li>{{/each}}';

	renderedTests('Mustache', '{{#block}}', can.mustache(blockHelperTemplate));
	renderedTests('Stache', '{{#block}}', can.stache(blockHelperTemplate));
	renderedTests('Mustache', '{{#each}}', can.mustache(eachHelperTemplate));
	renderedTests('Stache', '{{#each}}', can.stache(eachHelperTemplate));


	test('Sort primitive values without a comparator defined', function () {
		var list = new can.List([8,5,2,1,5,9,3,5]);
		list.sort();
		equal(list[0], 1, 'Sorted the list in ascending order');
	});

	test('Sort primitive values with a comparator function defined', function () {
		var list = new can.List([8,5,2,1,5,9,3,5]);
		list.attr('comparator' , function (a, b) {
			return a === b ? 0 : a < b ? 1 : -1;
		});
		equal(list[0], 9, 'Sorted the list in descending order');
	});

	test('The "destroyed" event bubbles on a sorted list', 2, function () {

		var list = new can.Model.List([
			new can.Model({ name: 'Joe' }),
			new can.Model({ name: 'Max' }),
			new can.Model({ name: 'Pim' })
		]);

		list.attr('comparator' , 'name');

		list.bind('destroyed', function (ev) {
			ok(true, '"destroyed" event triggered');
		});

		list.attr(0).destroy();

		equal(list.attr('length'), 2, 'item removed');
	});
	
	test("sorting works with #each (#1566)", function(){
		
		var heroes = new can.List([ { id: 1, name: 'Superman'}, { id: 2, name: 'Batman'} ]);
		
		heroes.attr('comparator', 'name');
		
		var template = can.stache("<ul>\n{{#each heroes}}\n<li>{{id}}-{{name}}</li>\n{{/each}}</ul>");
		
		var frag = template({
			heroes: heroes
		});
		
		var lis = frag.childNodes[0].getElementsByTagName("li");
		
		equal(lis[0].innerHTML, "2-Batman");
		equal(lis[1].innerHTML, "1-Superman");
		
		heroes.attr('comparator', 'id');
		
		equal(lis[0].innerHTML, "1-Superman");
		equal(lis[1].innerHTML, "2-Batman");
	});
	
	test("sorting works with comparator added after a binding", function(){
		var heroes = new can.List([ { id: 1, name: 'Superman'}, { id: 2, name: 'Batman'} ]);
		
		var template = can.stache("<ul>\n{{#each heroes}}\n<li>{{id}}-{{name}}</li>\n{{/each}}</ul>");
		
		var frag = template({
			heroes: heroes
		});
		
		heroes.attr('comparator', 'id');
		
		heroes.attr("0.id",3);
		
		var lis = frag.childNodes[0].getElementsByTagName("li");
		
		equal(lis[0].innerHTML, "2-Batman");
		equal(lis[1].innerHTML, "3-Superman");
		
	});
	
	test("removing comparator tears down bubbling", function(){
		
		var heroes = new can.List([ { id: 1, name: 'Superman'}, { id: 2, name: 'Batman'} ]);
		var lengthHandler = function(){};
		
		heroes.bind("length",lengthHandler);
		
		ok(!heroes[0]._bindings, "item has no bindings");
		
		heroes.attr('comparator', 'id');
		
		heroes.attr("0.id",3);
		
		ok(heroes._bindings, "list has bindings");
		ok(heroes[0]._bindings, "item has bindings");
		
		heroes.removeAttr('comparator');
		
		ok(!heroes[0]._bindings, "has bindings");
		ok(heroes._bindings, "list has bindings");
		
		heroes.unbind("length",lengthHandler);
		ok(!heroes._bindings, "list has no bindings");
	});

	test('sorting works when returning any negative value (#1601)', function() {
		var list = new can.List([1, 4, 2]);

		list.attr('comparator', function(a, b) {
			return a - b;
		});

		list.sort();
		deepEqual(list.attr(), [1, 2, 4]);
	});

	test('Batched events originating from sort plugin lack batchNum (#1707)', function () {
		var list = new can.List();
		list.attr('comparator', 'id');

		list.bind('length', function (ev) {
			ok(ev.batchNum, 'Has batchNum');
		});

		can.batch.start();
		list.push({ id: 'a' });
		list.push({ id: 'a' });
		list.push({ id: 'a' });
		can.batch.stop();
	});

	test('The sort plugin\'s _change handler ignores batched _changes (#1706)', function () {
		var list = new can.List();
		var _getRelativeInsertIndex = list._getRelativeInsertIndex;
		var sort = list.sort;
		list.attr('comparator', 'id');

		list.bind('move', function (ev) {
			ok(false, 'No "move" events should be fired');
		});
		list._getRelativeInsertIndex = function () {
			ok(false, 'No items should be evaluated independently');
			return _getRelativeInsertIndex.apply(this, arguments);
		};
		list.sort = function () {
			ok(true, 'Batching caused sort() to be called');
			return sort.apply(this, arguments);
		};

		can.batch.start();
		list.push({ id: 'c', index: 1 });
		list.push({ id: 'a', index: 2 });
		list.push({ id: 'a', index: 3 });
		can.batch.stop();

		equal(list.attr('2.id'), 'c', 'List was sorted');
	});

	test('Items aren\'t unecessarily swapped to the end of a list of equal items (#1705)', function () {
		var list = new can.List([
			{ id: 'a', index: 1 },
			{ id: 'b', index: 2 },
			{ id: 'c', index: 3 }
		]);
		list.attr('comparator', 'id');
		list.bind('move', function () {
			ok(false, 'No "move" events should be fired');
		});

		list.attr('0.id', 'b');
		equal(list.attr('0.index'), 1, 'Item hasn\'t moved');

		ok(true, '_getRelativeInsertIndex prevented an unecessary \'move\' event');
	});

	test('Items aren\'t unecessarily swapped to the beginning of a list of equal items (#1705)', function () {
		var list = new can.List([
			{ id: 'a', index: 1 },
			{ id: 'b', index: 2 },
			{ id: 'c', index: 3 }
		]);
		list.attr('comparator', 'id');
		list.bind('move', function () {
			ok(false, 'No "move" events should be fired');
		});

		list.attr('2.id', 'b');
		equal(list.attr('2.index'), 3, 'Item hasn\'t moved');

		ok(true, '_getRelativeInsertIndex prevented an unecessary \'move\' event');
	});

	test('Insert index is not evaluted for irrelevant changes', function () {
		var list = new can.List([
			{
				id: 'a',
				index: 1
			},
			{
				id: 'b',
				index: 2,
				child: {
					grandchild: {
						id: 'c',
						index: 3
					}
				}
			}
		]);

		// Setup
		var _getRelativeInsertIndex = list._getRelativeInsertIndex;

		list.bind('move', function (ev) {
			ok(false, 'A "move" events should be fired');
		});
		list._getRelativeInsertIndex = function () {
			ok(false, 'This item should not be evaluated independently');
			return _getRelativeInsertIndex.apply(this, arguments);
		};
		list.attr('comparator', 'id');

		// Start test
		list.attr('0.index', 4);
		list.attr('comparator', 'child.grandchild.id');
		list.attr('1.child.grandchild.index', 4);

		list._getRelativeInsertIndex = function () {
			ok(true, 'This item should be evaluated independently');
			return _getRelativeInsertIndex.apply(this, arguments);
		};

		list.attr('1.child', {
			grandchild: {
				id: 'c',
				index: 4
			}
		});

		equal(list.attr('0.id'), 'a', 'Item not moved');
	});

	test('_getInsertIndex moves items to the end', function () {
		var list = new can.List();
		var _getRelativeInsertIndex = list._getRelativeInsertIndex;
		var sort = list.sort;

		list.attr('comparator', 'id');

		list.bind('move', function (ev) {
			ok(false, 'No "move" events should be fired');
		});
		list._getRelativeInsertIndex = function () {
			ok(false, 'No items should be evaluated independently');
			return _getRelativeInsertIndex.apply(this, arguments);
		};
		list.sort = function () {
			ok(true, 'Batching caused sort() to be called');
			return sort.apply(this, arguments);
		};

		list.replace([
			{ id: 'a', index: 1 },
			{ id: 'a', index: 2 },
			{ id: 'a', index: 3 }
		]);

		equal(list.attr('0.index'), 3, 'Item positioned correctly');
		equal(list.attr('1.index'), 2, 'Item positioned correctly');
		equal(list.attr('2.index'), 1, 'Item positioned correctly');
	});


	test('_getInsertIndex chooses correct index', function () {
		var list = new can.List();
		var _getRelativeInsertIndex = list._getRelativeInsertIndex;
		var sort = list.sort;

		list.attr('comparator', 'id');

		list.bind('move', function (ev) {
			ok(false, 'No "move" events should be fired');
		});
		list._getRelativeInsertIndex = function () {
			ok(false, 'No items should be evaluated independently');
			return _getRelativeInsertIndex.apply(this, arguments);
		};
		list.sort = function () {
			ok(true, 'Batching caused sort() to be called');
			return sort.apply(this, arguments);
		};

		can.batch.start();
		list.replace([
			{ id: 'a', index: 1 },
			{ id: 'b', index: 2 },
			{ id: 'c', index: 3 }
		]);
		can.batch.stop();

		equal(list.attr('0.index'), 1, 'Item positioned correctly');
		equal(list.attr('1.index'), 2, 'Item positioned correctly');
		equal(list.attr('2.index'), 3, 'Item positioned correctly');
	});
});
