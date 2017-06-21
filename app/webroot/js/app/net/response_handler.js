import 'mad';

import 'app/component/session_expired';

/**
 * @inherits {mad.net.ResponseHandler}
 * @parent index
 *
 * Our ajax response handler. It will handle each ajax response on passbolt.
 *
 * @constructor
 * Create a new response handler
 * @return {passbolt.net.ResponseHandler}
 */
mad.net.ResponseHandler.extend('passbolt.net.ResponseHandler', /** @static */ {

}, /** @prototype */ {

	/**
	 * Handle the success response. By default notify the user about this success
	 * if the request is a POST, DELETE or PUT request.
	 */
	'_success': function () {
		// send a notification on the event bus for any successful response.
		// the notification system will take care of filtering what should be displayed.
		if (mad.bus && (this.request.silentNotify == undefined || !this.request.silentNotify)) {
			mad.bus.trigger('passbolt_notify', {
				title: this.response.header.title,
				status: this.response.header.status,
				data: this.response
			});
		}
		this._super();
	},

	'_error': function() {
        // Trigger notification.
        if (mad.bus && (this.request.silentNotify == undefined || !this.request.silentNotify)) {
            mad.bus.trigger('passbolt_notify', {
                title: this.response.header.title,
                status: this.response.header.status,
                data: this.response
            });
        }

		// If the user is not logged in to the application.
		// Redirect the user to the front page.
		if(this.response.getStatus() == mad.net.Response.STATUS_ERROR
			&& this.response.getMessage() == __('You need to login to access this location')) {

			// If the session expired dialog has already been shown.
			if ($('.session-expired-dialog').length > 0) {
				return;
			}

			// get the dialog
			var dialog = new mad.component.Dialog(null, {
				label: __('Session expired'),
				cssClasses : ['session-expired-dialog', 'dialog-wrapper']
			}).start();

			// attach the component to the dialog
			dialog.add(passbolt.component.SessionExpired, {});
		}
		else {
			// @todo Same for success we use message as title, maybe we should to something cleaner.
			this.response.attr('header').title = this.response.getMessage();
		}
		this._super();
	}
});
