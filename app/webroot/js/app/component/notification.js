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

	_getNotificationSettings: function(notification) {
		var notifSettings = mad.Config.read('notification.messages.' + notification.title);
		if (can.getObject('severity', notifSettings) == undefined) {
			notifSettings.severity = 'notice';
		}
		if (can.getObject('group', notifSettings) == undefined) {
			notifSettings.group = 'main';
		}
		if (can.getObject('msg', notifSettings) == undefined) {
			notifSettings.msg = '--';
		}
		return notifSettings;
	},

	_buildMessage: function(notification, settings) {
		var msg = can.getObject('msg', settings);
		var variables = msg.match(/%([^%]*)%/g);
		var data = notification.data;
		for (let i in variables) {
			let dataKey = variables[i].replace(/%/g, '');
			let value = can.getObject(dataKey, data);
			if (value == undefined) {
				value = 'undefined';
			}
			msg = msg.replace(variables[i], value);
		}
		return msg;
	},

	_populateNotification: function(notification, settings) {
		if (!mad.Config.read('notification.messages.' + notification.title)) {
			return null;
		}
		notification.message = this._buildMessage(notification, settings);
		// Status is equal to the status given, or if not defined the severity defined in the config.
		notification.status = (notification.status != undefined) ? notification.status : settings.severity;
		// Set severity.
		notification.severity = settings.severity;

		// TODO : set uuid
		// TODO : check if notification should be displayed according to the severity.
		return notification;
	},

	_checkShouldBeDisplayed: function(notification, settings) {
		var displaySeverity = mad.Config.read('notification.displaySeverity');
		if (displaySeverity == undefined) {
			return false;
		}
		if (displaySeverity.indexOf(notification.severity) != -1) {
			return true;
		}
		return false;
	},

	/**
	 * Load a notification
	 * @param {passbolt.model.Notification} notification
	 */
	load: function (notification) {
		// Check if notification should be processed.
		var title = notification.title;
		var notifSettings = this._getNotificationSettings(notification);
		var notification = this._populateNotification(notification, notifSettings);
		var display = this._checkShouldBeDisplayed(notification, notifSettings);
		if (notification === null) {
			return;
		}
		if (display === false) {
			return;
		}
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