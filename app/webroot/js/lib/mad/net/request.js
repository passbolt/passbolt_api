steal( 
	'jquery/class',
	MAD_ROOT+'/net/response.js'
)
.then( 
	function($){
        
		/*
        * @class mad.net.Request
		* 
		* @inherits {mad.core.Class}
        * @parent index
		* 
        * Layer used on top of the ajax function of the jQuery library to setup request
		* and give us more tools to manipulate request status (error, success, notice, warning)
        */
		$.Class('mad.net.Request', 
		/** @static */
		{
			/**
			 * Constant to symbolise the the GET method
			 * @type {string}
			 */
			'METHOD_GET':'GET',
			/**
			 * Constant to symbolise the the DELETE method
			 * @type {string}
			 */
			'METHOD_DELETE':'DELETE',
			/**
			 * Constant to symbolise the the POST method
			 * @type {string}
			 */
			'METHOD_POST':'POST',
			/**
			 * Constant to symbolise the the PUT method
			 * @type {string}
			 */
			'METHOD_PUT':'PUT',
			
			/**
			 * Setup the request
			 */
			'setupRequest': function(request, responseHandler)
			{
				var callbacks = {
					'success': request.success || null,
					'error': request.error || null
				};
				var dataType = request.dataType;
				// the response handler class to use to propagate the request results
				var ResponseHandlerClass = mad.getGlobal('RESPONSE_HANDLER_CLASS');
				
				// override the request data type to handle ajax response with our ajax response model
				var uid = uuid();
				mad.net.Response.extend('mad.net.Response'+uid, {
					attributes:{
						content: dataType
					}
				}, {});
				request.dataType = 'json Response'+uid+'.model';
				
				// override the ajax request success function to handle server answers
				request.success = function(response, isSuccess, obj){
					
					// The response is well formated
					if(response.isValid()){
						var responseHandler = new ResponseHandlerClass(response, callbacks, request);
						// Dispatch the response function of the response status
						switch(response.status){
							case 'error':
								responseHandler.error();
								break;
							case 'notice':
								responseHandler.notice();
								break;
							case 'success':
								responseHandler.success();
								break;
							case 'warning':
								responseHandler.warning();
								break;
						}
					}
					// The response is not well formed
					else{
						// Create a not well formed response and call the error function of the response handler
						var badFormatResponse = new mad.net.Response({
							'id':			response.id,
							'status':		mad.net.Response.STATUS_ERROR,
							'title':		'Ajax Response not well formed',
							'message':		'The format of the ajax response is not well formed, check the documentation.',
							'contoller':	response.controller,
							'action':		response.action,
							'data':			{
								'response':response
							}
						});
						var responseHandler = new ResponseHandlerClass(badFormatResponse, callbacks, request);
						responseHandler.error();
					}
					delete window['mad.net.Response'+uid];
				};
				
				// override the ajax request error function to handle client ajax error
				request.error = function(response){
					// Create a not completed request response and call the error function of the response handler
					var requestNotCompletedResponse = new mad.net.Response({
						'id':			mad.net.Response.RESPONSE_ID_UNDEFINED,
						'status':		mad.net.Response.STATUS_ERROR,
						'title':		response.status,
						'message':		'The request has not been completed ('+request.url+'). Check : controller url, data type ...',
						'contoller':	mad.net.Response.RESPONSE_CONTROLLER_UNDEFINED,
						'action':		mad.net.Response.RESPONSE_ACTION_UNDEFINED,
						'data':			{
							'response':response
						}
					});
					var responseHandler = new ResponseHandlerClass(requestNotCompletedResponse, callbacks, request);
					responseHandler.error();
					delete window['mad.net.Response'+uid];
				};
				
				return request;
			}
		},
			
		/** @prototype */
		{  }
	);
        
});
