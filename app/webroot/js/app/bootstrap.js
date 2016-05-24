import moment from 'moment';
import 'moment-timezone';
import passbolt from 'app/util/util';
import 'mad/mad';
import 'mad/bootstrap';
import 'app/error/error_handler';
import 'app/net/response_handler';
import 'app/component/app';
import Common from 'app/util/common';

var Bootstrap = passbolt.Bootstrap = mad.Bootstrap.extend('passbolt.Bootstrap', /* @static */ {

	/**
	 * Default options.
	 */
	defaults: {
	}

}, /**  @prototype */ {

	/**
	 * Constructor.
	 * @param options
	 */
	init: function (options) {
		// Load mad bootstrap.
		this._super(options);
		// Load helpers.
		this.loadViewHelpers();
	},

	/**
	 * Load helpers for the view.
	 */
	loadViewHelpers: function() {
		// Load view helper
		can.ejs.Helpers.prototype.moment = moment;
		// Load date helper.
		can.ejs.Helpers.prototype.datetimeToJSDate = Common.datetimeToJSDate;
		// Load datetimeGetTimeAgo.
		can.ejs.Helpers.prototype.datetimeGetTimeAgo = Common.datetimeGetTimeAgo;
	}

});

export default Bootstrap;