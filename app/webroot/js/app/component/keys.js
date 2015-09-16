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

	defaults: { }

}, /** @prototype */ {

	/**
	 * After start.
	 */
	afterStart: function() {
		var self = this;
		this._super();
	},

	/**
	 * Before render.
	 */
	beforeRender: function() {
		var self = this;
		this._super();
		// Set user key data.
		self.setViewData('gpgkey', passbolt.model.User.getCurrent().Gpgkey);
	},

	/* ************************************************************** */
	/* LISTEN TO THE VIEW EVENTS */
	/* ************************************************************** */

	'#js_settings_keys_download click': function(el, ev) {
		mad.bus.trigger('passbolt.settings.backup_key');
	}
});
export default Keys;