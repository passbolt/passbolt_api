steal('jquery/class').then(function () {

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
	mad.net.ResponseHandler.extend('passbolt.net.ResponseHandler', /** @static */ {

	}, /** @prototype */ {

		/**
		 * Handle the success response. By default notify the user about this success
		 * if the request is a POST, DELETE or PUT request.
		 * @return {void}
		 */
		'_success': function () {
			// send a notification on the events bus if the request is a POST, PUT or DELETE
			switch (this.request.type.toUpperCase()) {
			case 'POST':
			case 'PUT':
			case 'DELETE':
				if (mad.eventBus) {
					mad.eventBus.trigger('passbolt_notify', {
						'status': this.response.getStatus(),
						'title': this.response.getMessage()
					});
				}
				break;
			}

			this._super();
		}
	});

});