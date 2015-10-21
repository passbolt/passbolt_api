import moment from 'moment';
import passbolt from 'app/util/util';
import 'mad/mad';
import 'mad/bootstrap';
import 'app/error/error_handler';
import 'app/net/response_handler';
import 'app/component/app';
import appConfig from 'app/config/config.json';
import notifConfig from 'app/config/notification.json';

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
		// Load the config packaged with the front-end application.
		mad.Config.load(appConfig);
		// Load notifications config.
		mad.Config.load(notifConfig);
		// Load the dynamic config served by the back-end.
		mad.Config.load(cakephpConfig);

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
	}

});

export default Bootstrap;