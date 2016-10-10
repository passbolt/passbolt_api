import 'urijs/punycode';
import 'urijs/SecondLevelDomains';
import 'urijs/IPv6';
import URI from 'urijs/URI';
import 'mad/view/component/tree';
import 'app/component/sidebar';
import 'app/view/component/resource_sidebar';
import 'app/component/comments';
import 'app/component/sidebar_section';
import 'app/component/sidebar_section/description';
import 'app/component/sidebar_section/permissions';
import 'app/view/template/component/resource_sidebar.ejs!';

/**
 * @inherits mad.Component
 * @parent index
 *
 * @constructor
 * Creates a new Resource sidebar controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.ResourceSidebar}
 */
var ResourceSidebar = passbolt.component.ResourceSidebar = passbolt.component.Sidebar.extend('passbolt.component.ResourceSidebar', /** @static */ {

	defaults: {
        // Label.
		label: 'Resource Details',
        // View class to be used.
		viewClass: passbolt.view.component.ResourceSidebar,
		// template uri.
		templateUri: 'app/view/template/component/resource_sidebar.ejs'
	}

}, /** @prototype */ {

	/**
	 * before start hook.
	 */
	beforeRender: function () {
		this._super();
        if (this.options.selectedItem != null) {
			// Format the resource URI.
			var uri = URI(this.options.selectedItem.uri);
			// If the uri is an url and is not absolute.
			// Add the default http:// protocol
			if (!uri.is('absolute') && uri.is('url')) {
				uri.protocol('http');
			}

			// Send the resource formatted uri to the view
			this.setViewData('resourceFormattedUri', uri.toString());
            // Send the resource to the view
			this.setViewData('resource', this.options.selectedItem);
            // Send the secret strength label to the view
            var secretStrength = passbolt.model.SecretStrength.getSecretStrength(this.options.selectedItem.Secret.data);
            this.setViewData('secretStrength', secretStrength);
        }
	},

	/**
	 * After start hook.
	 * @see {mad.Component}
	 */
	afterStart: function () {
        this._super();

		// Instantiate the description controller for the current resource.
		var descriptionController = new passbolt.component.sidebarSection.Description($('#js_rs_details_description', this.element), {
			resource: this.options.selectedItem
		});
		descriptionController.start();

		// Instantiate the permissions component for the current resource.
		var permissionsComponent = new passbolt.component.sidebarSection.Permissions($('#js_rs_details_permissions', this.element), {
			acoInstance: this.options.selectedItem
		});
		permissionsComponent.start();

		// Instantiate the comments controller for the current resource.
		var commentsController = new passbolt.component.Comments($('#js_rs_details_comments', this.element), {
			resource: this.options.selectedItem,
			foreignModel: 'Resource',
			foreignId: this.options.selectedItem.id
		});
		commentsController.start();
	},

	/* ************************************************************** */
	/* LISTEN TO THE STATE CHANGES */
	/* ************************************************************** */

	/**
	 * A password has been clicked.
	 * @param {HTMLElement} el The element the event occurred on
	 * @param {HTMLEvent} ev The event which occurred
	 */
	' password_clicked': function (el, ev) {
		// Get secret out of Resource object.
		var secret = this.options.selectedItem.Secret[0].data;
		// Request decryption. (delegated to plugin).
		mad.bus.trigger('passbolt.secret.decrypt', secret);
	}
});

export default ResourceSidebar;