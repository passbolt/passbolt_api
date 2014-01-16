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

		'beforeRender': function() {
			this._super();
			if(this.state.label != 'ready') {
				this.setViewData({
					'status': '',
					'title': '',
					'message': '',
					'data': '',
					'persistent': false
				});
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
			// The component is not already started, start it
			if(this.view == null) {
				this.start();
			}
			// Pass the data to the view
			this.setViewData({
				'status': notif.status,
				'title': notif.title,
				'message': notif.message,
				'data': notif.data,
				'persistent': notif.persistent
			});
			$.extend( this.view.params, notif );
			this.setState('ready');
			this.refresh();
			this.view.reset();
		}

	});
});
