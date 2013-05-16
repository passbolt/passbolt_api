steal(
	'funcunit',
	'can/util/fixture'
).then(function () {

	module("mad.lang", {
		// runs before each test
		setup: function () {
			mad.test.testing.initEnv('//lib/mad/test/testEnv/app.html', 'mad/test/testEnv/config_light.json');
			// var boot = new mad.bootstrap.AppBootstrap({
				// 'config': [
					// 'mad/test/testEnv/config_light.json'
				// ]
			// });
		},
		// runs after each test
		teardown: function () {}
	});

	// Sample of dictionnary
	var dico = {
		'my sentence without hook': 'ma phrase sans hook',
		'my sentence with a final hook %s': 'ma phrase avec un hook final %s',
		'%s my sentence with a start hook': '%s ma phrase avec un hook en debut',
		'%s my sentence with a start and a final hooks %s': '%s ma phrase avec un hook en debut et en fin %s',
		'%s': '%s'
	};

	// Fixture to access to the dictionnary
	// not required but good to know how to use it
	can.fixture({
		type: 'get',
		url: '/dictionaries/fr-FR.json'
	}, function (original, settings, headers) {
		return {
			'header': {
				'id': uuid(),
				'status': mad.net.Response.STATUS_SUCCESS,
				'title': 'I18m Unit Test fixture',
				'message': 'I18m Unit Test fixture',
				'controller': 'I18mUnitTestController',
				'action': 'view'
			},
			'body': dico
		};
	});

	test('I18n : Load dictionnary', function () {
		stop();
		S.win.mad.net.Ajax.request({
			'type': 'get',
			'url': '/dictionaries/fr-FR.json',
			'async': false,
			'dataType': 'json'
		}).then(function (data, response, request) {
			var i18n = S.win.mad.lang.I18n.singleton();
			i18n.loadDico(data);
			for (var key in data) {
				equal(dico[key], i18n.dico[key]);
				start();
			}
		});
	});

	// test('I18n : translate', function () {
		// stop();
		// S.win.mad.net.Ajax.request({
			// 'type': 'get',
			// 'url': '/dictionaries/fr-FR.json',
			// 'async': false,
			// 'dataType': 'json'
		// }).then(function (data, response, request) {
			// var i18n = S.win.mad.lang.I18n.singleton();
			// S.win.i18n.loadDico(data);
// 
			// equal(i18n.translate('my sentence without hook'), 'ma phrase sans hook');
			// equal(__('my sentence without hook'), 'ma phrase sans hook');
			// equal(i18n.translate('my sentence with a final hook %s', ['HOOK_FINAL']), 'ma phrase avec un hook final HOOK_FINAL');
			// equal(__('my sentence with a final hook %s', 'HOOK_FINAL'), 'ma phrase avec un hook final HOOK_FINAL');
			// equal(i18n.translate('%s my sentence with a start hook', ['HOOK_START']), 'HOOK_START ma phrase avec un hook en debut');
			// equal(__('%s my sentence with a start hook', 'HOOK_START'), 'HOOK_START ma phrase avec un hook en debut');
			// equal(i18n.translate('%s my sentence with a start and a final hooks %s', ['HOOK_START', 'HOOK_FINAL']), 'HOOK_START ma phrase avec un hook en debut et en fin HOOK_FINAL');
			// equal(__('%s my sentence with a start and a final hooks %s', 'HOOK_START', 'HOOK_FINAL'), 'HOOK_START ma phrase avec un hook en debut et en fin HOOK_FINAL');
			// equal(i18n.translate('%s', ['HOOK']), 'HOOK');
			// equal(__('%s', 'HOOK'), 'HOOK');
			// equal(i18n.translate('%s%s%s%s', ['HOOK1', 'HOOK2', 'HOOK3', 'HOOK4']), 'HOOK1HOOK2HOOK3HOOK4');
			// equal(__('%s%s%s%s', 'HOOK1', 'HOOK2', 'HOOK3', 'HOOK4'), 'HOOK1HOOK2HOOK3HOOK4');
			// start();
		// });
	// });
// 
	// test('I18n : Not as many variables as they are hooks', function () {
		// stop();
		// S.win.mad.net.Ajax.request({
			// 'type': 'get',
			// 'url': '/dictionaries/fr-FR.json',
			// 'async': false,
			// 'dataType': 'json'
		// }).then(function (data, response, request) {
			// var i18n = S.win.mad.lang.I18n.singleton();
			// i18n.loadDico(data);
// 
			// //no hook, variables given
			// raises(function () {
				// i18n.translate('my sentence without hook', ['HOOK']);
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// raises(function () {
				// __('my sentence without hook', 'HOOK');
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// //hook, no variables given
			// raises(function () {
				// i18n.translate('my sentence with a final hook %s', []);
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// raises(function () {
				// __('my sentence with a final hook %s');
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// //hook, not enough variables
			// raises(function () {
				// i18n.translate('%s my sentence with a final hook %s', ['HOOK_START']);
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// raises(function () {
				// __('%s my sentence with a final hook %s', 'HOOK_START');
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// //hook, too much variables
			// raises(function () {
				// i18n.translate('%s my sentence with a final hook %s', ['HOOK_START', 'HOOK_END', 'BILOUTE']);
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// raises(function () {
				// __('%s my sentence with a final hook %s', 'HOOK_START', 'HOOK_END', 'BILOUTE');
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// start();
		// });
// 
	// });
// 
	// test('I18n : Allowed scalar variables', function () {
		// stop();
		// mad.net.Ajax.request({
			// 'type': 'get',
			// 'url': '/dictionaries/fr-FR.json',
			// 'async': false,
			// 'dataType': 'json'
		// }).then(function (data, response, request) {
			// var i18n = mad.lang.I18n.singleton();
			// i18n.loadDico(data);
// 
			// equal(i18n.translate('%s', [1]), '1');
			// equal(__('%s', 1), '1');
// 
			// equal(i18n.translate('%s', [1.5]), '1.5');
			// equal(__('%s', 1.5), '1.5');
// 
			// equal(i18n.translate('%s', [true]), 'true');
			// equal(__('%s', true), 'true');
// 
			// start();
		// });
// 
	// });
// 
	// test('I18n : Wrong variables type', function () {
		// stop();
		// mad.net.Ajax.request({
			// 'type': 'get',
			// 'url': '/dictionaries/fr-FR.json',
			// 'async': false,
			// 'dataType': 'json'
		// }).then(function (data, response, request) {
			// var i18n = mad.lang.I18n.singleton();
			// i18n.loadDico(data);
// 
			// //object not allowed
			// raises(function () {
				// i18n.translate('%s', [new Object()]);
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// raises(function () {
				// __('%s', new Object());
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// //array not allowed
			// raises(function () {
				// i18n.translate('%s', [
					// ['A', 'B']
				// ]);
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// raises(function () {
				// __('%s', ['A', 'B']);
			// }, mad.error.WrongParametersException, mad.error.WrongParametersException.message);
// 
			// start();
		// });
// 
	// });

});