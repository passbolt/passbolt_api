import 'mad/component/component';
import 'app/model/notification';
import 'app/view/component/notification';
import 'app/util/common';
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
	 * Read settings from the configuration file.
	 *
	 * If no settings are provided, but the notification is an error,
	 * then return error settings. Otherwise return null.
	 *
	 * @param notification
	 * @returns {*}
	 * @private
	 */
	_readSettings: function(notification) {
		var notifSettings = mad.Config.read('notification.messages.' + notification.title);
		if (notifSettings == undefined) {
			// Exception in case of errors.
			// We want to return all errors without exceptions.
			if (notification.status == "error") {
				return {
					"msg": notification.data.header.message,
					"severity": "error"
				};
			}
			return null;
		}
		return notifSettings;
	},

	/**
	 * Get settings for a given notification.
	 * @param notification
	 * @returns {*}
	 * @private
	 */
	_getNotificationSettings: function(notification) {
		var notifSettings = this._readSettings(notification);
		if (notifSettings == null) {
			return null;
		}

		// Severity is taken from the configuration.
		// If there is no configuration, then from the message status.
		// If no status, then it is the default : notice.
		if (can.getObject('severity', notifSettings) == undefined) {
			notifSettings.severity = (notification.status != undefined) ? notification.status : 'notice';
		}
		// If no group is provided, then it is put in the main.
		if (can.getObject('group', notifSettings) == undefined) {
			notifSettings.group = 'main';
		}
		// If no message is provided, we return null.
		if (can.getObject('msg', notifSettings) == undefined) {
			return null;
		}
		return notifSettings;
	},

	/**
	 * Build the message string for a given notification, and given settings.
	 * @param notification
	 * @param settings
	 * @returns {*|Object}
	 * @private
	 */
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

	/**
	 * Populate a notification object from given settings.
	 * @param notification
	 * @param settings
	 * @returns {*}
	 * @private
	 */
	_populateNotification: function(notification, settings) {
		notification.message = this._buildMessage(notification, settings);
		// Status is equal to the status given, or if not defined the severity defined in the config.
		notification.status = (notification.status != undefined) ? notification.status : settings.severity;
		// Set severity.
		notification.severity = settings.severity;
		// Generate id.
		notification.id = passbolt.Common.uuid(notification.title);

		return notification;
	},

	/**
	 * Check whether a notification should be displayed depending on the configuration given.
	 * @param notification
	 * @param settings
	 * @returns {boolean}
	 * @private
	 */
	_checkShouldBeDisplayed: function(notification, settings) {
		// Check the settings provided in the configuration file.
		var displaySeverity = mad.Config.read('notification.displaySeverity');
		// If settings are not provided, we return false. (should not be displayed).
		if (displaySeverity == undefined) {
			return false;
		}
		// If the notification severity is included in the severity options in the config, we return true.
		if (displaySeverity.indexOf(notification.severity) != -1) {
			return true;
		}

		return false;
	},

	/**
	 * Load a notification.
	 * Basically receive a configuration and get the corresponding configuration for the given notification.
	 * The configuration is provided in the configuration file.
	 * The strategy is the following :
	 * 1. We check if a configuration is given for the received notification (conf retrieved with the title).
	 *   a. If no configuration, we do nothing.
	 *   b. If there is a configuration, we continue.
	 * 2. From the configuration given, and the defaults, populate the configuration with the missing information.
	 *   - message : the final message, formatted, translated, and with variables replaced by their match in data.
	 *   - status : type of notification
	 *   - severity : the severity of the notification. With this information we can then configure whether or not to display the notif.
	 *   - id : is recalculated locally as per the title, always in a previsible way (for css and tests).
	 * 3. Check whether or not the notification should be displayed on the interface.
	 *    This is decided through the severity and the severityDisplay settings.
	 * Once it is confirmed that the message should be displayed, push it on the interface.
	 * @param {passbolt.model.Notification} notification
	 */
	load: function (notification) {
		// Check if notification should be processed.
		var notifSettings = this._getNotificationSettings(notification);
		if (notifSettings === null) {
			return;
		}
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
	 * Listen the event passbolt_notify and display load the corresponding notification.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {array} notification
	 */
	'{mad.bus.element} passbolt_notify': function (el, ev, notif) {
		// When we receive a notification, we load it in the main system.
		this.load(new passbolt.model.Notification(notif));
	}

});

export default Notification;