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
				// The constructor
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
					var message = 'request : '+this.request.url+' / method :'+this.request.type+' / server message  :'+this.response.message;
					var data = this.response.content && this.response.content.response ? this.response.content.response : {};
					mad.getGlobal('ERROR_HANDLER_CLASS').handleError(this.response.status, this.response.title, message, data);
				},
				
				/**
				 * Handle success response
				 * @return {void}
				 */
				'success': function()
				{
					// Log the request result into the console
					var message = 'status : '+this.response.status+' / request : '+this.request.url+' / method :'+this.request.type+' / server message  :'+this.response.message;
					steal.dev.log(message);
					// callback if defined
					if(this.callbacks.success){
						this.callbacks.success(this.response.content, this);
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
