steal( 
	'jquery/class'
	)
.then( function ($) {

	/*
	 * @class passbolt.helper.ResponseHandler
	 * @inherits {mad.net.ResponseHandler}
	 * @parent index
	 * 
	 * Our ajax response handler. It will handle each ajax response on passbolt.
	 * 
	 * @constructor
	 * Create a new response handler
	 * @return {passbolt.helper.ResponseHandler}
	 */
	mad.net.ResponseHandler.extend('passbolt.helper.ResponseHandler',
	/** @static */
	{},

	/** @prototype */
	{
		/**
		 * Handle success response
		 * @return {void}
		 */
		'success': function () {
			this._super();
			// send a notification on the events' bus
			var message = 'request : ' + this.request.url + ' / method :' + this.request.type + ' / server message  :' + this.response.message;
			var data = this.response.content && this.response.content.response ? this.response.content.response : {};
			if (mad.eventBus) mad.eventBus.trigger('passbolt_notify', {
				'status': this.response.status,
				'title': this.response.title,
				'message': message,
				'data': data
			});
		}
	});

});