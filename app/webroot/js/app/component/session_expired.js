import 'mad/component/component';

import 'app/view/template/component/session_expired.ejs!';

/**
 * @class passbolt.component.SessionExpired
 * @inherits mad.Component
 * @parent index
 *
 * @constructor
 * Creates a new Session Expired compoennt
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.SessionExpired}
 */
var SessionExpired = passbolt.component.SessionExpired = mad.Component.extend('passbolt.component.SessionExpired', /** @static */ {

	defaults: {
		label: 'Session Expired Controller',
		templateBased: true,
		templateUri: 'app/view/template/component/session_expired.ejs',
		timeToRedirect: 5000,
		countDownInterval: null,
	}

}, /** @prototype */ {

	/**
	 * The session expired component has been destroyed.
	 */
	destroy: function() {
		if (this.options.countDownInterval != null) {
			clearInterval(this.options.countDownInterval);
		}
	},

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function() {
		var self = this,
			initialTime = new Date().getTime();

		// Check every second if the time to wait before redirection has been consumed.
		this.options.countDownInterval = setInterval(function() {
				var elapsedTime = new Date().getTime() - initialTime;
				if (elapsedTime > self.options.timeToRedirect) {
					clearInterval(self.options.countDownInterval);
					location.href = mad.Config.read('app.url');
				}
			}, 1000);
	},

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	/**
	 * The user clicked on the Redirect now button
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	' .submit-wrapper input click': function(el, ev) {
		clearInterval(this.options.countDownInterval);
		location.href = mad.Config.read('app.url');
	}
});

export default SessionExpired;
