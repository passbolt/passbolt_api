steal( 
    MAD_ROOT+'/controller/componentController.js',
	MAD_ROOT+'/core/singleton.js'
)
.then( function ($) {

	/*
	 * @class passbolt.controller.component.NotificationController
	 * @inherits mad.controller.ComponentController
	 * @parent index
	 * @see {mad.view.View}
	 * @see {mad.core.Singleton}
	 * 
	 * The Notification class Controller will be used to display to users message
	 * from the application.
	 * </br>
	 * The notification class Controller is a singleton, use the function .singleton()
	 * to instanciate or get it.
	 */
	mad.controller.ComponentController.extend('passbolt.controller.component.NotificationController',
	/** @static */
	{
		'defaults': {
			'label': 'Notification Controller'
		}
	},
	/** @prototype */
	{

		/* ************************************************************** */
		/* LISTEN TO THE APP EVENTS */
		/* ************************************************************** */

		/**
		 * Listen the event passbolt_notify and display any 
		 * @param {Notification} notif
		 */
		// @todo notice that the event has to be writen with a-Z0-1_
		// create an object Notification
		'{mad.eventBus} passbolt_notify': function (elt, event, notif) {
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
		 * @param {jQuery} element The source element
		 * @param {Event} event The jQuery event
		 * @return {void}
		 */
		'#js_notif_see_details click': function (element, ev) {
			var self = this;
			$(this.element).find('#js_notif_details').show().one('mouseleave', function () {
				$(this).hide();
			});
		}
	});

	// Augment the notification controller with the Singleton Object
	// @todo move this feature in the extend function, override the extend function of the class Class
	passbolt.controller.component.NotificationController.augment('mad.core.Singleton');
});