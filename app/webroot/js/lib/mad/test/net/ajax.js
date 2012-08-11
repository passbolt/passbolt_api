// Thers is probably better tool in the jquery lib to work with ajax transaction
// queue ... check that
steal('funcunit', function () {

	module("mad.net", {
		// runs before each test
		setup: function () {},
		// runs after each test
		teardown: function () {}
	});

	// Requests result
	var requestResult = {};
	requestResult[APP_URL + '/ajax/request1'] = 'REQ 1';
	requestResult[APP_URL + '/ajax/request2'] = 'REQ 2';

	//
	$.fixture({
		type: 'post',
		url: APP_URL + '/ajax/request1'
	}, function () {
		return requestResult[APP_URL + '/ajax/request1'];
	});

	//
	$.fixture({
		type: 'post',
		url: APP_URL + '/ajax/requests'
	}, function (transaction) {
		var returnValue = {
			transactionId: transaction.data.transactionId,
			requests: {}
		};

		for (var rId in transaction.data.requests) {
			var request = transaction.data.requests[rId];
			returnValue.requests[request.id] = {
				'id': request.id,
				'data': requestResult[request.url]
			};

		}

		return returnValue;
	});

	test('Ajax : Simple request', function () {
		stop();
		mad.net.Ajax.singleton().request({
			'type': 'post',
			'url': APP_URL + '/ajax/request1',
			'async': false,
			'dataType': 'json',
			'success': function (DATA) {
				equal(DATA, 'REQ 1');
				start();
			}
		});
	});

	test('Ajax : Simple request', function () {
		stop();
		mad.net.Ajax.singleton().request({
			'type': 'post',
			'url': APP_URL + '/ajax/request1',
			'async': true,
			'dataType': 'json',
			'success': function (DATA) {
				equal(DATA, 'REQ 1');
				start();
			}
		}, true);
		mad.net.Ajax.singleton().request({
			'type': 'post',
			'url': APP_URL + '/ajax/request2',
			'async': true,
			'dataType': 'json',
			'success': function (DATA) {
				equal(DATA, 'REQ 2');
				start();
			}
		}, true);
	});

});