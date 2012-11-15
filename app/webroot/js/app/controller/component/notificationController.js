steal(
	'mad/controller/componentController.js',
	'mad/core/singleton.js'
).then(function () {

	/*
	 * @class passbolt.controller.component.NotificationController
	 * @inherits mad.controller.ComponentController
	 * @parent index
	 * @see {mad.view.View}
	 * @see {mad.core.Singleton}
	 * 
	 * @constructor
	 * The Notification class Controller will be used to display to users message
	 * from the application.
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
			'label': 'Notification Controller'
		}

	}, /** @prototype */ {

		// 
		'timeoutBeforeReset': null,

		// constructor like
		'init': function () {
			this._super();
		},

		/**
		 * Render the component
		 * @see {mad.controller.ComponentController}
		 */
		'render': function (options) {
			var self = this;
			// A notification is already shown
			if (this.timeoutBeforeReset) {
				clearTimeout(this.timeoutBeforeReset);
				self.reset();
			}
			// reset the notificator after 30 secondes
			setTimeout(function(){
				self.reset();
			}, 30000);

			this._super();
			var eltWidth = this.element.width(),
				refEltWidth = $('#js_search_field').width(),
				left = (refEltWidth - eltWidth) / 2
			this.element.css('left', left);
		},

		/**
		 * reset the component
		 */
		'reset': function () {
			this.setState('hidden');
			this.element.empty();
		},

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
			this.reset();
			this.setViewData({
				'status': notif.status,
				'title': notif.title,
				'message': notif.message,
				'data': notif.data
			});
			this.render();
			this.setState('ready');
		},

		/**
		 * The user wants to see the message details
		 * @param {HTMLElement} el The element the event occured on
		 * @param {HTMLEvent} ev The event which occured
		 * @return {void}
		 */
		'#js_notification_more_button click': function (element, ev) {
			var self = this;
			$(this.element).find('#js_notification_details').show().one('mouseleave', function () {
				$(this).hide();
			});
		}

	});

	// Augment the notification controller with the Singleton Object
	// @todo move this feature in the extend function, override the extend function of the class Class
	passbolt.controller.component.NotificationController.augment('mad.core.Singleton');
});
