import 'mad/component/component';
import 'app/model/gpgkey';
import 'app/view/template/component/keys.ejs!';


/**
 * @inherits {mad.Component}
 * @parent index
 *
 * Our Keys controller
 *
 * @constructor
 * Creates a new Keys Controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.Keys}
 */
var Keys = passbolt.component.Keys = mad.Component.extend('passbolt.component.Keys', /** @static */ {

	defaults: {
		templateUri: 'app/view/template/component/keys.ejs'
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function() {
		this._super();
	},

	/**
	 * Before render.
	 */
	beforeRender: function() {
		var self = this;
		this._super();
		var gpgKey = passbolt.model.User.getCurrent().Gpgkey;
		gpgKey.uid = passbolt.Common.decodeHtmlEntities(gpgKey.uid);
		// Set user key data.
		self.setViewData('gpgkey', gpgKey);
	}
});

export default Keys;