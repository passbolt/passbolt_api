steal('funcunit').then(function () {

	// Test environnement, the window of the released popup
	var testEnv = null,
		treeController = null,
		map = null;

	var instanciateGrid = function () {
			var map = new testEnv.mad.object.Map({
				'id': 'id',
				'name': 'name',
				'surname': 'surname',
				'freelancer': 'freelancer',
				'phone': 'phone',
				'email': 'email',
				'children': {
					'key': 'children',
					'func': mad.object.Map.mapObjects
				}
			});
			var columnModel = [{
				'name': 'name',
				'index': 'name',
				'label': 'name'
			}, {
				'name': 'surname',
				'index': 'surname',
				'label': 'surname'
			}, {
				'name': 'freelancer',
				'index': 'freelancer',
				'label': 'freelancer'
			}, {
				'name': 'phone',
				'index': 'phone',
				'label': 'phone'
			}, {
				'name': 'email',
				'index': 'email',
				'label': 'email'
			}, {
				'name': 'children',
				'index': 'children',
				'label': 'children',
				'valueAdapter': function (value, mappedItem, item, columnModel) {
					var returnValue = mappedItem.children.length;
					return returnValue;
				}
			}];

			var $grid = testEnv.mad.helper.HtmlHelper.create(testEnv.mad.app.element, 'inside_replace', '<table></table>');
			var grid = new testEnv.mad.controller.component.GridController($grid, {
				id: 'test_grid',
				itemClass: testEnv.demo.model.Person,
				map: map,
				columnModel: columnModel
			});
			grid.render();
			return grid;
		};

	module("mad.controller.component", {
		// runs before each test
		setup: function () {
			stop();
			var url = '//lib/mad/test/testEnv/app.html';
			//			var url = steal.idToUri('mad/test/testEnv/app.html').toString(); // sopen does not get full url, it needs relative url
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

	test('GridController.init', function () {
		var grid = instanciateGrid();
		notEqual(grid, null, 'The instance is not null');
		equal(grid instanceof testEnv.mad.controller.component.GridController, true, 'The instance is belonging to the right type');
	});

	// Test the insert item function
	test('GridController: Test insert item', function () {
		var grid = instanciateGrid();

		// Test that item class is well defined before inserting items
		grid.options.itemClass = null;
		// Try to insert a null item
		raises(function () {
			grid.insertItem(null);
		}, testEnv.mad.error.Exception, 'The associated itemClass can not be null');
		grid.setItemClass(testEnv.demo.model.Person);

		// Try to insert a null item
		raises(function () {
			grid.insertItem(null);
		}, testEnv.mad.error.WrongParametersException, 'Wrong parameter [item] expected type is [demo.model.Person]');

		// Try to insert an instance of a wrong type
		var wrongTypeIns = new testEnv.mad.model.Model({
			id: 'bad_id',
			'name': 'bad_name',
			'surname': 'bad_surname'
		});
		raises(function () {
			grid.insertItem(wrongTypeIns);
		}, testEnv.mad.error.WrongParametersException, 'Wrong parameter [item] expected type is [demo.model.Person]');

		// Insert an item an check it has been rendered
		testEnv.demo.model.Person.findAll().then(function (persons, response, request) {
			var item = persons[0];
			grid.insertItem(item);
			// check the item exists in the view
			S('#' + item.id).exists(1000, null, 'Item found in the view ' + item.surname);
		});

	});

	// Test remove function
	test('GridController: Test remove item', function () {
		var grid = instanciateGrid();

		testEnv.demo.model.Person.findAll().then(function (persons, response, request) {
			var item = persons[0];
			grid.insertItem(item);
			grid.removeItem(item);
			S('#' + item.id).missing(1000, null, 'Item not found in the view ' + item.surname);
		});
	});

	// load a list and test that all items are rendered
	test('GridController: Load list of items', function () {
		var grid = instanciateGrid();

		testEnv.demo.model.Person.findAll().then(function (persons, response, request) {
			grid.load(persons);
			// check that all items have been rendered
			can.each(persons, function (person, i) {
				S('#' + person.id).exists(1000, null, 'Item found in the view ' + person.surname);
			});
		});

	});

	// Test events on simple list
	test('GridController: Catch events (hover, click)', function () {
		var grid = instanciateGrid();

		// Insert an item an check it has been rendered
		testEnv.demo.model.Person.findAll().then(function (persons, response, request) {
			var hovered = 0,
				selected = 0,
				rightSelected = 0;
			grid.load(persons);

			can.each(persons, function (person, i) {
				// fuck in unit tests, the element is not released in the parameters
				// listen in item hovered in tree item
				grid.element.one('item_hovered', function ( /*el,*/ ev, item, srcEvent) {
					equal(item.id, person.id, 'The component is well catching the mouse hover event and the returned item is correct');
					hovered++;
				});
				// listen in item selected in tree item
				grid.element.one('item_selected', function ( /*el,*/ ev, item, srcEvent) {
					equal(item.id, person.id, 'The component is well catching the mouse click event and the returned item is correct');
					selected++;
				});
				//				// listen in item selected in tree item
				//				tree.element.one('item_right_selected', function (/*el,*/ ev, item, srcEvent) {
				//					equal(item.id, person.id, 'The component is well catching the mouse right click event and the returned item is correct');
				//					rightSelected++;
				//				});
				testEnv.$('#' + person.id).trigger('mouseenter');
				testEnv.$('#' + person.id).trigger('click');
				//				testEnv.$('#' + person.id + ' .label').trigger('contextmenu');
			});
			equal(hovered, persons.length, 'All the items have been hovered');
			equal(selected, persons.length, 'All the items have been clicked');
			//			equal(rightSelected, listPersons.length, 'All the items have been right clicked');
		});

	});

});