steal(
	'funcunit'
).then(function () {

	// Test environnement, the window of the released popup
	var testEnv = null;

	var instanciateMenu  = function (type) {
		var $menu = testEnv.mad.helper.HtmlHelper.create(testEnv.$('#mad_test_app_controller'), 'inside_replace', '<ul/>');
		var menu = new testEnv.mad.controller.component.MenuController($menu);
		menu.render();
		return menu;
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

	test('MenuController.init', function () {
		var menu = instanciateMenu();
		notEqual(menu, null, 'The instance is not null');
		equal(menu instanceof testEnv.mad.controller.component.MenuController, true, 'The instance is belonging to the right type');
	});

	test('MenuController', function () {
		testEnv.mad.setGlobal('clickedAction', null);
		var menu = instanciateMenu(),
			actions = testEnv.mad.model.Action.models([
				{ id: 'action_1', 'label': 'actions_1',
					action: function (menu) {
						testEnv.mad.setGlobal('clickedAction', 'action_1');
					}},
				{ id: 'action_2', 'label': 'actions_2',
					action: function (menu) {
						testEnv.mad.setGlobal('clickedAction', 'action_2');
					}},
				{ id: 'action_3', 'label': 'actions_3',
					action: function (menu) {
						testEnv.mad.setGlobal('clickedAction', 'action_3');
					}}
			]);
		menu.load(actions);
		can.each(actions, function (action, i) {
			testEnv.$('#' + action.id + ' .label').trigger('click');
			equal(testEnv.mad.getGlobal('clickedAction'), action.id, '');
		});
	});

});