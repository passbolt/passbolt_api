import 'mad/view/component/tree';
import 'app/component/comments';
import 'app/component/sidebar_section';

/*
 * @inherits mad.component.Component
 * @parent index
 *
 * @constructor
 * Creates a new Sidebar Component
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.Sidebar}
 */
var Sidebar = passbolt.view.component.Sidebar = mad.View.extend('passbolt.view.component.Sidebar', /** @static */ {

	defaults: {
		label: 'Sidebar Controller',
		viewClass: passbolt.view.component.ResourceDetails,
		// the resource to bind the component on
		resource: null
	}

}, /** @prototype */ {

	/**
	 * before start hook.
	 * @return {void}
	 */
	beforeRender: function () {
		this._super();
	},

	/**
	 * Called right after the start function
	 * @return {void}
	 * @see {mad.controller.ComponentController}
	 */
	afterStart: function () {

	}


	/* ************************************************************** */
	/* LISTEN TO THE MODEL EVENTS */
	/* ************************************************************** */


});

export default Sidebar;