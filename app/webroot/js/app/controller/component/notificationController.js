steal(
	'mad/controller/componentController.js',
	'app/model/notification.js',
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
			'viewClass': passbolt.view.component.Notification,
			// The notification to display
			'notification': null,
			'status': 'hidden'
		}

	}, /** @prototype */ {

		/**
		 * Load a notification
		 * @param {passbolt.model.Notification} notification
		 */
		'load': function (notification) {
			this.options.notification = notification;
			this.setViewData(this.options.notification);

			// The component is not already started, start it
			if(this.view == null) {
				this.start();
			}
			// Otherwise refresh it
			else {
				this.refresh();
				this.setState('ready');
			}
		},

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Listen the event passbolt_notify and display any notification
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @param {array} notif
		 */
		'{mad.bus} passbolt_notify': function (el, ev, notif) {
			this.load(new passbolt.model.Notification(notif));
		}

	});
});
