steal('jquery/class').then(function ($) {

	/*
	 * @class mad.error.ErrorHandler
	 * @inherits jQuery.Class
	 * @parent mad.core
	 * 
	 * Common error handler. Use the console as output
	 */
	$.Class('mad.error.ErrorHandler', /** @static */ {

		/**
		 * Handle Exception
		 * @param {Exception} ex
		 * @return {void}
		 */
		'handleException': function (exception) {
			steal.dev.warn('An exception occured: status(exception) title (' + exception.name + ') message (' + exception.message + ')');
			if (exception.stack) {
				steal.dev.warn(exception.stack);
				throw exception;
			}
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
			steal.dev.warn('An error occured: status(' + status + ') title (' + title + ') message (' + message + ')');
			if (data) {
				steal.dev.warn(data);
			}
		}
	},

	/** @prototype */ {});

});