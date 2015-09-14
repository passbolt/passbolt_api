import 'mad/view/view';

/**
 * @inherits mad.view.View
 */
var Notification = passbolt.view.component.Notification = mad.View.extend('passbolt.view.component.Notification', /** @static */ {

	defaults: {
		timeout: 2500,
		notifications: []
	}

}, /** @prototype */ {

	// constructor like
	init: function(elt, opts, notifications) {
		var timeoutConf = mad.Config.read('notification.timeout');
		if (typeof timeoutConf != 'undefined') {
			this.options.timeout = timeoutConf;
		}
		this._super(elt, opts);
	},

	/**
	 * Load a new notification
	 */
	load: function(notification) {
		this.notifications.push(notification);
	},

	/**
	 * Override mad.view.View.render() function.
	 */
	render: function () {
		var self = this;

		// Set the view data with the next notification in the queue.
		var notifications = this.getController().options.notifications,
			notification = notifications.shift();
		this.getController().setViewData(notification);

		// Hide the notification after a defined timeout.
		setTimeout(function () {
			if (notifications.length) {
				self.getController().refresh();
			} else {
				self.getController().setState('hidden');
			}
		}, self.options.timeout);

		return this._super();
	}

});

export default Notification;