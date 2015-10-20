steal(
	'funcunit',
	'app/controller/component/resourceActionsTabController.js'
).then(function () {

	module("mad.controller.component", {
		// runs before each test
		setup: function () {
			mad.test.testing.initEnv('//app/test/testEnv/app.html' );
		},
		// runs after each test
		teardown: function () {}
	});

	test('MenuController.init', function () {
		var $elt = S.win.mad.helper.HtmlHelper.create(S.win.mad.app.element, 'inside_replace', '<div/>');
		var menu = new S.win.passbolt.controller.component.ResourceActionsTabController($elt);
		// menu.render();
		// return menu;
	});

});