steal( 
	'jquery/class'
	)
.then( 
	function($){
        
		/*
        * @class mad.net.ResponseHandler
		* 
		* @inherits {mad.core.Class}
        * @parent index
		* 
        * The common ajax response handler
		* 
        * @constructor
        * Creates a new ajax response handler
		* @param {mad.net.Response} response The server response
		* @param {array} callbacks The optional callbacks passed of the orginial request (success, error)
		* @param {request} request The request (the original callbacks have been removed and placed in the callbacks array)
        * @return {mad.net.ResponseHandler}
        */
		$.Class('mad.net.ResponseHandler', 
			/** @static */
			{ },
			
			/** @prototype */
			{ 
				// Constructor like
				'init': function(response, callbacks, request)
				{
					this.response = response;
					this.request = request;
					this.callbacks = callbacks;
				},
				
				/**
				 * Notify the error handler
				 * @return {void}
				 */
				'notifyErrorHandler': function()
				{
					var message = 'request : '+this.request.url+' / method :'+this.request.type+' / server message  :'+this.response.header.message;
					var data = this.response.content && this.response.body.content.response ? this.response.body.content.response : {};
					mad.getGlobal('ERROR_HANDLER_CLASS').handleError(this.response.header.status, this.response.header.title, message, data);
				},
				
				/**
				 * Handle the response
				 * @return {void}
				 */
				'handle': function()
				{					
					// Dispatch the response function of the response status
					switch(this.response.header.status){
						case mad.net.Header.STATUS_ERROR:
							this.error();
							break;
						case mad.net.Header.STATUS_NOTICE:
							this.notice();
							break;
						case mad.net.Header.STATUS_SUCCESS:
							this.success();
							break;
						case mad.net.Header.STATUS_WARNING:
							this.warning();
							break;
					}	
				},
				
				/**
				 * Handle success response
				 * @return {void}
				 */
				'success': function()
				{
					// Log the request result into the console
					var message = 'status : '+this.response.header.status+' / request : '+this.request.url+' / method :'+this.request.type+' / server message  :'+this.response.header.message;
					steal.dev.log(message);
					// callback if defined
					if(this.callbacks.success){
						this.callbacks.success(this.request, this.response, this.response.body);
					}
				},
				
				/**
				 * Handle error response
				 * @return {void}
				 */
				'error': function(){
					this.notifyErrorHandler();
					// callback if defined
					if(this.callbacks.error){
						this.callbacks.error(this);
					}
				},
				
				/**
				 * Handle notice response
				 * @return {void}
				 */
				'notice': function()
				{
					this.notifyErrorHandler();
					// callback if defined
					if(this.callbacks.notice){
						this.callbacks.notice(this);
					}
				},
				
				/**
				 * Handle warning response
				 * @return {void}
				 */
				'warning': function()
				{
					this.notifyErrorHandler();
					// callback if defined
					if(this.callbacks.warning){
						this.callbacks.warning(this);
					}
				}
			}
		);
        
	}
);
