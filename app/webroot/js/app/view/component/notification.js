steal(
	'mad/view'
).then(function () {

	/*
	 * @class passbolt.view.component.Notification
	 * @inherits mad.view.View
	 */
	mad.view.View.extend('passbolt.view.component.Notification', /** @static */ {

		'defaults': {
			'timeout': 2500,
			'timeoutBeforeReset': null,
			'persistent': false
		}

	}, /** @prototype */ {

		// constructor like
		'init': function(elt, opts) {
			var timeoutConf = mad.Config.read('notification.timeout');
			if (typeof timeoutConf != 'undefined') {
				this.options.timeout = timeoutConf;
			}
			this._super(elt, opts);
		},

		/**
		 * Override mad.view.View.render() function.
		 */
		'render': function () {
			var self = this;

			// A notification is already shown, destroy the current timeout listener
			if (this.options.timeoutBeforeReset) {
				if(!this.options.persistent) {
					clearTimeout(this.options.timeoutBeforeReset);
				}
				self.getController().setState('hidden');
			}

			if(!this.options.persistent) {
				// hide the notificator after timeout value
				self.options.timeoutBeforeReset = setTimeout(function () {
					self.getController().setState('hidden');
				}, self.options.timeout);
			}

			return this._super();
		}

	});
});