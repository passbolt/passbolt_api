steal(
	'mad/controller/componentController.js',
	'mad/core/singleton.js',
	'app/view/component/notification.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.NotificationController
	 * @inherits mad.controller.ComponentController
	 * @parent index
	 * @see {mad.view.View}
	 * @see {mad.core.Singleton}
	 * 
	 * @constructor
	 * The Notification class Controller will be used to display to users 
	 * application' messages.
	 * </br>
	 * The notification class Controller is a singleton, use the function .singleton()
	 * to instanciate or get it.
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
		'{mad.eventBus} passbolt_notify': function (el, ev, notif) {
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

	// Augment the notification controller with the Singleton Object
	// @todo move this feature in the extend function, override the extend function of the class Class
	passbolt.controller.component.NotificationController.augment('mad.core.Singleton');
});
