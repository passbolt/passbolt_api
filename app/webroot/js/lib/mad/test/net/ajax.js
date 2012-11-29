// Thers is probably better tool in the jquery lib to work with ajax transaction
// queue ... check that
steal(
	'funcunit',
	'can/util/fixture'
).then( function () {

	module("mad.net", {
		// runs before each test
		setup: function () {
			var boot = new mad.bootstrap.AppBootstrap({
				'config': [
					'mad/test/testEnv/config_light.json'
				]
			});
		},
		// runs after each test
		teardown: function () {}
	});

	can.fixture({
		type: 'POST',
		url: '/ajax/request'
	}, function (original, settings, headers) {
		return {
			'header': {
				'id': uuid(),
				'status': mad.net.Response.STATUS_SUCCESS,
				'title': 'Ajax Unit Test fixture title',
				'message': 'Ajax Unit Test fixture message',
				'controller': 'controllerName',
				'action': 'actionName'
			}, 
			'body': 'RESULT REQUEST 1'
		};
	});

	can.fixture({
		type: 'POST',
		url: '/ajax/server_error'
	}, function (original, settings, headers) {
		return {
			'header': {
				'id': uuid(),
				'status': mad.net.Response.STATUS_ERROR,
				'title': 'Ajax Unit Test fixture title',
				'message': 'Ajax Unit Test fixture message',
				'controller': 'controllerName',
				'action': 'actionName'
			}, 
			'body': 'RESULT REQUEST 1'
		};
	});

	test('Ajax : Success request', function () {
		stop();
		mad.net.Ajax.request({
			'type': 'POST',
			'url': '/ajax/request',
			'async': false,
			'dataType': 'json'
		}).then(function (data, response, request) {
			ok(true, 'the request is a success');
			equal(data, 'RESULT REQUEST 1', 'expected body data');
			ok(response instanceof mad.net.Response, 'the response parameter is belonging to the right type');
			start();
		});
	});

	test('Ajax : Server not reachable', function () {
		stop();
		mad.net.Ajax.request({
			'type': 'POST',
			'url': '/ajax/not_reachable_biatch',
			'async': false,
			'dataType': 'json'
		}).then(function (data, response, request) {
			ok(false, 'the servor error is not well handled');
		}, function (jqXHR, status, response, request) {
			var unreachableResponse = mad.net.Response.getResponse('unreachable');
			ok(true, 'the url is unreachable');
			ok(response instanceof mad.net.Response, 'the response parameter is belonging to the right type');
			equal(response.getStatus(), mad.net.Response.STATUS_ERROR);
			equal(response.getTitle(), unreachableResponse.getTitle());
			equal(response.getAction(), unreachableResponse.getAction());
			equal(response.getController(), unreachableResponse.getController());
			equal(response.getData(), unreachableResponse.getData());
			start();
		});
	});

	test('Ajax : Server error', function () {
		stop();
		mad.net.Ajax.request({
			'type': 'POST',
			'url': '/ajax/server_error',
			'async': false,
			'dataType': 'json'
		}).then(function (data, response, request) {
			ok(false, 'the servor error is not well handled');
		}, function (jqXHR, status, response, request) {
			ok(true, 'the url server encountered an error');
			ok(response instanceof mad.net.Response, 'the response parameter is belonging to the right type');
			equal(response.getStatus(), mad.net.Response.STATUS_ERROR);
			start();
		});
	});

});