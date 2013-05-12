steal(
	'mad/controller/componentController.js',
	'app/view/component/notification.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.NotificationController
	 * @inherits mad.controller.ComponentController
	 * @parent index
	 * @see {mad.view.View}
	 * 
	 * @constructor
	 * The Notification class Controller will be used to display to users 
	 * application' messages.
	 * 
	 * @param {HTMLElement} element the element this instance operates on.
	 * @param {Object} [options] option values for the controller.  These get added to
	 * this.options and merged with defaults static variable 
	 * @return {passbolt.controller.component.NotificationController}
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.NotificationController', /** @static */ {

		'defaults': {
			'label': 'Notification Controller',
			'viewClass': passbolt.view.component.Notification
		}

	}, /** @prototype */ {

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Listen the event passbolt_notify and display any 
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {array} notif
		 */
		// @todo notice that the event has to be writen with a-Z0-1_
		// create an object Notification
		'{mad.bus} passbolt_notify': function (el, ev, notif) {
			this.setViewData({
				'status': notif.status,
				'title': notif.title,
				'message': notif.message,
				'data': notif.data
			});
			this.render();
			this.setState('ready');
		}

	});
});
