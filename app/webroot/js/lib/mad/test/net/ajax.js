// Thers is probably better tool in the jquery lib to work with ajax transaction
// queue ... check that
steal('funcunit', function () {

	module("mad.net", {
		// runs before each test
		setup: function () {
			mad.controller.AppController.destroy();
			mad.controller.AppController.setNs(APP_NS_ID);
			mad.setGlobal('RESPONSE_HANDLER_CLASS', mad.net.ResponseHandler);
		},
		// runs after each test
		teardown: function () {
			mad.controller.AppController.destroy();
		}
	});

	// Requests result
	var requestResult = {};
	requestResult[APP_URL + '/ajax/request1'] = 'RESULT REQUEST 1';
//	requestResult[APP_URL + '/ajax/request2'] = 'REQ 2';

	//
	$.fixture({
		type: 'post',
		url: APP_URL + '/ajax/request1'
	}, function (original, settings, headers) {
		return {
			'header': new mad.net.Header({
				'id': uuid(),
				'status': mad.net.Header.STATUS_SUCCESS,
				'title': 'Ajax Unit Test fixture',
				'message': 'Ajax Unit Test fixture',
				'controller': 'AjaxUnitTestController',
				'action': 'request1'
			}), 
			'body': requestResult[APP_URL + '/ajax/request1']
		};
	});

//	//
//	$.fixture({
//		type: 'post',
//		url: APP_URL + '/ajax/requests'
//	}, function (transaction) {
//		var returnValue = {
//			transactionId: transaction.data.transactionId,
//			requests: {}
//		};
//
//		for (var rId in transaction.data.requests) {
//			var request = transaction.data.requests[rId];
//			returnValue.requests[request.id] = {
//				'id': request.id,
//				'data': requestResult[request.url]
//			};
//
//		}
//
//		return returnValue;
//	});

	test('Ajax : Simple request', function () {
		stop();
		mad.net.Ajax.singleton().request({
			'type': mad.net.Request.METHOD_POST,
			'url': APP_URL + '/ajax/request1',
			'async': false,
			'dataType': 'json',
			'success': function (request, response, data) {
				equal(data, 'RESULT REQUEST 1');
				start();
			}
		});
	});
	
//	test('Ajax : Aggregated requests', function () {
//		stop();
//		mad.net.Ajax.singleton().request({
//			'type': 'post',
//			'url': APP_URL + '/ajax/request1',
//			'async': true,
//			'dataType': 'json',
//			'success': function (DATA) {
//				equal(DATA, 'REQ 1');
//				start();
//			}
//		}, true);
//		mad.net.Ajax.singleton().request({
//			'type': 'post',
//			'url': APP_URL + '/ajax/request2',
//			'async': true,
//			'dataType': 'json',
//			'success': function (DATA) {
//				equal(DATA, 'REQ 2');
//				start();
//			}
//		}, true);
//	});

});