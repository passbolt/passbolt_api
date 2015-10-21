/**
 * @inherits jQuery.Class
 * @parent mad.core
 *
 * Common error handler.
 * Use the console as logger.
 */
var ErrorHandler = mad.error.ErrorHandler = can.Construct.extend('mad.error.ErrorHandler', /** @static */ {

    /**
     * Log the error or exception
     * @return {void}
     */
    _log: function (status, title, message, data) {
        var log = status.toUpperCase() + ' ' +
            title + ' ' +
            '(' + message + ')';

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
    handleException: function (exception) {
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
    handleError: function (status, title, message, data) {
        mad.error.ErrorHandler._log(
            status,
            title,
            message || '',
            data || null
        );
    }
},

/** @prototype */ {});

export default ErrorHandler;
