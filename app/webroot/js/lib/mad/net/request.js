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
        * Layer used on top of the ajax function of the jQuery library to setup request.
		* It deals with the response handler to treat server response.
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
				// the response handler class to use to treat the request result
				// override the request handler following the needs
				var ResponseHandlerClass = mad.getGlobal('RESPONSE_HANDLER_CLASS');
				
				// override the request data type to handle ajax response with our ajax response model
				// destroy this temporary model when operation is done
				var uid = uuid();
				var tmpRspClassName = 'mad.net.Response'+uid;
				mad.net.Response.extend(tmpRspClassName, {
					attributes:{
						header: 'mad.net.Header.model',
						body: dataType
					}
				}, {});
				request.dataType = 'json Response'+uid+'.model';
				
				// Override the ajax success callback
				request.success = function(response, isSuccess, obj){
					
					// Response well formed, delegate the treatment to the Response handler
					if(response.isValid()){
						var responseHandler = new ResponseHandlerClass(response, callbacks, request);
						responseHandler.handle();
					}
					
					// Invalid response, notify the system
					else {
						var badFormatResponse = new mad.net.Response({
							'header': new mad.net.Header({
								'id':			response.id,
								'status':		mad.net.Header.STATUS_ERROR,
								'title':		'Ajax Response not well formed',
								'message':		'The format of the ajax response is not well formed, check the documentation.',
								'contoller':	response.controller,
								'action':		response.action
							})
							, 'body': response
						});
						
						var responseHandler = new ResponseHandlerClass(badFormatResponse, callbacks, request);
						responseHandler.handle();
					}
					
					// clean the stack
					delete window[tmpRspClassName];
				};
				
				// Override the ajax error callback to treat : Not completed ajax request
				request.error = function(response) {
					
					var requestNotCompletedResponse = new mad.net.Response({
						'header': new mad.net.Header({
							'id':			mad.net.Header.RESPONSE_ID_UNDEFINED,
							'status':		mad.net.Header.STATUS_ERROR,
							'title':		response.status,
							'message':		'The request has not been completed ('+request.url+'). Check : controller url, data type ...',
							'contoller':	mad.net.Header.RESPONSE_CONTROLLER_UNDEFINED,
							'action':		mad.net.Header.RESPONSE_ACTION_UNDEFINED
						})
						, 'body': response
					});
					
					var responseHandler = new ResponseHandlerClass(requestNotCompletedResponse, callbacks, request);
					responseHandler.handle();
					
					// clean the stack
					delete window[tmpRspClassName];
				};
				
				return request;
			}
		},
			
		/** @prototype */
		{  }
	);
        
});
