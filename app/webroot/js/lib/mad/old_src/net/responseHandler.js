steal(
	'can/construct'
).then(function () {

	/*
	 * @class mad.net.ResponseHandler
	 * @see mad.error.ErrorHandler
	 * @inherits mad.core.Class
	 * @parent mad.net
	 * 
	 * Ajax server response handler
	 * 
	 * @constructor
	 * Creates a new ajax server response handler
	 * @param {mad.net.Response} response The server response
	 * @param {object} request The request parameters
	 * @param {object} callbacks The optional callbacks to push on if existing
	 * @return {mad.net.ResponseHandler}
	 */
	can.Construct('mad.net.ResponseHandler', /** @static */ {

	}, /** @prototype */ {

		/**
		 * The server response to treat
		 * @type {mad.net.Response}
		 */
		'response': null,

		/**
		 * The server request
		 * @type {object}
		 */
		'request': null,

		/**
		 * The callback to push on
		 * @type {object}
		 */
		'callback': null,

		// Constructor like
		'init': function (response, request, callbacks) {
			this.response = response;
			this.request = request;
			this.callbacks = callbacks || {};
		},

		/**
		 * Handle the reponse and dispatch to the right action.
		 * @return {void}
		 */
		'handle': function () {
			// log response
			this._log();
			// Dispatch the response function of the response status
			switch (this.response.getStatus('status')) {
			case mad.net.Response.STATUS_ERROR:
				this._error();
				break;
			case mad.net.Response.STATUS_NOTICE:
				this._notice();
				break;
			case mad.net.Response.STATUS_SUCCESS:
				this._success();
				break;
			case mad.net.Response.STATUS_WARNING:
				this._warning();
				break;
			}
		},

		/**
		 * Log the server response
		 * @return {void}
		 */
		'_log': function () {
			var message = this.response.getStatus().toUpperCase() + ' ' +
				this.request.type.toUpperCase() + ' ' +
				this.request.url + '  ' +
				this.response.getController() + ' ' +
				this.response.getAction() + ' (' +
				this.response.getTitle() + ')';

			steal.dev.log(message);
		},

		/**
		 * Handle success response
		 * @return {void}
		 */
		'_success': function () {
			// callback if defined
			if (this.callbacks.success) {
				this.callbacks.success(this.response, this.request);
			}
		},

		/**
		 * Handle error response
		 * @return {void}
		 */
		'_error': function () {
			// notify the error handler
			mad.Config.read('error.ErrorHandlerClass').handleError(
				this.response.getStatus(),
				this.response.getTitle(),
				this.response.getMessage(),
				this.response.getData()
			);
			// callback if defined
			if (this.callbacks.error) {
				this.callbacks.error(this.response, this.request);
			}
		},

		/**
		 * Handle notice response
		 * @return {void}
		 */
		'_notice': function () {
			// notify the error handler
			mad.Config.read('error.ErrorHandlerClass').handleError(
				this.response.getStatus(),
				this.response.getTitle(),
				this.response.getMessage(),
				this.response.getData()
			);
			// callback if defined
			if (this.callbacks.notice) {
				this.callbacks.notice(this.response, this.request);
			}
		},

		/**
		 * Handle warning response
		 * @return {void}
		 */
		'_warning': function () {
			// notify the error handler
			mad.Config.read('error.ErrorHandlerClass').handleError(
				this.response.getStatus(),
				this.response.getTitle(),
				this.response.getMessage(),
				this.response.getData()
			);
			// callback if defined
			if (this.callbacks.warning) {
				this.callbacks.warning(this.response, this.request);
			}
		}
	});

});