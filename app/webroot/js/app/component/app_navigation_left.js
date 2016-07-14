import  'mad/component/menu';

/**
 * @inherits {mad.component.Menu}
 * @parent index
 *
 * Navigation left controller
 *
 * @constructor
 * Instantiate the application navigation left component
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.AppNavigationLeft}
 */
var AppNavigationLeft = passbolt.component.AppNavigationLeft = mad.component.Menu.extend('passbolt.component.AppNavigationLeft', /** @static */ {

	defaults: {
		// The current selected workspace
		selected: null
	}

}, /** @prototype */ {

	/**
	 * After start hook.
	 * Initialize the menu items.
	 * @see {mad.Component}
	 */
	afterStart: function () {
		var self = this,
			menuItems = [
				new mad.model.Action({
					id: 'js_app_nav_left_home_link',
					label: __('home'),
					cssClasses: ['home'],
					action: function () {
						self.options.selected = 'home';
						mad.bus.trigger('request_workspace', 'password');
					}
				}), new mad.model.Action({
					id: 'js_app_nav_left_password_wsp_link',
					label: __('passwords'),
					cssClasses: ['password'],
					action: function () {
						self.options.selected = 'password';
						mad.bus.trigger('request_workspace', 'password');
					}
				}), new mad.model.Action({
					id:  'js_app_nav_left_user_wsp_link',
					label: __('users'),
					cssClasses: ['user'],
					action: function () {
						self.options.selected = 'people';
						mad.bus.trigger('request_workspace', 'people');
					}
				})
			];
		this.load(menuItems);
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user wants to switch to another workspace
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 * @param {string} workspace The target workspace
	 * @param {array} options Workspace's options
	 */
	'{mad.bus.element} request_workspace': function (el, event, workspace, options) {
		if (this.options.selected != workspace) {
			var li = $('li.' + workspace),
				itemClass = this.getItemClass();

			if (itemClass) {
				var data = li.data(itemClass.fullName);
				if (typeof data != 'undefined') {
					this.selectItem(data);
				}
			}

		}
	}

});

export default AppNavigationLeft;