steal('can/construct').then(function () {

	/*
	 * @class mad.error.ErrorHandler
	 * @inherits jQuery.Class
	 * @parent mad.core
	 * 
	 * Common error handler.
	 * Use the console as logger.
	 */
	can.Construct('mad.error.ErrorHandler', /** @static */ {

		/**
		 * Log the error or exception
		 * @return {void}
		 */
		'_log': function (status, title, message, data) {
			var log = status.toUpperCase() + ' ' +
				title + ' ' +
				'(' + message + ')';

			console.log(log);
			console.log(data);
			steal.dev.warn(log);
			if (data) {
				steal.dev.warn(data);
			}
		},

		/**
		 * Handle Exception
		 * @param {Exception} ex
		 * @return {void}
		 */
		'handleException': function (exception) {
			mad.error.ErrorHandler._log(
				'exception',
				exception.name,
				exception.message,
				exception.stack || null
			);
			throw exception;
		},

		/**
		 * Handle Error
		 * @param {string} status Error status
		 * @param {string} title Error title
		 * @param {string} message Error message
		 * @param {mixed} data Error associated data
		 * @return {void}
		 */
		'handleError': function (status, title, message, data) {
			mad.error.ErrorHandler._log(
				status,
				title,
				message || '',
				data || null
			);
		}
	},

	/** @prototype */ {});

});