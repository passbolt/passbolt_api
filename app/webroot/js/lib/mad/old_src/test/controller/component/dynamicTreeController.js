steal(
	'funcunit'
).then(function () {

	// Test environnement, the window of the released popup
	var testEnv = null,
		treeController = null,
		map = null;

	var instanciateTree  = function (type) {
		var map = new testEnv.mad.object.Map({
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

		var $tree = testEnv.mad.helper.HtmlHelper.create(testEnv.mad.app.element, 'inside_replace', '<ul/>');
		var tree = new testEnv.mad.controller.component.DynamicTreeController($tree, {
			id: 'test_tree',
			itemClass: testEnv.demo.model.Person,
			map: map
		});
		tree.render();
		return tree;
	}

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

	test('DynamicTreeController.init', function () {
		var tree = instanciateTree();
		notEqual(tree, null, 'The instance is not null');
		equal(tree instanceof testEnv.mad.controller.component.DynamicTreeController, true, 'The instance is belonging to the right type');
	});

	test('DynamicTreeController: Test events (open & close)', function () {
		var tree = instanciateTree();
		testEnv.demo.model.Person.findAll().then(function (persons, response, request) {
			tree.load(persons);

			can.each(persons, function (person, i) {
				var hiddenItems = mad.model.Model.nestedListToList(person.children, 'children');

				// check that sub items are well hidden
				can.each(hiddenItems, function (hiddenItem, j) {
					ok(S('#' + hiddenItem.id).is(':hidden'), 'The sub item [' + hiddenItem.id + '] is hidden as expected');
				});

				// open each node and test that sub items are visible
				testEnv.$('#' + person.id + ' a.control:first').trigger('click');
				can.each(person.children, function (child, i) {
					ok(S('#' + child.id).is(':visible'), 'The sub item [' + child.id + '] is visible as expected');
				});

				// close each node and test that sub items are hidden
				testEnv.$('#' + person.id + ' a.control:first').trigger('click');
				can.each(person.children, function (child, i) {
					ok(S('#' + child.id).is(':hidden'), 'The sub item [' + child.id + '] is hidden as expected');
				});
			});
		});
	});

});