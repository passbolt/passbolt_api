import 'mad/model/model';

/**
 * @inherits {mad.Model}
 * @parent index
 *
 * The Notification model used to carry notification message
 *
 * @constructor
 * Creates a notification message
 * @param {array} options
 * @return {passbolt.model.Notification}
 */
var Notification = passbolt.model.Notification = mad.Model.extend('passbolt.model.Notification', /** @static */ {

	attributes: {
		// The status of the notification
		status: 'string',
		// The title of the notification
		title: 'string'
	}

}, /** @prototype */ { });
export default Notification;

