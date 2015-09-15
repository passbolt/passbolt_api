import 'mad/view/component/tree';
//import 'app/component/comments';

passbolt.component.sidebarSection = passbolt.component.sidebarSection || {};

/*
 * @inherits mad.Component
 * @parent index
 *
 * @constructor
 * Creates a new Sidebar Section Controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.SidebarSection}
 */
var SidebarSection = passbolt.component.SidebarSection = mad.Component.extend('passbolt.component.SidebarSection', /** @static */ {
	'defaults': {
		'label': 'Sidebar Section Component',
		'viewClass': passbolt.view.component.ResourceDetails,
		// the resource to bind the component on
		'resource': null
	}
}, /** @prototype */ {

});

export default SidebarSection;
