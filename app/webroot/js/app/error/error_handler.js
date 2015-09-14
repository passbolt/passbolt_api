import "mad/error/error_handler";

/**
 * @inherits jQuery.Class
 * @parent mad.core
 *
 * Common error handler.
 * Use the console as logger.
 */
passbolt.error = passbolt.error || {};
var ErrorHandler = passbolt.error.ErrorHandler = mad.error.ErrorHandler.extend('passbolt.error.ErrorHandler', /** @static */ {

});

export default ErrorHandler;