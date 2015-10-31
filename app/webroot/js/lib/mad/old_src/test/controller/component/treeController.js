steal(
	'funcunit'
).then(function () {

	module("mad.controller.component", {
		// runs before each test
		setup: function () {
			stop();
			var url = 'lib/mad/test/testEnv/app.html';
			S.open(url, function () {
				// store the env windows in a global var for the following unit tests
				testEnv = S.win;
				S('body').hasClass('mad_test_app_ready', true, function () {
					start();
				});
			});
		},
		// runs after each test
		teardown: function () {}
	});

	// Test environnement, the window of the released popup
	var testEnv = null,
		treeController = null,
		map = null;

	var instanciateTree  = function (type) {
		type = type || 'list';
		var map = null;
		switch (type) {
		case 'list':
			map = new testEnv.mad.object.Map({
				'id': 'id',
				'label': {
					'key': 'name',
					'func': function (value, map, rowObject) {
						return rowObject.name + ' ' + rowObject.surname;
					}
				}
			});
			break;
		case 'tree':
			map = new testEnv.mad.object.Map({
				'id': 'id',
				'label': {
					'key': 'name',
					'func': function (value, map, rowObject) {
						return rowObject.name + ' ' + rowObject.surname;
					}
				},
				'children': {
					'key': 'children',
					'func': mad.object.Map.mapObjects
				}
			});
			break;
		}

		var $tree = testEnv.mad.helper.HtmlHelper.create(testEnv.mad.app.element, 'inside_replace', '<ul/>');
		var tree = new testEnv.mad.controller.component.TreeController($tree, {
			id: 'test_tree',
			itemClass: testEnv.demo.model.Person,
			map: map
		});
		tree.render();
		return tree;
	};

	test('TreeController.init', function () {
		var tree = instanciateTree();
		notEqual(tree, null, 'The instance is not null');
		equal(tree instanceof testEnv.mad.controller.component.TreeController, true, 'The instance is belonging to the right type');
	});

	// Test the insert item function
	test('TreeController: Test insert item', function () {
		var tree = instanciateTree('tree');

		// Test that item class is well defined before inserting items
		tree.options.itemClass = null;
		// Try to insert a null item
		raises(function () {
			tree.insertItem(null);
		}, testEnv.mad.error.Exception, 'The associated itemClass can not be null');
		tree.setItemClass(testEnv.demo.model.Person);

		// Try to insert a null item
		raises(function () {
			tree.insertItem(null);
		}, testEnv.mad.error.WrongParametersException, 'Wrong parameter [item] expected type is [demo.model.Person]');

		// Try to insert an instance of a wrong type
		var wrongTypeIns = new testEnv.mad.model.Model({id: 'bad_id', 'name': 'bad_name', 'surname': 'bad_surname'});
		raises(function () {
			tree.insertItem(wrongTypeIns);
		}, testEnv.mad.error.WrongParametersException, 'Wrong parameter [item] expected type is [demo.model.Person]');

		// Insert an item an check it has been rendered
		testEnv.demo.model.Person.findAll().then(function (persons, response, request) {
			var item = persons[0];
			tree.insertItem(item);
			// check the item exists in the view
			S('#' + item.id).exists(1000, null, 'Item found in the view ' + item.surname);
		});

	});

	// Test remove function
	test('TreeController: Test remove item', function () {
		var tree = instanciateTree('list');

		testEnv.demo.model.Person.findAll().then(function (persons, response, request) {
			var item = persons[0];
			tree.insertItem(item);
			tree.removeItem(item);
			S('#' + item.id).missing(1000, null, 'Item not found in the view ' + item.surname);
		});
	});

	// load a list and test that all items are rendered
	test('TreeController: Load simple list data', function () {
		var tree = instanciateTree();

		testEnv.demo.model.Person.findAll().then(function (persons, response, request) {
			tree.load(persons);
			// check that all items have been rendered
			can.each(persons, function (person, i) {
				S('#' + person.id).exists(1000, null, 'Item found in the view ' + person.surname);
			});
		});

	});

	// load a tree and test that all items are rendered
	test('TreeController: Load tree data', function () {
		var tree = instanciateTree('tree');

		testEnv.demo.model.Person.findAll().then(function (persons, response, request) {
			tree.load(persons);
			var listPersons = testEnv.mad.model.Model.nestedListToList(persons, 'children');
			// check that all items have been rendered
			can.each(listPersons, function (person, i) {
				S('#' + person.id).exists(1000, null, 'Item found in the view ' + person.surname);
			});
		});

	});

	// Test events on simple list
	test('TreeController: Catch events (hover, click)', function () {
		var tree = instanciateTree('tree');

		// Insert an item an check it has been rendered
		testEnv.demo.model.Person.findAll().then(function (persons, response, request) {
			var listPersons = mad.model.Model.nestedListToList(persons, 'children'),
				hovered  = 0,
				selected  = 0,
				rightSelected = 0;
			tree.load(persons);

			can.each(listPersons, function (person, i) {
				// fuck in unit tests, the element is not released in the parameters
				// listen in item hovered in tree item
				tree.element.one('item_hovered', function (/*el,*/ ev, item, srcEvent) {
					equal(item.id, person.id, 'The component is well catching the mouse hover event and the returned item is correct');
					hovered++;
				});
				// listen in item selected in tree item
				tree.element.one('item_selected', function (/*el,*/ ev, item, srcEvent) {
					equal(item.id, person.id, 'The component is well catching the mouse click event and the returned item is correct');
					selected++;
				});
//				// listen in item selected in tree item
//				tree.element.one('item_right_selected', function (/*el,*/ ev, item, srcEvent) {
//					equal(item.id, person.id, 'The component is well catching the mouse right click event and the returned item is correct');
//					rightSelected++;
//				});

				testEnv.$('#' + person.id + ' .label').trigger('mouseenter');
				testEnv.$('#' + person.id + ' .label').trigger('click');
//				testEnv.$('#' + person.id + ' .label').trigger('contextmenu');
			});
			equal(hovered, listPersons.length, 'All the items have been hovered');
			equal(selected, listPersons.length, 'All the items have been clicked');
//			equal(rightSelected, listPersons.length, 'All the items have been right clicked');
		});

	});

});