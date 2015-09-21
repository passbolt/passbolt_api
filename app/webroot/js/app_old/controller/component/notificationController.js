steal(
	'mad/controller/componentController.js',
	'app/model/notification.js',
	'app/view/component/notification.js',

	'app/view/template/component/notification.ejs'
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
            'status': 'hidden',
			'notifications': []
		}

	}, /** @prototype */ {

		/**
		 * Load a notification
		 * @param {passbolt.model.Notification} notification
		 */
		'load': function (notification) {
			this.options.notifications.push(notification);

			// The component is not already started, start it
			if(this.view == null) {
				this.start();
			}
			// If the component is not ready restart it, otherwise the view will take care of the notifications queue.
			else if (this.state.is('hidden')) {
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
			// @todo fixed in future canJs.
			if (!this.element) return;

			this.load(new passbolt.model.Notification(notif));
		}

	});
});
