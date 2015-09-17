import 'mad/component/component';
import 'app/model/notification';
import 'app/view/component/notification';
import 'app/view/template/component/notification.ejs!';

/**
 * @inherits mad.Component
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
 * @return {passbolt.component.Notification}
 */
var Notification = passbolt.component.Notification = mad.Component.extend('passbolt.component.Notification', /** @static */ {

	defaults: {
		label: 'Notification Component',
		viewClass: passbolt.view.component.Notification,
		status: 'hidden',
		notifications: [],
		templateUri: 'app/view/template/component/notification.ejs'
	}

}, /** @prototype */ {

	/**
	 * Load a notification
	 * @param {passbolt.model.Notification} notification
	 */
	load: function (notification) {
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
	'{mad.bus.element} passbolt_notify': function (el, ev, notif) {
		// @todo fixed in future canJs.
		if (!this.element) return;

		this.load(new passbolt.model.Notification(notif));
	}

});

export default Notification;