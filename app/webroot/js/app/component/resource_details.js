import 'mad/view/component/tree';
import 'app/view/component/resource_details';
import 'app/component/comments';
import 'app/component/sidebar_section';
import 'app/component/sidebar_section/tags';
import 'app/component/sidebar_section/description';
import 'app/view/template/component/resource_details.ejs!';

/**
 * @inherits mad.Component
 * @parent index
 *
 * @constructor
 * Creates a new Resource details controller
 *
 * @param {HTMLElement} element the element this instance operates on.
 * @param {Object} [options] option values for the controller.  These get added to
 * this.options and merged with defaults static variable
 * @return {passbolt.component.ResourceDetails}
 */
var ResourceDetails = passbolt.component.ResourceDetails = mad.Component.extend('passbolt.component.ResourceDetails', /** @static */ {
	defaults: {
		label: 'Resource Details',
		viewClass: passbolt.view.component.ResourceDetails,
		// the resource to bind the component on
		resource: null,
		// the selected resources, you can pass an existing list as parameter of the constructor to share the same list
		selectedRs: new can.Model.List()
	}

}, /** @prototype */ {

	/**
	 * before start hook.
	 * @return {void}
	 */
	beforeRender: function () {
		this._super();
		// pass the new resource to the view
		this.setViewData('resource', this.options.resource);
		// pass the secret strength label to the view
		var secretStrength = passbolt.model.SecretStrength.getSecretStrength(this.options.resource.Secret.data);
		this.setViewData('secretStrength', secretStrength);
	},

	/**
	 * Called right after the start function
	 * @return {void}
	 * @see {mad.controller.ComponentController}
	 */
	afterStart: function () {
		// Instantiate the description controller for the current resource.
		var descriptionController = new passbolt.component.sidebarSection.Description($('#js_rs_details_description', this.element), {
			'resource': this.options.resource
		});
		descriptionController.start();

		// Instantiate the comments controller for the current resource.
		var commentsController = new passbolt.component.Comments($('#js_rs_details_comments', this.element), {
			'resource': this.options.resource,
			'foreignModel': 'Resource',
			'foreignId': this.options.resource.id
		});
		commentsController.start();

		//// Instantiate the item tags controller for the current resource.
		//var sidebarTagsController = new passbolt.component.sidebarSection.SidebarSectionTagsController($('#js_rs_details_tags', this.element), {
		//	'instance': this.options.resource,
		//	'foreignModel': 'Resource',
		//	'foreignId': this.options.resource.id
		//});
		//sidebarTagsController.start();
	},

	/**
	 * Load details of a resource
	 * @param {passbolt.model.Resource} resource The resource to load
	 * @return {void}
	 */
	load: function (resource) {
		// push the new resource in the options to be able to listen the resource
		// change in the function name
		this.options.resource = resource;
		// If the component has not been already started
		if (this.state.is(null)) {
			this.start();
		} else {
			// refresh the component -> afterStart
			this.refresh();
		}
		// Some options changed, make the controller able to listen changes on this new options
		this.on();
	},

	/**
	 * Check if the component is disabled or it is planned to disable it right after
	 * its start
	 * @return {boolean}
	 */
	isDisabled: function() {
		// if the component is disabled
		if(this.state.is('disabled') ||
			// OR the component is not started AND it will be disabled right after its start
			(this.state.is(null) &&
				(this.options.state == 'disabled' ||
					($.isArray(this.options.state) && this.options.state.indexOf('disabled') != -1)
				)
			)
		) {
			return true;
		}
		return false;
	},

	/* ************************************************************** */
	/* LISTEN TO THE STATE CHANGES */
	/* ************************************************************** */

	/**
	 * Listen to the change relative to the state Ready
	 * @param {boolean} go Enter or leave the state
	 * @return {void}
	 */
	stateReady: function(go) {
		if(go) {
			// because by default the component is hidden (see associated ejs)
			this.view.show();
		}
		this._super(go);
	},

	/**
	 * A password has been clicked.
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @return {void}
	 */
	' password_clicked': function (el, ev) {
		// Get secret out of Resource object.
		var secret = this.options.selectedRs[0].Secret[0].data;
		// Request decryption. (delegated to plugin).
		mad.bus.trigger('passbolt.secret.decrypt', secret);
	},

	/* ************************************************************** */
	/* LISTEN TO THE MODEL EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when an resource is updated
	 * @param {passbolt.model.Resource} resource The updated resource
	 * @return {void}
	 */
	'{resource} updated': function (resource) {
		// The reference of the resource does not change, refresh the component
		if(!this.isDisabled()) {
			this.refresh();
		}
	},

	/* ************************************************************** */
	/* LISTEN TO THE APP EVENTS */
	/* ************************************************************** */

	/**
	 * Observe when the user desire to hide the sidebar
	 * @param {passbolt.model.Resource} resource The updated resource
	 * @return {void}
	 */
	'{mad.bus} workspace_showSidebar': function(el, ev, show) {
		// @todo fixed in future canJs.
		if (!this.element) return;

		if (show) {
			if(this.state.is(null)) {
				this.options.state = 'ready';
			} else {
				this.setState('ready');
				// and if one resource has been selected
				if (this.options.selectedRs.length == 1) {
					this.refresh();
				}
			}
		} else {
			if(this.state.is(null)) {
				this.options.state = ['hidden', 'disabled'];
			} else {
				this.setState(['hidden', 'disabled']);
			}
		}
	},

	/**
	 * Observe when a resource is selected
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @param {passbolt.model.Resource} resource The selected resource
	 * @return {void}
	 */
	'{selectedRs} add': function (el, ev, resource) {
		// if more than one resource selected, or no resource selected
		if (this.options.selectedRs.length == 0 || this.options.selectedRs.length > 1) {
			this.options.resource = null;
			if(!this.isDisabled()) {
				this.setState('hidden');
			}

		// else if only 1 resource selected show the details
		} else {
			// load the only one resource
			this.options.resource = this.options.selectedRs[0];
			if(!this.isDisabled()) {
				this.load(this.options.resource);
				this.setState('ready');
			}
		}
	},

	/**
	 * Observe when a resource is unselected
	 * @param {HTMLElement} el The element the event occured on
	 * @param {HTMLEvent} ev The event which occured
	 * @param {passbolt.model.Resource} resource The unselected resource
	 * @return {void}
	 */
	'{selectedRs} remove': function (el, ev, resource) {
		// if more than one resource selected, or no resource selected
		if (this.options.selectedRs.length == 0 || this.options.selectedRs.length > 1) {
			this.options.resource = null;
			// if the component is not disabled, hide it
			// and the component has already been started
			if(!this.isDisabled() && !this.state.is(null)) {
				this.setState('hidden');
			}

			// else if only 1 resource selected show the details
		} else {
			// load the only one resource
			this.options.resource = this.options.selectedRs[0];
			// if the component is not disabled, load the resource and display the component
			if(!this.isDisabled()) {
				this.load(this.options.resource);
				this.setState('ready');
			}
		}
	}

});

export default ResourceDetails;