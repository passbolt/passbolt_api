steal(
	'can/construct',
	'mad/net/response.js',
	'mad/net/responseHandler.js'
).then(function () {

	/*
	* @class mad.net.Ajax
	* @inherits can.Construct
	* @see mad.net.Response
	* @see mad.net.ResponseHandler
	* @parent mad.net
	* 
	* ## Simple Request Example
	*	
	*	@codestart
mad.net.Ajax.request({
	'type': mad.net.Request.METHOD_POST,
	'url': APP_URL + '/resources/viewByCategory',
	'async': false,
	'dataType': 'passbolt.model.Resource.models',
	'success': function (request, response, body) {
		...
	},
	'error': function (request, response) {
		...
	}
});
	*	@codeend
	*	
	* *dataType* The dataType options allow you to define the format of the server result.
	* It gets the standart jQuery ajax setting option : xml, html, script, json, jsonp, test. 
	* Or a javascriptMVC model reference. This model will be used to map  the server result to 
	* a ready to use object by the client application. Here we are expecting an array of Resources.
	*	
	* *success* The success callback function gets the following parameters
	* 
	* * request (<a href="#!mad.net.Request">mad.net.Request</a>) : The original request setting
	* * response (<a href="#!mad.net.Response">mad.net.Response</a>) : The server answer
	* * body (<a href="#!mad.net.Response.body">mad.net.Response.body</a>) : The server body answer
	*
	* *error* The error callback functions gets the following parameters
	* 
	* * request (<a href="#!mad.net.Request">mad.net.Request</a>) : The original request setting
	* * response (<a href="#!mad.net.Response">mad.net.Response</a>) : The server answer
	* 
	*/
	can.Construct('mad.net.Ajax', /** @static */ {
		/**
		 * Perform an ajax request
		 * @param {Array} request The ajax request settings (almost similar to the 
		 * jQuery ajax function)
		 * @return {jQuery.deferred}
		 */
		'request': function (request) {
			var ResponseHandlerClass = mad.Config.read('net.ResponseHandlerClass');
			// Duplicate and store the original params in a variable
			request.originParams = $.extend({}, request.params);
			// Treat templated uri (like /controller/action/{id}
			request.url = $.String.sub(request.url, request.params, true);
			// By default we expect json data
			request.dataType = request.dataType || 'json';
			// Add the params left to the request
			request.data = request.params;
			// Format request attribute.
			request.type = request.type ? request.type.toUpperCase() : 'GET';

			// The request will not display a loading feedback, default true.
			if (typeof request.silentLoading == 'undefined') {
				request.silentLoading = true;
				if (request.type == 'POST' || request.type == 'DELETE' || request.type == 'PUTw') {
					request.silentLoading = false;
				}
			}

			// Propagate an event on the bus to inform other components.
			mad.bus.trigger('passbolt_ajax_request_start', [request]);

			// make the ajax request
			var returnValue = can.ajax(request)
				// pipe it to intercept server before any other treatments
				.pipe(

					// the request has been performed sucessfully
					function (data, textStatus, jqXHR) {
						var response = new mad.net.Response(data),
							// the deferred to return
							deferred = null;

						// @todo check the response format is valid

						// the server returns an error
						if (response.getStatus() == mad.net.Response.STATUS_ERROR) {
							deferred = $.Deferred();
							deferred.rejectWith(this, [jqXHR, 'error', response]);
							return deferred;
						}
						// @todo treat notice, warning & success

						// everything fine, continue
						// override the deferred to pass the desired data
						// findOne, findAll get this deffered, but create seems to have
						// its own, it return only the bulk server response (treat it there)
						deferred = $.Deferred();
						deferred.resolveWith(this, [data.body, response, request]);
						return deferred;
					},

					// the request has not been performed
					function (jqXHR, textStatus, data) {
						var jsonData = null;
						var response = null;
						// In case of error the reponse is not automatically parsed.
						// Try to parse it, in case the server return an understable message.
						try{
							if(typeof jqXHR.responseText != undefined) {
								jsonData = $.parseJSON(jqXHR.responseText);
							}
						} catch(e) {}

						// In case we've been able to parse the server answer.
						if (jsonData != null && mad.net.Response.isResponse(jsonData)) {
							response = new mad.net.Response(jsonData);
						}
						// Otherwise treat a default unreacheable server answer.
						else {
							response = mad.net.Response.getResponse('unreachable');
						}

						var deferred = $.Deferred();
						deferred.rejectWith(this, [jqXHR, response.getStatus(), response, request]);
						return deferred;
					}

				); // end of pipe

			// Handle the server success response with the default response handler
			returnValue.then(function (data, response, request) {
				// Propagate an event on the bus to inform other components.
				mad.bus.trigger('passbolt_ajax_request_complete', [request]);
				var responseHandler = new ResponseHandlerClass(response, request);
				responseHandler.handle();
			});

			// Handle the server fail response with the default response handler
			returnValue.fail(function (jqXHR, textStatus, response) {
				// Propagate an event on the bus to inform other components.
				mad.bus.trigger('passbolt_ajax_request_complete', [request]);
				var responseHandler = new ResponseHandlerClass(response, request);
				responseHandler.handle();
			});

			return returnValue;
		}
	}, /** @prototype */ { });

});