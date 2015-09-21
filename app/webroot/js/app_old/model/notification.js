steal(
	'mad/model'
).then(function () {

	/*
	 * @class passbolt.model.Notification
	 * @inherits {mad.model.Model}
	 * @parent index
	 * 
	 * The Notification model used to carry notification message
	 * 
	 * @constructor
	 * Creates a notification message
	 * @param {array} options
	 * @return {passbolt.model.Notification}
	 */
	mad.model.Model('passbolt.model.Notification', /** @static */	{

		'attributes': {
			// The status of the notification
			'status': 'string',
			// The title of the notification
			'title': 'string'
		}

	}, /** @prototype */ { });

});
